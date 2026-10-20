# Safetunnels_Landing_WP

WordPress theme dedicated to SafeTunnels.

| URL | Page |
| --- | --- |
| `/` | SafeTunnels landing page |
| `/tunnels/` | Permanent redirect to `/` |
| `/tunnels/privacy/` | SafeTunnels Privacy Policy |
| `/tunnels/terms/` | Terms of Service |
| `/tunnels/support/` | Support |
| `/tunnels/contact/` | Contact |
| `/tunnels/faq/` | FAQ |
| `/blog/` | Blog archive with pagination (or the existing posts page) |

## Blog

Publish articles from **Posts → Add New** in WordPress. Set a title, excerpt,
featured image and category; published posts get their own detail pages.
The home page shows the three newest public, non-password-protected posts and
a **View all posts** link. The archive includes all posts with pagination using
the count configured in **Settings → Reading**. With no posts, the home section
is hidden and the archive displays an empty state.

Every article automatically includes the shared download and plans section in
`template-parts/content/post-cta.php`; no copy/paste into post content is needed.
Navigation and the footer link to the blog. The theme provides article metadata,
canonical URLs and BlogPosting structured data. The page migration preserves an
existing published posts page and adds a default blog description if missing.

The theme creates its pages on activation and migrates an existing installation
once when the page structure changes. Existing page IDs and editor content are
preserved. The former hub and Peeko pages are moved to Trash during migration.
Former flat URLs, such as `/privacy/`, redirect to the nested paths.
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
