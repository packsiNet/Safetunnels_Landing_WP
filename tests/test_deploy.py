import hashlib
import importlib.util
import json
import os
import pathlib
import subprocess
import tarfile
import tempfile
import unittest
from unittest.mock import patch

ROOT = pathlib.Path(__file__).resolve().parents[1]
def module(name):
    spec = importlib.util.spec_from_file_location(name, ROOT / 'scripts/deploy' / (name + '.py'))
    result = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(result)
    return result

package = module('package')
deploy = module('deploy')
SHA = 'a' * 40

class PackageTests(unittest.TestCase):
    def test_artifact_excludes_private_files_and_contains_revision(self):
        with tempfile.TemporaryDirectory() as temporary:
            root = pathlib.Path(temporary)
            for name in ['functions.php', 'style.css', 'wp-config.php', '.env', 'database.sqlite', 'run-local.sh', '.aws/credentials', '.local-wordpress/login.txt', 'assets/css/site.css']:
                path = root / name
                path.parent.mkdir(parents=True, exist_ok=True)
                path.write_text('<?php' if name == 'functions.php' else 'test')
            output = root / 'dist/theme.tar.gz'
            digest = package.package(root, output, SHA)
            with tarfile.open(output) as archive:
                self.assertEqual(set(archive.getnames()), {'functions.php', 'style.css', 'assets/css/site.css', 'release.json'})
                self.assertEqual(json.load(archive.extractfile('release.json'))['revision'], SHA)
                self.assertIn(SHA.encode(), archive.extractfile('functions.php').read())
                self.assertEqual((root / 'functions.php').read_text(), '<?php')
            self.assertEqual(digest, hashlib.sha256(output.read_bytes()).hexdigest())

    def test_symlink_is_rejected(self):
        with tempfile.TemporaryDirectory() as temporary:
            root = pathlib.Path(temporary)
            (root / 'functions.php').symlink_to('/etc/passwd')
            (root / 'style.css').write_text('test')
            with self.assertRaises(ValueError):
                package.package(root, root / 'out.tar.gz', SHA)

class ConfigurationTests(unittest.TestCase):
    def test_valid_config_and_rejects_unverified_host(self):
        env = dict(APP_URL='https://example.org', SERVER_HOST='server.example.org', SERVER_USER='deploy', SERVER_SSH_KEY='private', SERVER_KNOWN_HOSTS='verified', WP_ROOT='/srv/wordpress', RELEASE_SHA=SHA, ARTIFACT='artifact.tar.gz')
        with patch.dict(os.environ, env, clear=True):
            self.assertEqual(deploy.config()['SERVER_PORT'], '22')
            del os.environ['SERVER_KNOWN_HOSTS']
            with self.assertRaises(ValueError):
                deploy.config()

    def test_cloudflare_requires_exact_zone_and_https_required(self):
        env = dict(APP_URL='https://example.org', SERVER_HOST='server.example.org', SERVER_USER='deploy', SERVER_SSH_KEY='private', SERVER_KNOWN_HOSTS='verified', WP_ROOT='/srv/wordpress', RELEASE_SHA=SHA, ARTIFACT='artifact.tar.gz', CLOUDFLARE_API_TOKEN='token')
        with patch.dict(os.environ, env, clear=True):
            with self.assertRaises(ValueError): deploy.config()
            os.environ['CLOUDFLARE_ZONE_ID'] = 'b' * 32
            deploy.config()
            os.environ['APP_URL'] = 'http://example.org'
            with self.assertRaises(ValueError): deploy.config()

