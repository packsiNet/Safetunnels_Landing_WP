#!/usr/bin/env python3
"""Verified SSH transport and Cloudflare cache invalidation. No third-party SDKs."""
import hashlib
import json
import os
import pathlib
import re
import shlex
import subprocess
import sys
import tempfile
import urllib.parse
import urllib.request
import time

def required(name):
    value = os.environ.get(name, '').strip()
    if not value:
        raise ValueError(f'Missing configuration: {name}')
    return value

def config():
    values = {key: required(key) for key in ('APP_URL', 'SERVER_HOST', 'SERVER_USER', 'SERVER_SSH_KEY', 'SERVER_KNOWN_HOSTS', 'WP_ROOT', 'RELEASE_SHA', 'ARTIFACT')}
    values['SERVER_PORT'] = os.environ.get('SERVER_PORT', '').strip() or '22'
    if not re.fullmatch(r'[A-Za-z0-9][A-Za-z0-9.:-]*', values['SERVER_HOST']):
        raise ValueError('Invalid SERVER_HOST; use a hostname or IP without protocol.')
    if not re.fullmatch(r'[A-Za-z_][A-Za-z0-9_-]*', values['SERVER_USER']):
        raise ValueError('Invalid SERVER_USER.')
    if not values['SERVER_PORT'].isdigit() or not 1 <= int(values['SERVER_PORT']) <= 65535:
        raise ValueError('Invalid SERVER_PORT.')
    url = urllib.parse.urlsplit(values['APP_URL'])
    if url.scheme != 'https' or not url.hostname or url.username or url.password or url.query or url.fragment:
        raise ValueError('APP_URL must be a canonical HTTPS URL without credentials/query/fragment.')
    if not values['WP_ROOT'].startswith('/'):
        raise ValueError('WP_ROOT must be an absolute server path.')
    if not re.fullmatch(r'[a-f0-9]{40}', values['RELEASE_SHA']):
        raise ValueError('Invalid release SHA.')
    if os.environ.get('CLOUDFLARE_API_TOKEN') and not re.fullmatch(r'[a-fA-F0-9]{32}', os.environ.get('CLOUDFLARE_ZONE_ID', '')):
        raise ValueError('CLOUDFLARE_ZONE_ID is required when CLOUDFLARE_API_TOKEN is configured.')
    return values

def purge_cache(app_url):
    token = os.environ.get('CLOUDFLARE_API_TOKEN', '').strip()
    if not token:
        print('Cloudflare purge skipped: no token configured.')
        return
    zone = required('CLOUDFLARE_ZONE_ID')
    # Full-zone purge includes cached old asset URLs. Use a zone dedicated to this site.
    request = urllib.request.Request(
        f'https://api.cloudflare.com/client/v4/zones/{zone}/purge_cache',
        data=json.dumps({'purge_everything': True}).encode(),
        headers={'Authorization': 'Bearer ' + token, 'Content-Type': 'application/json'}, method='POST')
    for attempt in range(3):
        try:
            with urllib.request.urlopen(request, timeout=30) as response:
                body = json.load(response)
            if not body.get('success'):
                raise RuntimeError('Cloudflare returned success=false.')
            print('Cloudflare zone cache purged.')
            return
        except Exception:
            if attempt == 2:
                raise RuntimeError('Release is deployed, but Cloudflare cache purge failed. Check token scope/zone ID.') from None
            time.sleep(3 * (attempt + 1))

def main():
    cfg = config()
    artifact = pathlib.Path(cfg['ARTIFACT']).resolve()
    expected = artifact.with_suffix(artifact.suffix + '.sha256').read_text().strip()
    digest = hashlib.sha256(artifact.read_bytes()).hexdigest()
    if digest != expected:
        raise ValueError('Artifact checksum mismatch.')
    with tempfile.TemporaryDirectory(prefix='safetunnels-ssh-') as directory:
        directory = pathlib.Path(directory)
        key, hosts = directory / 'key', directory / 'known_hosts'
        key.write_text(cfg['SERVER_SSH_KEY'] + '\n')
        hosts.write_text(cfg['SERVER_KNOWN_HOSTS'] + '\n')
        key.chmod(0o600)
        hosts.chmod(0o600)
        common = ['-i', str(key), '-o', 'IdentitiesOnly=yes', '-o', 'BatchMode=yes', '-o', 'StrictHostKeyChecking=yes',
                  '-o', f'UserKnownHostsFile={hosts}', '-o', 'ConnectTimeout=15', '-o', 'ServerAliveInterval=15', '-o', 'ServerAliveCountMax=4']
        subprocess.run(['ssh-keygen', '-y', '-P', '', '-f', str(key)], check=True, stdout=subprocess.DEVNULL)
        host = cfg['SERVER_HOST']
        target = cfg['SERVER_USER'] + '@' + host
        ssh = ['ssh', *common, '-p', cfg['SERVER_PORT'], target]
        remote_dir = subprocess.check_output([*ssh, 'mktemp -d /tmp/safetunnels-deploy.XXXXXXXX'], text=True).strip()
        if not re.fullmatch(r'/tmp/safetunnels-deploy\.[A-Za-z0-9]+', remote_dir):
            raise ValueError('Unexpected temporary path returned by server.')
        try:
            scp_host = '[' + host + ']' if ':' in host else host
            destination = cfg['SERVER_USER'] + '@' + scp_host + ':' + remote_dir + '/'
            subprocess.run(['scp', *common, '-P', cfg['SERVER_PORT'], str(artifact), 'scripts/deploy/remote.sh', destination], check=True)
            args = ['bash', remote_dir + '/remote.sh', remote_dir + '/' + artifact.name, digest, cfg['RELEASE_SHA'], cfg['WP_ROOT'], cfg['APP_URL'], os.environ.get('DEPLOY_ROOT', '')]
            subprocess.run([*ssh, shlex.join(args)], check=True)
        finally:
            subprocess.run([*ssh, 'rm -rf -- ' + shlex.quote(remote_dir)], check=False)
    purge_cache(cfg['APP_URL'])

if __name__ == '__main__':
    try:
        main()
    except (ValueError, RuntimeError, OSError, subprocess.CalledProcessError) as error:
        # Never print environment values or the SSH private key.
        print(f'Deployment failed: {error}', file=sys.stderr)
        sys.exit(1)
