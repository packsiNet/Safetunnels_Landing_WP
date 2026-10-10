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
import socket
import ipaddress

def required(name):
    value = os.environ.get(name, '').strip()
    if not value:
        raise ValueError(f'Missing configuration: {name}')
    return value

def config():
    values = {key: required(key) for key in ('APP_URL', 'SERVER_HOST', 'SERVER_USER', 'SERVER_SSH_KEY', 'SERVER_KNOWN_HOSTS', 'RELEASE_SHA', 'ARTIFACT')}
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
    if url.path not in ('', '/') or url.port not in (None, 443):
        raise ValueError('Docker provisioning requires an HTTPS domain at the root URL on port 443.')
    domain = url.hostname.lower()
    if not re.fullmatch(r'(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?', domain) or len(domain) > 253 or re.fullmatch(r'[0-9.]+', domain):
        raise ValueError('APP_URL must use a valid public domain.')
    values['DOMAIN'] = domain
    values['APP_URL'] = 'https://' + domain
    values['DEPLOY_ROOT'] = os.environ.get('DEPLOY_ROOT', '').strip() or '/opt/safetunnels'
    if not re.fullmatch(r'/[a-zA-Z0-9/_-]+', values['DEPLOY_ROOT']) or '..' in values['DEPLOY_ROOT'] or values['DEPLOY_ROOT'].rstrip('/') in ('', '/etc', '/var', '/opt', '/srv', '/root', '/home'):
        raise ValueError('DEPLOY_ROOT must be a dedicated absolute path.')
    values['HTTP_PORT'] = os.environ.get('HTTP_PORT', '18080').strip() or '18080'
    if not values['HTTP_PORT'].isdigit() or not 1024 <= int(values['HTTP_PORT']) <= 65535:
        raise ValueError('HTTP_PORT must be between 1024 and 65535.')
    values['ADMIN_EMAIL'] = os.environ.get('ADMIN_EMAIL', '').strip() or 'admin@' + domain
    values['ADMIN_USER'] = os.environ.get('ADMIN_USER', '').strip() or 'safetunnels_admin'
    if not re.fullmatch(r'[A-Za-z0-9_.+-]+@[A-Za-z0-9.-]+', values['ADMIN_EMAIL']) or not re.fullmatch(r'[A-Za-z0-9_-]+', values['ADMIN_USER']):
        raise ValueError('Invalid ADMIN_EMAIL or ADMIN_USER.')
    if not re.fullmatch(r'[a-f0-9]{40}', values['RELEASE_SHA']):
        raise ValueError('Invalid release SHA.')
    if os.environ.get('CLOUDFLARE_ZONE_ID') and not re.fullmatch(r'[a-fA-F0-9]{32}', os.environ['CLOUDFLARE_ZONE_ID']):
        raise ValueError('Invalid CLOUDFLARE_ZONE_ID.')
    return values

def cloudflare(method, path, data=None):
    request = urllib.request.Request('https://api.cloudflare.com/client/v4/' + path,
        data=None if data is None else json.dumps(data).encode(),
        headers={'Authorization': 'Bearer ' + required('CLOUDFLARE_API_TOKEN'), 'Content-Type': 'application/json'}, method=method)
    try:
        with urllib.request.urlopen(request, timeout=30) as response:
            body = json.load(response)
    except Exception:
        raise RuntimeError('Cloudflare API request failed. Check token permissions and connectivity.') from None
    if not body.get('success'):
        raise RuntimeError('Cloudflare API returned success=false.')
    return body['result']

def prepare_dns(cfg):
    if not os.environ.get('CLOUDFLARE_API_TOKEN', '').strip():
        print('No Cloudflare token: domain DNS must already point to the origin server.')
        return
    zone = os.environ.get('CLOUDFLARE_ZONE_ID', '').strip()
    if not zone:
        labels = cfg['DOMAIN'].split('.')
        for index in range(len(labels) - 1):
            results = cloudflare('GET', 'zones?' + urllib.parse.urlencode({'name': '.'.join(labels[index:]), 'status': 'active'}))
            if len(results) == 1:
                zone = results[0]['id']
                break
        if not zone:
            raise ValueError('No Cloudflare zone found for APP_URL.')
        os.environ['CLOUDFLARE_ZONE_ID'] = zone
    details = cloudflare('GET', 'zones/' + zone)
    if cfg['DOMAIN'] != details['name'] and not cfg['DOMAIN'].endswith('.' + details['name']):
        raise ValueError('Cloudflare zone does not belong to APP_URL.')
    origin = os.environ.get('ORIGIN_IP', '').strip()
    if not origin:
        try:
            origin = str(ipaddress.ip_address(cfg['SERVER_HOST']))
        except ValueError:
            addresses = socket.getaddrinfo(cfg['SERVER_HOST'], None, socket.AF_INET, socket.SOCK_STREAM)
            origin = addresses[0][4][0]
    address = ipaddress.ip_address(origin)
    if not address.is_global:
        raise ValueError('ORIGIN_IP must be a public origin IP for automatic DNS.')
    kind = 'A' if address.version == 4 else 'AAAA'
    records = cloudflare('GET', f'zones/{zone}/dns_records?' + urllib.parse.urlencode({'name': cfg['DOMAIN']}))
    relevant = [r for r in records if r['type'] in ('A', 'AAAA', 'CNAME')]
    if relevant:
        if not all(r['type'] == kind and ipaddress.ip_address(r['content']) == address for r in relevant):
            raise ValueError('Domain already has different A/AAAA/CNAME records; refusing to replace an existing site DNS.')
        print('Existing domain DNS points to the requested origin.')
    else:
        cloudflare('POST', f'zones/{zone}/dns_records', {'type': kind, 'name': cfg['DOMAIN'], 'content': str(address), 'ttl': 120, 'proxied': False, 'comment': 'Managed by SafeTunnels deployment'})
        print('Created DNS-only origin record for the application domain.')
    # Avoid changing zone-wide SSL settings: they may belong to other sites.
    if any(r.get('proxied') for r in relevant):
        setting = cloudflare('GET', f'zones/{zone}/settings/ssl')
        if setting['value'] != 'strict':
            raise ValueError('Existing proxied DNS requires Cloudflare Full (strict) SSL. Zone-wide settings are not modified automatically.')