# Exercise actual remote.sh filesystem transitions with fake external services.
FAKE = '''#!/usr/bin/env python3
import json, os, pathlib, sys
name = pathlib.Path(sys.argv[0]).name
args = sys.argv[1:]
base = pathlib.Path(os.environ['TEST_BASE'])
with (base / 'calls').open('a') as f: f.write(name + ' ' + ' '.join(args) + '\\n')
if name == 'wp':
    if 'eval' in args and 'echo get_theme_root();' in args: print(base / 'wordpress/wp-content/themes')
    elif 'option' in args and 'stylesheet' in args: print('safetunnels')
    elif 'export' in args:
        if os.environ.get('FAIL_BACKUP'): sys.exit(1)
        pathlib.Path(args[args.index('export') + 1]).write_text('database backup')
elif name == 'curl':
    out = pathlib.Path(args[args.index('-o') + 1])
    if 'rest_route=' in args[-1]: out.write_text(json.dumps({'revision': os.environ['TEST_SHA'], 'theme': 'safetunnels'}))
    else: out.write_text('<link id="safetunnels-landing-css">')
elif name == 'php' and args[0] == '-r' and 'json_decode' in args[1]:
    sys.exit(1 if os.environ.get('FAIL_HEALTH') else 0)
'''

class RemoteTests(unittest.TestCase):
    def run_remote(self, fail_health=False, fail_backup=False, initial=False, bad_digest=False):
        with tempfile.TemporaryDirectory() as temporary:
            base = pathlib.Path(temporary)
            wp = base / 'wordpress'
            themes = wp / 'wp-content/themes'
            themes.mkdir(parents=True)
            (wp / 'wp-config.php').write_text('config')
            state = base / 'deploy'
            old = state / 'releases/old'
            old.mkdir(parents=True)
            (old / 'style.css').write_text('old code')
            target = themes / 'safetunnels'
            if initial:
                target.mkdir()
                (target / 'style.css').write_text('old code')
            else:
                target.symlink_to(old)
            theme = base / 'theme'
            theme.mkdir()
            (theme / 'functions.php').write_text('<?php')
            (theme / 'style.css').write_text('theme')
            artifact = base / 'theme.tar.gz'
            digest = package.package(theme, artifact, SHA)
            binaries = base / 'bin'
            binaries.mkdir()
            for name in ['wp', 'curl', 'php', 'sleep']:
                path = binaries / name
                path.write_text(FAKE)
                path.chmod(0o755)
            env = dict(os.environ, PATH=str(binaries) + ':' + os.environ['PATH'], TEST_BASE=str(base), TEST_SHA=SHA)
            if fail_health: env['FAIL_HEALTH'] = '1'
            if fail_backup: env['FAIL_BACKUP'] = '1'
            result = subprocess.run(['bash', str(ROOT / 'scripts/deploy/remote.sh'), str(artifact), '0' * 64 if bad_digest else digest, SHA, str(wp), 'https://example.org', str(state)], env=env, text=True, capture_output=True)
            if fail_health or fail_backup or bad_digest:
                self.assertNotEqual(result.returncode, 0, result.stdout + result.stderr)
                self.assertEqual((target / 'style.css').read_text(), 'old code')
            else:
                self.assertEqual(result.returncode, 0, result.stdout + result.stderr)
                self.assertTrue(target.is_symlink())
                self.assertEqual(json.loads((target / 'release.json').read_text())['revision'], SHA)
            calls = (base / 'calls').read_text() if (base / 'calls').exists() else ''
            self.assertNotIn('db import', calls)
            if initial and fail_health:
                self.assertIn('maintenance-mode deactivate', calls)
            if not bad_digest and not fail_backup:
                self.assertEqual(len(list((state / 'backups').glob('*.sql'))), 1)

    def test_successful_atomic_switch(self): self.run_remote()
    def test_failed_probe_restores_previous_release(self): self.run_remote(fail_health=True)
    def test_first_deployment_failure_restores_original_directory(self): self.run_remote(fail_health=True, initial=True)
    def test_backup_failure_never_switches_files(self): self.run_remote(fail_backup=True)
    def test_checksum_failure_never_switches_files(self): self.run_remote(bad_digest=True)

if __name__ == '__main__': unittest.main()
