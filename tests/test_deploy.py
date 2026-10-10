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
        env = dict(APP_URL='https://example.org', SERVER_HOST='server.example.org', SERVER_USER='deploy', SERVER_SSH_KEY='private', SERVER_KNOWN_HOSTS='verified', RELEASE_SHA=SHA, ARTIFACT='artifact.tar.gz')
        with patch.dict(os.environ, env, clear=True):
            self.assertEqual(deploy.config()['SERVER_PORT'], '22')
            del os.environ['SERVER_KNOWN_HOSTS']
            with self.assertRaises(ValueError):
                deploy.config()

    def test_cloudflare_zone_optional_but_must_be_valid_and_https_required(self):
        env = dict(APP_URL='https://example.org', SERVER_HOST='server.example.org', SERVER_USER='deploy', SERVER_SSH_KEY='private', SERVER_KNOWN_HOSTS='verified', RELEASE_SHA=SHA, ARTIFACT='artifact.tar.gz', CLOUDFLARE_API_TOKEN='token')
        with patch.dict(os.environ, env, clear=True):
            deploy.config()
            os.environ['CLOUDFLARE_ZONE_ID'] = 'invalid'
            with self.assertRaises(ValueError): deploy.config()
            os.environ['CLOUDFLARE_ZONE_ID'] = 'b' * 32
            deploy.config()
            os.environ['APP_URL'] = 'http://example.org'
            with self.assertRaises(ValueError): deploy.config()

nginx = module('nginx')

class NginxTests(unittest.TestCase):
    def test_tls_and_acme_proxy_are_limited_to_own_domain(self):
        content = nginx.render('site.example.org', 18080, True)
        self.assertIn('server_name site.example.org;', content)
        self.assertIn('127.0.0.1:18080', content)
        self.assertIn('location ^~ /.well-known/acme-challenge/', content)
        self.assertIn('/etc/letsencrypt/live/safetunnels-site.example.org/', content)
        self.assertNotIn('default_server', content)
        with self.assertRaises(ValueError): nginx.render('example.org;evil', 80, True)

    def test_unmanaged_or_conflicting_site_never_overwritten(self):
        with tempfile.TemporaryDirectory() as d:
            target = pathlib.Path(d) / 'safetunnels-example.org.conf'
            target.write_text('existing site')
            dump = '# configuration file /etc/nginx/nginx.conf:\ninclude /etc/nginx/conf.d/*.conf;\n'
            with patch.object(nginx.subprocess, 'check_output', return_value=dump):
                with self.assertRaises(ValueError): nginx.install('example.org', 18080, False, d)
            self.assertEqual(target.read_text(), 'existing site')
            target.unlink()
            dump += '# configuration file /etc/nginx/conf.d/other.conf:\nserver { server_name example.org; }\n'
            with patch.object(nginx.subprocess, 'check_output', return_value=dump):
                with self.assertRaises(ValueError): nginx.install('example.org', 18080, False, d)
            self.assertFalse(target.exists())

    def test_failed_reload_restores_existing_managed_config(self):
        with tempfile.TemporaryDirectory() as d:
            target = pathlib.Path(d) / 'safetunnels-example.org.conf'
            original = nginx.MARKER + '\nold configuration'
            target.write_text(original)
            dump = '# configuration file /etc/nginx/nginx.conf:\ninclude /etc/nginx/conf.d/*.conf;\n'
            with patch.object(nginx.subprocess, 'check_output', return_value=dump), patch.object(nginx.subprocess, 'run', side_effect=[subprocess.CalledProcessError(1, 'nginx'), None, None]):
                with self.assertRaises(subprocess.CalledProcessError): nginx.install('example.org', 18080, True, d)
            self.assertEqual(target.read_text(), original)

class DnsTests(unittest.TestCase):
    def test_conflicting_dns_never_replaced(self):
        cfg = {'DOMAIN': 'app.example.org', 'SERVER_HOST': '1.1.1.1'}
        with patch.dict(os.environ, {'CLOUDFLARE_API_TOKEN': 'token', 'CLOUDFLARE_ZONE_ID': 'b' * 32}, clear=True), patch.object(deploy, 'cloudflare', side_effect=[{'name': 'example.org'}, [{'type': 'A', 'content': '8.8.8.8'}]]) as api:
            with self.assertRaises(ValueError): deploy.prepare_dns(cfg)
            self.assertTrue(all(call.args[0] == 'GET' for call in api.call_args_list))

    def test_missing_dns_created_without_modifying_zone_ssl(self):
        cfg = {'DOMAIN': 'app.example.org', 'SERVER_HOST': '1.1.1.1'}
        with patch.dict(os.environ, {'CLOUDFLARE_API_TOKEN': 'token', 'CLOUDFLARE_ZONE_ID': 'b' * 32}, clear=True), patch.object(deploy, 'cloudflare', side_effect=[{'name': 'example.org'}, [], {}]) as api:
            deploy.prepare_dns(cfg)
            self.assertEqual(api.call_args.args[0], 'POST')
            self.assertEqual(api.call_args.args[2]['proxied'], False)
            self.assertTrue(all('/settings/' not in call.args[1] for call in api.call_args_list))

