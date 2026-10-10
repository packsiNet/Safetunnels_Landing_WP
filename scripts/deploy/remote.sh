#!/usr/bin/env bash
# Run as the site's deployment user. WordPress and WP-CLI must already exist.
set -Eeuo pipefail
umask 022
archive=$1 digest=$2 revision=$3 wp_root=$4 app_url=$5 deploy_root=$6
[[ $revision =~ ^[a-f0-9]{40}$ && $digest =~ ^[a-f0-9]{64}$ ]] || exit 2
[[ $wp_root = /* && -f "$wp_root/wp-config.php" ]] || { echo 'WP_ROOT must contain wp-config.php.' >&2; exit 2; }
wp_root=$(realpath "$wp_root")
for command in wp php curl tar flock sha256sum realpath; do command -v "$command" >/dev/null || { echo "Missing server tool: $command" >&2; exit 2; }; done
[[ -n $deploy_root ]] || deploy_root="$HOME/.local/state/safetunnels-deploy"
mkdir -p "$deploy_root"
deploy_root=$(realpath "$deploy_root")
# Backups and releases contain private data/code and must be outside the web root.
[[ "$deploy_root/" != "$wp_root/"* ]] || { echo 'DEPLOY_ROOT must be outside WP_ROOT.' >&2; exit 2; }
chmod 755 "$deploy_root"
exec 9>"$deploy_root/deploy.lock"
flock -w 120 9 || { echo 'Another deployment holds the server lock.' >&2; exit 1; }
printf '%s  %s\n' "$digest" "$archive" | sha256sum --check --status
wp_cmd=(wp --path="$wp_root" --no-color)
"${wp_cmd[@]}" --skip-themes core is-installed
# Read paths without running theme migration code during preflight.
themes=$("${wp_cmd[@]}" --skip-themes eval 'echo get_theme_root();')
[[ "$themes" = /* && -d "$themes" && -w "$themes" ]] || { echo 'Theme directory is not writable.' >&2; exit 2; }
target="$themes/safetunnels"
[[ ! -L "$target" || -d "$target" ]] || { echo 'Existing theme symlink is broken.' >&2; exit 2; }
active=$("${wp_cmd[@]}" --skip-themes option get stylesheet)
release="$deploy_root/releases/$revision-$(date -u +%Y%m%dT%H%M%SZ)-$$"
mkdir -p "$deploy_root/releases" "$deploy_root/backups"
chmod 700 "$deploy_root/backups"
[[ ! -e "$release" ]] || { echo 'This release already exists; use a new commit or roll back explicitly.' >&2; exit 2; }
mkdir "$release"
# Artifacts are allowlisted in CI; reject traversal and links before extracting.
# PHP variables are intentionally not expanded by Bash.
# shellcheck disable=SC2016
php -r '$p=new PharData($argv[1]); foreach(new RecursiveIteratorIterator($p) as $f) { if ($f->isLink()) { exit(1); } }' "$archive"
tar -tzf "$archive" | awk '/(^\/|(^|\/)\.\.($|\/))/ { bad=1 } END { exit bad }'
tar --extract --gzip --file="$archive" --directory="$release" --no-same-owner --no-same-permissions
[[ -f "$release/style.css" && -f "$release/functions.php" && -f "$release/release.json" ]]
find "$release" -type d -exec chmod 755 {} +
find "$release" -type f -exec chmod 644 {} +
while IFS= read -r -d '' file; do php -l "$file" >/dev/null; done < <(find "$release" -name '*.php' -print0)
stamp="$(date -u +%Y%m%dT%H%M%SZ)-$$"
backup="$deploy_root/backups/$stamp-$revision.sql"
# Export before loading the new theme, including its page migrations.
umask 077
"${wp_cmd[@]}" --skip-themes db export "$backup" --quiet
[[ -s "$backup" ]]
umask 022
previous=''
switched=0
maintenance=0
rollback() {
  result=$?
  trap - EXIT
  if (( result != 0 )); then
    echo 'Deployment failed. Restoring the previous theme files.' >&2
    if (( switched )); then
      if [[ -n $previous ]]; then
        ln -s "$previous" "$target.rollback"
        mv -Tf "$target.rollback" "$target"
      else
        rm -f "$target"
      fi
      "${wp_cmd[@]}" --skip-themes theme activate "$active" || true
      "${wp_cmd[@]}" cache flush || true
    elif [[ -n $previous && ! -e $target && ! -L $target ]]; then
      ln -s "$previous" "$target"
    fi
    echo "Database backup retained at $backup (not automatically restored)." >&2
  fi
  if (( maintenance )); then "${wp_cmd[@]}" --skip-themes maintenance-mode deactivate || true; fi
  rm -f "$target.next"
  exit "$result"
}
trap rollback EXIT
if [[ -L $target ]]; then
  previous=$(realpath "$target")
elif [[ -d $target ]]; then
  # One-time conversion of an existing directory to the release symlink.
  "${wp_cmd[@]}" --skip-themes maintenance-mode activate
  maintenance=1
  previous="$deploy_root/releases/legacy-$stamp"
  mv "$target" "$previous"
elif [[ -e $target ]]; then
  echo 'Theme destination is not a directory.' >&2
  exit 2
fi
ln -s "$release" "$target.next"
mv -Tf "$target.next" "$target"
switched=1
if [[ $active != safetunnels ]]; then "${wp_cmd[@]}" theme activate safetunnels; fi
# Triggers the idempotent structure-version migration during WordPress bootstrap.
"${wp_cmd[@]}" eval 'if (!function_exists("safetunnels_page_url")) { exit(1); }'
"${wp_cmd[@]}" rewrite flush
"${wp_cmd[@]}" cache flush
if (( maintenance )); then
  "${wp_cmd[@]}" maintenance-mode deactivate
  maintenance=0
fi
# Verify the public PHP worker, not only the command-line PHP process.
probe="$deploy_root/probe-$revision.json"
probe_url="${app_url%/}/?rest_route=/safetunnels/v1/deployment&deploy_check=$revision"
healthy=0
for attempt in {1..12}; do
  echo "Checking public release ($attempt/12)..."
  # shellcheck disable=SC2016
  if curl --fail --silent --show-error --max-time 15 --header 'Cache-Control: no-cache' "$probe_url" -o "$probe" &&
    php -r '$d=json_decode(file_get_contents($argv[1]),true); exit(($d["revision"]??null)===$argv[2] && ($d["theme"]??null)==="safetunnels" ? 0 : 1);' "$probe" "$revision"; then
    healthy=1; break
  fi
  sleep 5
done
rm -f "$probe"
(( healthy )) || { echo 'Public PHP process is not serving the expected release. Check OPcache / web-server symlink policy.' >&2; exit 1; }
for path in / /tunnels/ /tunnels/privacy/ /peeko/privacy/; do
  page="$deploy_root/page-$revision.html"
  curl --fail --silent --show-error --max-time 20 --header 'Cache-Control: no-cache' "${app_url%/}$path?deploy_check=$revision" -o "$page"
  grep -q 'safetunnels-studio\|safetunnels-landing' "$page"
  if grep -q 'Fatal error\|There has been a critical error' "$page"; then exit 1; fi
  rm -f "$page"
done
printf '%s\n' "$revision" > "$deploy_root/current-revision"
printf '%s\n' "$previous" > "$deploy_root/previous-release"
echo "Deployment verified: $revision"
# Keep all releases/backups; retention is an explicit operator decision.