def purge_cache(app_url):
    if not os.environ.get('CLOUDFLARE_API_TOKEN', '').strip():
        print('Cloudflare purge skipped: no token configured.')
        return
    zone = required('CLOUDFLARE_ZONE_ID')
    # Purge only URLs belonging to this app, preserving other sites in the zone.
    paths = ['', '/tunnels/', '/tunnels/privacy/', '/tunnels/terms/', '/tunnels/support/', '/tunnels/contact/', '/tunnels/faq/', '/tunnels/pricing/', '/tunnels/download/', '/tunnels/iranian-apps/', '/tunnels/refund-policy/', '/tunnels/cookie-policy/', '/peeko/privacy/', '/blog/']
    for attempt in range(3):
        try:
            cloudflare('POST', f'zones/{zone}/purge_cache', {'files': [app_url.rstrip('/') + path if path else app_url.rstrip('/') + '/' for path in paths]})
            print('Cloudflare application page cache purged.')
            return
        except RuntimeError:
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
        lookup = host if int(cfg['SERVER_PORT']) == 22 else '[' + host + ']:' + cfg['SERVER_PORT']
        found = subprocess.run(['ssh-keygen', '-F', lookup, '-f', str(hosts)], stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
        if found.returncode != 0:
            raise ValueError('SERVER_KNOWN_HOSTS has no entry matching SERVER_HOST and SERVER_PORT. APP_URL is independent of the SSH host.')
        target = cfg['SERVER_USER'] + '@' + host
        ssh = ['ssh', *common, '-p', cfg['SERVER_PORT'], target]
        remote_dir = subprocess.check_output([*ssh, 'mktemp -d /tmp/safetunnels-deploy.XXXXXXXX'], text=True).strip()
        if not re.fullmatch(r'/tmp/safetunnels-deploy\.[A-Za-z0-9]+', remote_dir):
            raise ValueError('Unexpected temporary path returned by server.')
        try:
            prepare_dns(cfg)
            scp_host = '[' + host + ']' if ':' in host else host
            destination = cfg['SERVER_USER'] + '@' + scp_host + ':' + remote_dir + '/'
            subprocess.run(['scp', *common, '-P', cfg['SERVER_PORT'], str(artifact), 'scripts/deploy/docker-remote.sh', 'scripts/deploy/provision.sh', 'scripts/deploy/nginx.py', 'deploy/docker/compose.yml', destination], check=True)
            args = ([] if cfg['SERVER_USER'] == 'root' else ['sudo', '-n']) + ['bash', remote_dir + '/docker-remote.sh', remote_dir + '/' + artifact.name, digest, cfg['RELEASE_SHA'], cfg['APP_URL'], cfg['DOMAIN'], cfg['DEPLOY_ROOT'], cfg['HTTP_PORT'], cfg['ADMIN_EMAIL'], cfg['ADMIN_USER']]
            subprocess.run([*ssh, shlex.join(args)], check=True)
        finally:
            subprocess.run([*ssh, 'rm -rf -- ' + shlex.quote(remote_dir)], check=False)
    purge_cache(cfg['APP_URL'])
    probe_url = cfg['APP_URL'] + '/?rest_route=/safetunnels/v1/deployment&deploy_check=' + cfg['RELEASE_SHA']
    for attempt in range(6):
        try:
            request = urllib.request.Request(probe_url, headers={'Cache-Control': 'no-cache'})
            with urllib.request.urlopen(request, timeout=20) as response:
                probe = json.load(response)
            if probe.get('revision') == cfg['RELEASE_SHA'] and probe.get('theme') == 'safetunnels':
                print('Public HTTPS endpoint verifies the deployed release.')
                break
        except Exception:
            pass
        if attempt == 5:
            raise RuntimeError('Origin deployment succeeded, but public HTTPS probe failed. Check DNS, firewall and Cloudflare rules.')
        time.sleep(5)

if __name__ == '__main__':
    try:
        main()
    except (ValueError, RuntimeError, OSError, subprocess.CalledProcessError) as error:
        # Never print environment values or the SSH private key.
        print(f'Deployment failed: {error}', file=sys.stderr)
        sys.exit(1)
