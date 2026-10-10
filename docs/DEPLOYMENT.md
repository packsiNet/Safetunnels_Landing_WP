# دپلوی خودکار SafeTunnels روی وردپرس

این مخزن **قالب وردپرس** است. این pipeline قالب را روی یک نصب موجود وردپرس دپلوی می‌کند. نصب اولیه وردپرس، وب‌سرور، TLS، دیتابیس و تنظیم DNS باید پیش از اولین دپلوی انجام شده باشند.

## اجرا

- هر push به `main`: اعتبارسنجی → ساخت بسته → دپلوی روی `production`.
- Pull request به `main`: فقط اعتبارسنجی و ساخت بسته، بدون دسترسی به سرور.
- GitHub → Actions → **Validate and deploy WordPress theme** → Run workflow روی `main`: اجرای مجدد دستی.
- اجرای همزمان به همان شاخه صف می‌شود؛ روی سرور هم `flock` جلوی تداخل را می‌گیرد. دپلوی جاری لغو نمی‌شود.

## تنظیم GitHub

در Settings → Environments یک محیط به نام `production` بسازید و Deployment branches را فقط `main` قرار دهید. اگر دپلوی بدون توقف می‌خواهید، Required reviewers فعال نباشد. Secrets فعلی مخزن نیز قابل استفاده‌اند؛ مقدار هم‌نام در environment اولویت دارد.

### Secrets

| نام | مقدار |
| --- | --- |
| `SERVER_HOST` | hostname یا IP سرور، بدون `https://` |
| `SERVER_PORT` | پورت SSH؛ پیش‌فرض `22` |
| `SERVER_USER` | کاربر مخصوص دپلوی سایت |
| `SERVER_SSH_KEY` | متن کامل کلید خصوصی SSH، با newline واقعی؛ کلید بدون passphrase |
| `SERVER_KNOWN_HOSTS` | کلید عمومی **تأییدشده** سرور در قالب OpenSSH known_hosts |
| `CLOUDFLARE_API_TOKEN` | اختیاری؛ توکن با مجوز Zone → Cache Purge → Purge برای zone همین سایت |

`APP_URL` موجود در Secrets نیز پشتیبانی می‌شود. کلید خصوصی را در فایل مخزن قرار ندهید.

### Variables

| نام | مقدار |
| --- | --- |
| `APP_URL` | URL اصلی HTTPS، مثلاً `https://safetunnels.net`؛ در صورت وجود بر Secret اولویت دارد |
| `WP_ROOT` | **الزامی**؛ مسیر مطلق نصب وردپرس که `wp-config.php` داخل آن است، مثلاً `/var/www/safetunnels/public` |
| `DEPLOY_ROOT` | پیشنهاد: `/var/www/safetunnels-deploy`؛ بیرون از document root. در صورت خالی بودن: `$HOME/.local/state/safetunnels-deploy` |
| `CLOUDFLARE_ZONE_ID` | اگر توکن Cloudflare موجود است الزامی؛ Zone ID سی‌ودوکاراکتری همان سایت |

`WP_ROOT` و `DEPLOY_ROOT` نمونه هستند؛ با مسیر واقعی سرور جایگزین کنید. قالب در مسیری که خود وردپرس از `get_theme_root()` برمی‌گرداند، با slug ثابت `safetunnels` نصب می‌شود.

## آماده‌سازی یک‌باره سرور

نیازها: Linux، Bash، SSH/SFTP، PHP CLI حداقل 7.4 با Phar، WP-CLI، WordPress نصب‌شده، MySQL/MariaDB و ابزار مورد نیاز `wp db export`، `curl`، `tar`، `flock`، `sha256sum`، `realpath`، `find`، `awk` و `grep`.

کاربر SSH باید:

1. اجازه خواندن `wp-config.php` و اجرای WP-CLI داشته باشد.
2. اجازه نوشتن در پوشه themes و `DEPLOY_ROOT` داشته باشد.
3. کاربر root نباشد؛ pipeline از `sudo` استفاده نمی‌کند.

نمونه ساخت پوشه نسخه‌ها توسط مدیر سرور:

