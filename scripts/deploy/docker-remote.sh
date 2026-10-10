#!/usr/bin/env bash
set -Eeuo pipefail
umask 022
archive=$1 digest=$2 revision=$3 app_url=$4 domain=$5 root=$6 port=$7 email=$8 admin_user=$9
[[ $EUID == 0 ]] || { echo 'Root or passwordless sudo required for initial provisioning.' >&2; exit 2; }
[[ $revision =~ ^[a-f0-9]{40}$ && $digest =~ ^[a-f0-9]{64}$ && $root =~ ^/[a-zA-Z0-9/_-]+$ && $port =~ ^[0-9]+$ ]] || exit 2
(( port >= 1024 && port <= 65535 )) || exit 2
payload=$(cd "$(dirname "$0")" && pwd)
printf '%s  %s\n' "$digest" "$archive" | sha256sum --check --status
# Never assume an existing unrelated installation belongs to this deployment.
if [[ -e $root && ! -f $root/.safetunnels-managed ]]; then echo 'DEPLOY_ROOT already exists and is unmanaged.' >&2; exit 2; fi
install -d -m 755 "$root"
touch "$root/.safetunnels-managed"
exec 9>"$root/deploy.lock"
flock -w 300 9
bash "$payload/provision.sh"
install -d -m 755 "$root/releases" /var/lib/safetunnels-acme
install -d -m 700 "$root/secrets" "$root/backups"
# A unique compose project prevents affecting other Docker stacks.
project="safetunnels-$(printf '%s' "$root" | sha256sum | cut -c1-12)"
previous=''
[[ ! -f $root/.env ]] || previous=$(sed -n 's/^THEME_RELEASE=//p' "$root/.env")
if [[ -n $previous ]]; then
  previous_port=$(sed -n 's/^HTTP_PORT=//p' "$root/.env")
  [[ ${previous_port:-$port} == "$port" ]] || { echo 'HTTP_PORT changed; explicit port migration is required.' >&2; exit 2; }
  # APP_URL authorizes moving this managed installation to another domain.
  # Existing containers own the unchanged port, even if settings was removed.
else
  python3 - "$port" <<'PYTHON'
import socket,sys
with socket.socket() as s: s.bind(('127.0.0.1',int(sys.argv[1])))
PYTHON
fi
for secret in db_password db_root_password admin_password; do
  if [[ ! -s "$root/secrets/$secret" ]]; then openssl rand -hex 32 > "$root/secrets/$secret"; fi
  chmod 444 "$root/secrets/$secret"
done
# Secret files are readable only through their explicitly mounted container paths;
# the host parent directory stays root-only (700).
if [[ ! -f $root/images.env ]]; then
  : > "$root/images.env.pending"
  for item in 'WORDPRESS_IMAGE wordpress:php8.3-apache' 'DATABASE_IMAGE mariadb:11.4' 'CLI_IMAGE wordpress:cli-php8.3'; do
    read -r variable image <<< "$item"
    docker pull "$image"
    pin=$(docker image inspect --format '{{index .RepoDigests 0}}' "$image")
    [[ $pin == *@sha256:* ]]
    printf '%s=%s\n' "$variable" "$pin" >> "$root/images.env.pending"
  done
  mv "$root/images.env.pending" "$root/images.env"
fi
stamp="$(date -u +%Y%m%dT%H%M%SZ)-$$"
release="$root/releases/$revision-$stamp"
mkdir "$release"
# The package is allowlisted in CI and checksummed before extraction.
python3 - "$archive" "$release" <<'PY'
import pathlib,sys,tarfile
root=pathlib.Path(sys.argv[2])
with tarfile.open(sys.argv[1]) as archive:
 for member in archive:
  if member.issym() or member.islnk() or not (member.isfile() or member.isdir()) or member.name.startswith('/') or '..' in pathlib.PurePosixPath(member.name).parts:
   raise ValueError('Unsafe theme archive member')
 archive.extractall(root)
PY
find "$release" -type d -exec chmod 755 {} +
find "$release" -type f -exec chmod 644 {} +
previous_env=""
if [[ -f $root/.env ]]; then
  previous_env="$root/backups/env-$stamp"
  cp -p "$root/.env" "$previous_env"
fi
previous_compose=""
if [[ -f $root/compose.yml ]]; then
  previous_compose="$root/backups/compose-$stamp.yml"
  cp "$root/compose.yml" "$previous_compose"
