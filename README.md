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

For the prepared local installation, run `./run-local.sh` and open
`http://127.0.0.1:8080/`. Stop it with Ctrl+C. The local WordPress runtime,
database and administrator login are excluded from Git.

## CI/CD

GitHub Actions validates pull requests and deploys pushes to `main` to an existing
WordPress server. Deployments use a checksummed theme artifact, verified SSH host
keys, database backups, atomic release switches, public health checks and automatic
file rollback. Cloudflare cache purge is optional.

Configure the `production` environment, `WP_ROOT`, and verified host keys before
running production deployment. See [the complete deployment guide](docs/DEPLOYMENT.md)
for required Secrets/Variables, server prerequisites, recovery and limitations.