```bash
sudo install -d -m 755 -o DEPLOY_USER -g WEB_GROUP /var/www/safetunnels-deploy
```

نام کاربر و گروه بالا placeholder هستند. وب‌سرور/PHP باید بتواند تمام پوشه‌های والد مسیر نسخه‌ها را traverse کند و فایل‌های نسخه را بخواند. خود نسخه‌ها `755/644` و پوشه بکاپ `700` است. بکاپ دیتابیس `600` ساخته می‌شود. مسیر `DEPLOY_ROOT` نباید از طریق وب سرو شود.

وب‌سرور باید symlink قالب را دنبال کند. اگر PHP با کاربری متفاوت اجرا می‌شود، سیاست‌های `open_basedir`، SELinux/AppArmor و symlink ownership باید مسیر نسخه‌ها را مجاز بدانند. پوشه home با permission `700` برای این حالت مناسب نیست؛ `DEPLOY_ROOT` قابل‌خواندن مشخص کنید.

OPcache وب باید تغییرات فایل را ببیند (`opcache.validate_timestamps=1`) و کش realpath باید به‌سرعت منقضی شود؛ endpoint سلامت نسخه واقعاً اجراشده در PHP وب را بررسی می‌کند. سروری که به restart اجباری PHP-FPM نیاز دارد باید قبل از فعال کردن pipeline برای این روش پیکربندی شود.

بررسی آماده بودن سرور، با همان کاربر دپلوی:

```bash
wp --path=/ACTUAL/WP/ROOT --skip-themes core is-installed
wp --path=/ACTUAL/WP/ROOT --skip-themes eval 'echo get_theme_root();'
wp --path=/ACTUAL/WP/ROOT --skip-themes db export /PRIVATE/PATH/preflight.sql
```

برای SSH، کلید عمومی متناظر با `SERVER_SSH_KEY` را در `authorized_keys` کاربر قرار دهید. برای محدود کردن forwarding می‌توان از `restrict` استفاده کرد؛ forced command باید با SCP/SFTP و اجرای اسکریپت سازگار باشد.

برای `SERVER_KNOWN_HOSTS`، fingerprint را از کنسول قابل‌اعتماد سرور بررسی کنید:

```bash
ssh-keygen -lf /etc/ssh/ssh_host_ed25519_key.pub
```

سپس روی سیستم خود کلید را جمع کنید و fingerprint آن را با خروجی کنسول تطبیق دهید:

```bash
ssh-keyscan -p SERVER_PORT SERVER_HOST > known_hosts
ssh-keygen -lf known_hosts
```

پس از تطبیق، متن فایل را در Secret ذخیره کنید. برای پورت غیر استاندارد، نام میزبان در این فایل به شکل `[host]:port` است. pipeline از `StrictHostKeyChecking=yes` استفاده می‌کند و در هر اجرا کلید سرور را بی‌اعتبار جایگزین نمی‌کند.

## مراحل دپلوی

1. PHP lint در runner Ubuntu 24.04، بررسی syntax JavaScript، ShellCheck و تست‌های ابزار دپلوی.
2. بسته فقط شامل فایل‌های مجاز قالب است؛ `.git`، `.aws`، محیط لوکال، `wp-config.php`، دیتابیس، اسکریپت‌ها و تست‌ها منتشر نمی‌شوند.
3. ثبت SHA گیت در بسته و در bootstrap PHP، محاسبه SHA-256 و نگهداری artifact به مدت ۱۴ روز.
4. اتصال SSH با کلید host تأییدشده، آپلود در temp server و تطبیق checksum.
5. قفل دپلوی، بررسی وردپرس، PHP lint با **PHP خود سرور** و بکاپ دیتابیس پیش از بارگذاری نسخه جدید.
6. استخراج نسخه در پوشه مستقل و تعویض اتمیک symlink قالب. در تبدیل اولیه یک پوشه قدیمی به symlink، maintenance mode کوتاه فعال می‌شود.
7. فعال کردن قالب در اولین نصب، اجرای migration ساختار صفحات بر اساس نسخه، flush rewrite و object cache.
8. بررسی endpoint عمومی `/?rest_route=/safetunnels/v1/deployment` برای SHA واقعی، سپس هاب، `/tunnels/`، Privacy و Peeko Privacy.
9. در صورت موفقیت، ثبت نسخه فعلی و قبلی و پاک‌سازی کش Cloudflare.