fi
compose=(docker compose --project-name "$project" --project-directory "$root" --file "$root/compose.yml")
# Lint with the exact pinned PHP image before switching the service.
wp_image=$(sed -n 's/^WORDPRESS_IMAGE=//p' "$root/images.env")
docker run --rm --entrypoint sh --mount "type=bind,src=$release,dst=/theme,readonly" "$wp_image" -c 'find /theme -name "*.php" > /tmp/php-files; while IFS= read -r f; do php -l "$f" >/dev/null || exit 1; done < /tmp/php-files'
write_env() {
  cat "$root/images.env" > "$root/.env.next"
  printf 'APP_URL=%s\nHTTP_PORT=%s\nTHEME_RELEASE=%s\n' "$app_url" "$port" "$1" >> "$root/.env.next"
  chmod 600 "$root/.env.next"
  mv "$root/.env.next" "$root/.env"
}
backup=''
if [[ -n $previous ]]; then
  # Recover interrupted initial provisioning before attempting a database backup.
  "${compose[@]}" up -d --wait --wait-timeout 180 db
  # Consistent SQL snapshot; database volumes are never replaced on application deploy.
  backup="$root/backups/$stamp-$revision.sql"
  umask 077
  # Expansion intentionally occurs inside the database container.
  # shellcheck disable=SC2016
  "${compose[@]}" exec -T db sh -c 'exec mariadb-dump --single-transaction --routines --triggers -uroot --password="$(cat /run/secrets/db_root_password)" wordpress' > "$backup"
  [[ -s $backup ]]
  umask 022
fi
switched=0
rollback() {
  result=$?
  trap - EXIT
  if (( result != 0 && switched )); then
    if [[ -n $previous ]]; then
      echo 'Restoring previous Docker theme release.' >&2
      [[ -z $previous_compose ]] || cp "$previous_compose" "$root/compose.yml"
      if [[ -n $previous_env ]]; then cp -p "$previous_env" "$root/.env"; else write_env "$previous"; fi
      "${compose[@]}" up -d --wait --wait-timeout 180 wordpress || true
      "${compose[@]}" run --rm -T cli --skip-themes maintenance-mode deactivate || true
      "${compose[@]}" run --rm -T cli cache flush || true
    else
      echo 'Initial deployment failed. Persistent data retained for retry.' >&2
    fi
    echo "Database is not automatically restored. Backup: ${backup:-initial installation}" >&2
  fi
  exit "$result"
}
trap rollback EXIT
switched=1
install -m 644 "$payload/compose.yml" "$root/compose.yml"
write_env "$release"
"${compose[@]}" config --quiet
"${compose[@]}" up -d --wait --wait-timeout 180 db wordpress
cli=("${compose[@]}" run --rm -T cli)
if ! "${cli[@]}" --skip-themes core is-installed >/dev/null 2>&1; then
  "${cli[@]}" core install --url="$app_url" --title=SafeTunnels --admin_user="$admin_user" --admin_email="$email" --skip-email --prompt=admin_password < "$root/secrets/admin_password"
fi
"${cli[@]}" theme activate safetunnels
"${cli[@]}" eval 'if (!function_exists("safetunnels_page_url")) { exit(1); }'
"${cli[@]}" rewrite flush --hard
"${cli[@]}" cache flush
# Create only our domain's vhost, after explicitly checking for overlaps.
# Avoid downgrading HTTPS on subsequent deploys.
nginx_args=("$payload/nginx.py" "$domain" "$port")
[[ ! -f "/etc/letsencrypt/live/safetunnels-$domain/fullchain.pem" ]] || nginx_args+=(--tls)
python3 "${nginx_args[@]}"
certbot certonly --non-interactive --agree-tos --email "$email" --webroot --webroot-path /var/lib/safetunnels-acme --domain "$domain" --cert-name "safetunnels-$domain" --keep-until-expiring
python3 "$payload/nginx.py" "$domain" "$port" --tls
# Renewal uses webroot; other Nginx sites are not rewritten by a Certbot plugin.
install -d -m 755 /etc/letsencrypt/renewal-hooks/deploy
cat > /etc/letsencrypt/renewal-hooks/deploy/safetunnels-nginx-reload <<'HOOK'
#!/bin/sh
nginx -t && systemctl reload nginx
HOOK
chmod 755 /etc/letsencrypt/renewal-hooks/deploy/safetunnels-nginx-reload
# Direct origin TLS check bypasses Cloudflare and proves the origin certificate.
healthy=0
for attempt in {1..12}; do
  echo "Verifying Docker release ($attempt/12)..."
  if curl --fail --silent --show-error --max-time 15 --resolve "$domain:443:127.0.0.1" "$app_url/?rest_route=/safetunnels/v1/deployment&deploy_check=$revision" -o "$root/probe.json" &&
    python3 - "$root/probe.json" "$revision" <<'PY'
import json,sys
with open(sys.argv[1]) as f:d=json.load(f)
sys.exit(0 if d.get('revision')==sys.argv[2] and d.get('theme')=='safetunnels' else 1)
PY
  then healthy=1; break; fi
  sleep 3
done
(( healthy )) || { echo 'Origin release verification failed.' >&2; exit 1; }
for path in / /tunnels/ /tunnels/privacy/ /peeko/privacy/; do
  curl --fail --silent --show-error --max-time 20 --resolve "$domain:443:127.0.0.1" "$app_url$path?deploy_check=$revision" -o "$root/page.html"
  grep -q 'safetunnels-studio\|safetunnels-landing' "$root/page.html"
  if grep -q 'Fatal error\|There has been a critical error' "$root/page.html"; then exit 1; fi
done
rm -f "$root/page.html" "$root/probe.json"
printf '%s\n' "$domain:$port" > "$root/settings"
printf '%s\n' "$revision" > "$root/current-revision"
printf '%s\n' "$previous" > "$root/previous-release"
echo "Docker WordPress and origin TLS verified: $revision"
