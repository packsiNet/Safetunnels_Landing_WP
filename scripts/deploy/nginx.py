#!/usr/bin/env python3
"""Manage one owned vhost; validate/reload transactionally without touching other sites."""
import argparse
import pathlib
import re
import subprocess

MARKER = '# Managed by SafeTunnels Docker deployment'

def render(domain, port, tls):
    if not re.fullmatch(r'(?=.{1,253}$)[a-z0-9]+(?:[a-z0-9.-]*[a-z0-9])?', domain) or '..' in domain:
        raise ValueError('Invalid domain')
    if not 1024 <= int(port) <= 65535:
        raise ValueError('Invalid local HTTP port')
    challenge = 'location ^~ /.well-known/acme-challenge/ { root /var/lib/safetunnels-acme; }'
    proxy = f'''location / {{
        proxy_pass http://127.0.0.1:{port};
        proxy_set_header Host $host;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_read_timeout 90s;
        client_max_body_size 32m;
    }}'''
    http = f'''{MARKER}
server {{
    listen 80;
    listen [::]:80;
    server_name {domain};
    {challenge}
    {'location / { return 301 https://$host$request_uri; }' if tls else proxy}
}}
'''
    if not tls:
        return http
    return http + f'''server {{
    listen 443 ssl;
    listen [::]:443 ssl;
    server_name {domain};
    ssl_certificate /etc/letsencrypt/live/safetunnels-{domain}/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/safetunnels-{domain}/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    {proxy}
}}
'''

def install(domain, port, tls, directory='/etc/nginx/conf.d'):
    target = pathlib.Path(directory) / f'safetunnels-{domain}.conf'
    dump = subprocess.check_output(['nginx', '-T'], stderr=subprocess.DEVNULL, text=True)
    if target.exists() and not target.read_text().startswith(MARKER):
        raise ValueError('Refusing to overwrite an unmanaged Nginx config.')
    # Remove our own config from the dump, then reject any overlapping vhost.
    parts = re.split(r'(?m)^# configuration file (.+):\n', dump)
    other = ''.join(parts[i + 1] for i in range(1, len(parts), 2) if parts[i] != str(target))
    for names in re.findall(r'\bserver_name\s+([^;]+);', other):
        for name in names.split():
            if name == domain or name == '.' + domain or (name.startswith('*.') and domain.endswith(name[1:])) or name.startswith('~'):
                raise ValueError('Domain overlaps another Nginx vhost (or regex vhost). Review it before deployment.')
    # Ubuntu/Debian default includes conf.d. Custom layouts must include it explicitly.
    if not re.search(r'include\s+/etc/nginx/conf\.d/\*\.conf\s*;', dump):
        raise ValueError('Nginx must include /etc/nginx/conf.d/*.conf inside http {}.')
    old = target.read_bytes() if target.exists() else None
    target.parent.mkdir(parents=True, exist_ok=True)
    staging = target.with_suffix('.pending')
    staging.write_text(render(domain, port, tls))
    staging.chmod(0o644)
    staging.replace(target)
    try:
        subprocess.run(['nginx', '-t'], check=True)
        subprocess.run(['systemctl', 'reload', 'nginx'], check=True)
    except Exception:
        if old is None: target.unlink(missing_ok=True)
        else: target.write_bytes(old)
        subprocess.run(['nginx', '-t'], check=False)
        subprocess.run(['systemctl', 'reload', 'nginx'], check=False)
        raise

if __name__ == '__main__':
    parser = argparse.ArgumentParser()
    parser.add_argument('domain')
    parser.add_argument('port', type=int)
    parser.add_argument('--tls', action='store_true')
    args = parser.parse_args()
    install(args.domain, args.port, args.tls)
