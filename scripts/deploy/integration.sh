#!/usr/bin/env bash
# Disposable real WordPress + MariaDB test. Used only in CI, never production.
set -Eeuo pipefail
root=$(mktemp -d /tmp/safetunnels-integration.XXXXXXXX)
project="safetunnels-ci-$$"
compose=(docker compose --project-name "$project" --project-directory "$root" --file "$root/compose.yml")
cleanup() {
  result=$?
  trap - EXIT
  if (( result != 0 )); then "${compose[@]}" logs --tail 60 || true; fi
  "${compose[@]}" down --volumes --remove-orphans || true
  rm -rf "$root"
  exit "$result"
}
trap cleanup EXIT
mkdir "$root/theme" "$root/secrets"
tar -xzf dist/safetunnels.tar.gz -C "$root/theme"
chmod -R a+rX "$root/theme"
for secret in db_password db_root_password admin_password; do
  openssl rand -hex 24 > "$root/secrets/$secret"
  chmod 444 "$root/secrets/$secret"
done
cp deploy/docker/compose.yml "$root/compose.yml"
cat > "$root/.env" <<ENV
WORDPRESS_IMAGE=wordpress:php8.3-apache
DATABASE_IMAGE=mariadb:11.4
CLI_IMAGE=wordpress:cli-php8.3
APP_URL=https://safetunnels-ci.test
HTTP_PORT=18080
THEME_RELEASE=$root/theme
ENV
"${compose[@]}" config --quiet
"${compose[@]}" up -d --wait --wait-timeout 240 db wordpress
cli=("${compose[@]}" run --rm -T cli)
"${cli[@]}" core install --url=https://safetunnels-ci.test --title=SafeTunnels --admin_user=ci_admin --admin_email=ci@example.org --skip-email --prompt=admin_password < "$root/secrets/admin_password"
"${cli[@]}" theme activate safetunnels
"${cli[@]}" rewrite flush --hard
curl --fail --silent --show-error -H 'Host: safetunnels-ci.test' -H 'X-Forwarded-Proto: https' 'http://127.0.0.1:18080/?rest_route=/safetunnels/v1/deployment' -o "$root/probe.json"
python3 - "$root/probe.json" "$GITHUB_SHA" <<'PY'
import json,sys
with open(sys.argv[1]) as f: data=json.load(f)
assert data=={'revision':sys.argv[2],'theme':'safetunnels'},data
PY
for path in / /tunnels/privacy/ /tunnels/download/; do
  curl --fail --silent --show-error -H 'Host: safetunnels-ci.test' -H 'X-Forwarded-Proto: https' "http://127.0.0.1:18080$path" -o "$root/page.html"
  grep -q 'safetunnels-tunnels\|safetunnels-legal' "$root/page.html"
done
# Re-running activation/migration must preserve page IDs, not duplicate pages.
before=$("${cli[@]}" post list --post_type=page --format=ids)
"${cli[@]}" eval 'safetunnels_after_switch_theme();'
after=$("${cli[@]}" post list --post_type=page --format=ids)
[[ $before == "$after" ]]
echo 'Docker WordPress installation, active revision, page routes and idempotence passed.'