فایل‌های uploads، تنظیمات `wp-config.php` و فایل‌های سایر قالب‌ها/پلاگین‌ها دست‌نخورده می‌مانند. **این قالب هنگام اولین activation صفحات، منو و صفحه اصلی وردپرس را تنظیم می‌کند**؛ برای استفاده روی سایت موجود ابتدا staging را بررسی کنید.

برای Cloudflare، zone باید متعلق به همین سایت باشد: `purge_everything` تمام کش آن zone را پاک می‌کند، شامل assetهای قدیمی. این کار فقط cache است و DNS را تغییر نمی‌دهد. مسیر REST سلامت و query `deploy_check` باید از full-page cache/CDN cache سفارشی مستثنا باشند. `wp cache flush` فقط object cache را flush می‌کند؛ پلاگین full-page cache باید برای این مسیرها bypass داشته باشد یا تنظیمات پاک‌سازی مخصوص خودش را داشته باشد.

## شکست و بازگشت نسخه

- شکست پیش از تعویض: سایت فعلی ادامه می‌دهد.
- شکست پس از تعویض یا سلامت: symlink نسخه قبلی بازیابی می‌شود، قالب فعال قبلی برمی‌گردد و maintenance mode پاک می‌شود.
- دیتابیس **خودکار restore نمی‌شود**؛ این کار می‌تواند اطلاعات جدید کاربران را حذف کند. migration صفحات idempotent است، اما rollback فایل‌ها تغییرات دیتابیس را برنمی‌گرداند. بکاپ قبل از دپلوی برای بازیابی آگاهانه نگهداری می‌شود.
- شکست purge Cloudflare پس از سلامت: نسخه جدید روی سرور فعال می‌ماند، workflow با خطا گزارش می‌شود. مشکل token/zone را رفع و workflow را دوباره اجرا کنید.
- برای بازگشت دستی از طریق همین مسیر قابل‌تست، commit مشکل‌دار را `git revert` کنید و روی `main` push کنید. یک release جدید از کد قبلی ساخته می‌شود.
- قطع اجباری runner، خاموشی سرور یا `SIGKILL` ممکن است مانع اجرای trap شود. در این حالت symlink فعلی و `.maintenance` را روی سرور بررسی و workflow را دوباره اجرا کنید. قفل OS با پایان فرایند آزاد می‌شود.

نسخه‌ها و بکاپ‌های سرور خودکار حذف نمی‌شوند؛ بعد از تعریف سیاست نگهداری و بکاپ خارجی، نسخه‌های قدیمی را با حفظ نسخه فعلی و قبلی پاک کنید. هر بار اجرای مجدد همان SHA نیز پوشه نسخه جداگانه دارد.

## فعال‌سازی

فایل‌ها را commit و push کنید. این تغییر به‌تنهایی Secrets/Variables را در GitHub ایجاد نمی‌کند. پس از تکمیل تنظیمات بالا، push به `main` یا Run workflow دپلوی را آغاز می‌کند. بدون `WP_ROOT` یا host key معتبر، workflow عمداً پیش از تغییر سرور متوقف می‌شود.

تست‌های repository عملیات فایل، بسته‌بندی، شکست بکاپ و rollback را با سرویس‌های خارجی شبیه‌سازی می‌کنند؛ جایگزین اولین دپلوی staging روی سرور واقعی نیستند. محیط CI فقط PHP runner را بررسی می‌کند، اما مرحله سرور PHP نصب‌شده روی همان سرور را lint می‌کند.

## مستندات مرجع

- [GitHub deployment environments](https://docs.github.com/en/actions/concepts/workflows-and-actions/deployment-environments)
- [GitHub workflow syntax](https://docs.github.com/en/actions/reference/workflows-and-actions/workflow-syntax)
- [WordPress theme activate](https://developer.wordpress.org/cli/commands/theme/activate/)
- [Cloudflare cache purge](https://developers.cloudflare.com/cache/how-to/purge-cache/purge-cache-key/)
