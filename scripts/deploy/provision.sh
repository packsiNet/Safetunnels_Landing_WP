#!/usr/bin/env bash
# Privileged, idempotent host setup. Never replaces nginx.conf or other vhosts.
set -Eeuo pipefail
[[ $EUID == 0 ]] || { echo 'Provisioning requires root or passwordless sudo.' >&2; exit 2; }
# shellcheck disable=SC1091
source /etc/os-release
[[ $ID == ubuntu || $ID == debian ]] || { echo 'Automatic provisioning supports Ubuntu/Debian with systemd.' >&2; exit 2; }
command -v systemctl >/dev/null
export DEBIAN_FRONTEND=noninteractive
if command -v nginx >/dev/null; then nginx -t; fi
apt-get update -qq
packages=(ca-certificates curl openssl python3 certbot)
command -v nginx >/dev/null || packages+=(nginx)
apt-get install -y --no-install-recommends "${packages[@]}"
if ! command -v docker >/dev/null; then
  # Do not uninstall/replace conflicting existing container packages automatically.
  for package in docker.io podman-docker containerd runc; do
    if dpkg-query -W -f='${Status}' "$package" 2>/dev/null | grep -q 'install ok installed'; then
      echo "Existing package $package conflicts with Docker CE. Migrate it explicitly first." >&2; exit 2
    fi
  done
  install -d -m 0755 /etc/apt/keyrings
  curl -fsSL "https://download.docker.com/linux/$ID/gpg" -o /etc/apt/keyrings/safetunnels-docker.asc
  chmod 644 /etc/apt/keyrings/safetunnels-docker.asc
  codename=${UBUNTU_CODENAME:-$VERSION_CODENAME}
  printf 'deb [arch=%s signed-by=/etc/apt/keyrings/safetunnels-docker.asc] https://download.docker.com/linux/%s %s stable\n' "$(dpkg --print-architecture)" "$ID" "$codename" > /etc/apt/sources.list.d/safetunnels-docker.list
  apt-get update -qq
  apt-get install -y --no-install-recommends docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
fi
docker info >/dev/null 2>&1 || systemctl start docker
docker compose version >/dev/null || { echo 'Existing Docker lacks Compose v2; install its matching plugin first.' >&2; exit 2; }
systemctl enable docker
nginx -t
systemctl enable --now nginx
systemctl enable --now certbot.timer
