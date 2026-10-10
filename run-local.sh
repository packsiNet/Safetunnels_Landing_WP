#!/usr/bin/env bash
set -euo pipefail
project_dir="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
wp_dir="$project_dir/.local-wordpress"
export LD_LIBRARY_PATH="$wp_dir/runtime/usr/lib/x86_64-linux-gnu${LD_LIBRARY_PATH:+:$LD_LIBRARY_PATH}"
echo 'SafeTunnels: http://127.0.0.1:8080'
echo 'Press Ctrl+C to stop.'
exec "$wp_dir/runtime/usr/bin/php8.5" -c "$wp_dir/php.ini" -S 127.0.0.1:8080 -t "$wp_dir" "$wp_dir/router.php"