DOCKER_FAKE = '''#!/usr/bin/env python3
import json, os, pathlib, sys
name=pathlib.Path(sys.argv[0]).name
args=sys.argv[1:]
base=pathlib.Path(os.environ['TEST_BASE'])
with (base/'calls').open('a') as f:f.write(name+' '+' '.join(args)+'\\n')
if name=='docker':
 if 'exec' in args and 'db' in args:
  if os.environ.get('FAIL_BACKUP'):sys.exit(1)
  print('database snapshot')
 elif 'up' in args and 'wordpress' in args and os.environ.get('FAIL_START'):
  env=(base/'state/.env').read_text()
  sys.exit(0 if 'THEME_RELEASE='+str(base/'state/releases/old')+'\\n' in env else 1)
elif name=='curl':
 out=pathlib.Path(args[args.index('-o')+1])
 if any('rest_route=' in arg for arg in args):out.write_text(json.dumps({'revision':'wrong' if os.environ.get('FAIL_HEALTH') else os.environ['TEST_SHA'],'theme':'safetunnels'}))
 else:out.write_text('<link id="safetunnels-landing-css">')
'''

class DockerRemoteTests(unittest.TestCase):
    def run_deploy(self, failure=None, initial=False):
        with tempfile.TemporaryDirectory() as d:
            base=pathlib.Path(d)
            state=base/'state'
            old=state/'releases/old'
            old.mkdir(parents=True)
            (state/'.safetunnels-managed').touch()
            (state/'settings').write_text('example.org:18080\n')
            (state/'images.env').write_text('WORDPRESS_IMAGE=wordpress@sha256:abc\nDATABASE_IMAGE=mariadb@sha256:def\nCLI_IMAGE=wordpress@sha256:ghi\n')
            if not initial:
                (state/'compose.yml').write_text('previous compose configuration')
                (state/'.env').write_text('THEME_RELEASE='+str(old)+'\n')
            theme=base/'theme'
            theme.mkdir()
            (theme/'functions.php').write_text('<?php')
            (theme/'style.css').write_text('test')
            archive=base/'theme.tar.gz'
            digest=package.package(theme,archive,SHA)
            payload=base/'payload'
            payload.mkdir()
            # Isolate privileged OS boundaries; execute the release state machine unchanged.
            script=(ROOT/'scripts/deploy/docker-remote.sh').read_text()
            script='\n'.join(line for line in script.splitlines() if not line.startswith('[[ $EUID == 0 ]]'))+'\n'
            script=script.replace('/var/lib/safetunnels-acme',str(base/'acme')).replace('/etc/letsencrypt',str(base/'letsencrypt'))
            (payload/'docker-remote.sh').write_text(script)
            (payload/'provision.sh').write_text('#!/bin/bash\nexit 0\n')
            (payload/'nginx.py').write_text('pass\n')
            (payload/'compose.yml').write_text('test compose')
            binaries=base/'bin'
            binaries.mkdir()
            for name in ['docker','curl','certbot','sleep']:
                path=binaries/name;path.write_text(DOCKER_FAKE);path.chmod(0o755)
            env=dict(os.environ,PATH=str(binaries)+':'+os.environ['PATH'],TEST_BASE=str(base),TEST_SHA=SHA)
            if failure:env[failure]='1'
            result=subprocess.run(['bash',str(payload/'docker-remote.sh'),str(archive),digest,SHA,'https://example.org','example.org',str(state),'18080','admin@example.org','admin'],env=env,text=True,capture_output=True)
            current=(state/'.env').read_text()
            calls=(base/'calls').read_text()
            if failure:
                self.assertNotEqual(result.returncode,0,result.stdout+result.stderr)
                if not initial:
                    self.assertIn('THEME_RELEASE='+str(old)+'\n',current)
                    self.assertEqual((state/'compose.yml').read_text(),'previous compose configuration')
            else:
                self.assertEqual(result.returncode,0,result.stdout+result.stderr)
                self.assertIn(SHA,current)
                self.assertEqual((state/'current-revision').read_text().strip(),SHA)
            self.assertNotIn('down --volumes',calls)
            self.assertNotIn('db import',calls)
            if not initial and failure!='FAIL_BACKUP':
                self.assertTrue(list((state/'backups').glob('*.sql')))

    def test_existing_site_success(self):self.run_deploy()
    def test_initial_install_success(self):self.run_deploy(initial=True)
    def test_bad_health_restores_old_theme_mount(self):self.run_deploy('FAIL_HEALTH')
    def test_container_start_failure_restores_old_theme_mount(self):self.run_deploy('FAIL_START')
    def test_backup_failure_does_not_switch_mount(self):self.run_deploy('FAIL_BACKUP')
