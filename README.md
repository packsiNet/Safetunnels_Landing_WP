# Safetunnels_Landing_WP

WordPress theme with a two-product studio hub and the SafeTunnels landing site.

| URL | Page |
| --- | --- |
| `/` | Studio Hub: Tunnel Network and Peeko cards |
| `/tunnels/` | SafeTunnels landing page |
| `/tunnels/privacy/` | SafeTunnels Privacy Policy |
| `/tunnels/terms/` | Terms of Service |
| `/tunnels/support/` | Support |
| `/tunnels/contact/` | Contact |
| `/tunnels/faq/` | FAQ |
| `/peeko/privacy/` | Peeko Privacy Policy |

The theme creates its pages on activation and migrates an existing installation
once when the page structure changes. Existing page IDs and editor content are
preserved. Former flat URLs, such as `/privacy/`, redirect to the nested paths.
Appearance → SafeTunnels Pages can regenerate missing pages.

## Direct APK download

Place the release file at `assets/downloads/safetunnels.apk` and commit it before
pushing to `main`. The deployment package includes files under `assets`, and the
direct download button points to this file in the deployed theme by default.
After deployment, open the direct download button and confirm the APK downloads.
If **Appearance → Customize → SafeTunnels → Download → Direct APK URL** was
previously saved, clear that override or set it to the deployed file URL; saved
Customizer values take precedence over the theme default.

For the prepared local installation, run `./run-local.sh` and open
`http://127.0.0.1:8080/`. Stop it with Ctrl+C. The local WordPress runtime,
database and administrator login are excluded from Git.

## CI/CD

Pushes to `main` provision Ubuntu/Debian servers and deploy WordPress with Docker,
MariaDB, an isolated localhost port, the existing host Nginx, and automatically
issued/renewed Let's Encrypt TLS. Pull requests validate the theme and test a
fresh Docker WordPress installation without accessing production.

Deployments preserve persistent volumes, back up SQL, verify the active commit,
and restore the previous app release on origin health failure. Cloudflare DNS
creation and cache purge use the configured token. Existing vhosts and conflicting
DNS records are preserved.

Configure the `production` environment and verified SSH host keys. `WP_ROOT` is
not required; database/admin passwords are generated on the server. See [the
complete deployment guide](docs/DEPLOYMENT.md) for required permissions, SSL,
credentials, recovery and limitations.
