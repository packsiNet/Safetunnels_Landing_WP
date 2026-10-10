# نصب کامل و دپلوی Docker برای SafeTunnels

با push به `main` روی **Ubuntu/Debian با Nginx روی خود سرور**، نصب اولیه و آپدیت‌های بعدی انجام می‌شوند. نیازی به نصب قبلی وردپرس، PHP یا دیتابیس نیست.

## معماری

```text
Domain HTTPS → Nginx فعلی سرور → 127.0.0.1:18080 → WordPress/Apache/PHP در Docker
                                                   ↕ شبکه خصوصی
                                              MariaDB در Docker
```

- Nginx موجود با فایل اختصاصی همین دامنه گسترش پیدا می‌کند؛ `nginx.conf` و vhostهای دیگر بازنویسی نمی‌شوند.
- دیتابیس هیچ پورت عمومی ندارد. تنها پورت اپلیکیشن روی localhost باز می‌شود.
- داده‌های وردپرس، تنظیمات، uploads و دیتابیس در Docker volumes مستقل و دائمی می‌مانند.
- قالب از پوشه هر release به صورت read-only mount می‌شود؛ تغییر release فقط کانتینر همین سایت را بازسازی می‌کند.
- imageهای رسمی WordPress/PHP 8.3، MariaDB 11.4 و WP-CLI در اولین نصب به digest دقیق pin می‌شوند و در اجرای بعدی بی‌اجازه ارتقا نمی‌یابند.

## تنظیم GitHub

در Settings → Environments محیط `production` را بسازید؛ Deployment branches فقط `main`. برای اجرای کاملاً خودکار Required reviewers فعال نباشد. Secrets سطح مخزن نیز خوانده می‌شوند.

### Secrets

| نام | کاربرد |
| --- | --- |
| `SERVER_HOST` | IP یا hostname واقعی سرور برای SSH؛ بدون protocol |
| `SERVER_PORT` | پورت SSH؛ در صورت خالی بودن `22` |
| `SERVER_USER` | `root` یا کاربری با `sudo -n` برای provisioning |
| `SERVER_SSH_KEY` | متن کامل کلید خصوصی بدون passphrase، با newline واقعی |
| `SERVER_KNOWN_HOSTS` | **الزامی**؛ کلید عمومی تأییدشده سرور در قالب OpenSSH known_hosts |
| `APP_URL` | URL اصلی مانند `https://safetunnels.net`؛ می‌توان به جای Secret از Variable هم استفاده کرد |
| `CLOUDFLARE_API_TOKEN` | برای کش و DNS خودکار؛ اگر خالی باشد DNS باید از قبل به سرور اشاره کند |

برای ساخت DNS خودکار توکن باید روی zone همین دامنه مجوزهای **Zone Read، DNS Edit و Cache Purge** داشته باشد. اگر رکورد موجود proxied است، **Zone Settings Read** نیز برای بررسی Full (strict) لازم است. توکن قبلی که فقط Cache Purge دارد، برای نصب همراه DNS کافی نیست.

### Variables اختیاری

| نام | پیش‌فرض / کاربرد |
| --- | --- |
| `APP_URL` | اگر تعریف شود بر Secret اولویت دارد |
| `DEPLOY_ROOT` | `/opt/safetunnels`؛ پوشه اختصاصی جدید، بدون فاصله، بیرون از document root |
| `HTTP_PORT` | `18080`؛ پورت آزاد localhost، بین 1024 و 65535 |
| `ADMIN_EMAIL` | `admin@دامنه`؛ **توصیه می‌شود ایمیل واقعی خودتان را تنظیم کنید**؛ هم برای ادمین وردپرس و هم Let’s Encrypt |
| `ADMIN_USER` | `safetunnels_admin`؛ فقط در نصب اولیه |
| `CLOUDFLARE_ZONE_ID` | در صورت نبود، با Zone Read از روی دامنه پیدا می‌شود |
| `ORIGIN_IP` | در صورت نبود، از `SERVER_HOST` استخراج یا resolve می‌شود؛ برای ساخت DNS باید IP عمومی واقعی origin باشد |

`WP_ROOT` دیگر لازم نیست. دامنه باید HTTPS، بدون path، روی پورت استاندارد 443 باشد. این pipeline یک سایت مستقل روی دامنه اصلی یا زیردامنه می‌سازد.

## پیش‌نیازهای خارج از pipeline

- دسترسی SSH و مجوز root یا passwordless sudo.
- Ubuntu/Debian با systemd؛ اگر Docker از قبل نصب است، باید سالم و همراه Compose v2 باشد.
- دسترسی سرور به مخازن رسمی Docker، image registry و Let’s Encrypt.
- دسترسی عمومی به پورت‌های 80 و 443 برای دامنه در فایروال سرور و security group میزبان. pipeline قوانین فایروال سایر سایت‌ها را تغییر نمی‌دهد.
- دامنه در zone فعال Cloudflare و token معتبر، یا DNS از قبل آماده در DNS provider خودتان.
- Nginx باید `/etc/nginx/conf.d/*.conf` را داخل `http {}` include کند؛ این ساختار پیش‌فرض Ubuntu/Debian است.

اگر Docker نصب نیست، از repository رسمی Docker نصب می‌شود. اگر بسته container engine ناسازگار از قبل باشد، نصب متوقف می‌شود و آن engine یا کانتینرهای موجود حذف نمی‌شوند. اگر Nginx موجود است، pipeline آن را مجدداً نصب/ارتقا نمی‌دهد؛ تنظیمات فعلی ابتدا با `nginx -t` بررسی می‌شوند.

### تأیید کلید SSH سرور

از کنسول قابل‌اعتماد سرور:

```bash
ssh-keygen -lf /etc/ssh/ssh_host_ed25519_key.pub
```

روی سیستم خودتان:

```bash
ssh-keyscan -p SERVER_PORT SERVER_HOST > known_hosts
ssh-keygen -lf known_hosts
```

fingerprintها را تطبیق دهید؛ بعد متن `known_hosts` را در Secret ذخیره کنید. برای پورت غیر استاندارد entry به شکل `[host]:port` است. workflow کلید سرور را در هر اجرا بدون تأیید دوباره قبول نمی‌کند.

## جریان اولین push

1. PHP lint، بررسی JavaScript، ShellCheck، تست‌های ابزار دپلوی و ساخت بسته allowlist شامل فایل‌های قالب.
2. در CI یک WordPress و MariaDB واقعی و موقت با Docker ساخته می‌شود؛ نصب وردپرس، فعال شدن قالب، SHA نسخه، مسیر صفحات و idempotence نصب صفحات بررسی می‌شوند.
3. اتصال SSH با host key معتبر و بررسی checksum بسته.
4. یافتن zone و ساخت رکورد DNS-only دامنه، فقط اگر رکورد A/AAAA/CNAME موجود نباشد. اگر رکورد موجود به همین IP اشاره کند حفظ می‌شود؛ رکورد متفاوت بازنویسی نمی‌شود.
5. نصب Docker/Compose در صورت نیاز، Certbot و Nginx در صورت نبود؛ فعال شدن سرویس‌ها و timer تمدید گواهی.
6. ساخت رمزهای تصادفی دیتابیس، root دیتابیس و مدیر وردپرس روی سرور؛ رمزها در log یا GitHub منتشر نمی‌شوند.
7. ذخیره image digestها، اجرای کانتینرها، نصب خودکار وردپرس با WP-CLI و فعال شدن قالب و صفحات.
8. ساخت vhost اختصاصی HTTP و صدور گواهی Let’s Encrypt با webroot HTTP-01، سپس فعال کردن HTTPS و redirect از HTTP.
9. بررسی مستقیم origin با TLS معتبر و SHA اجراشده، بررسی هاب و صفحات محصول و Privacy، پاک‌سازی کش دامنه و بررسی HTTPS عمومی.

## SSL و سایت‌های دیگر

- فایل اختصاصی: `/etc/nginx/conf.d/safetunnels-DOMAIN.conf`.
- گواهی اختصاصی: `/etc/letsencrypt/live/safetunnels-DOMAIN/`.
- webroot چالش: `/var/lib/safetunnels-acme`.
- تمدید با `certbot.timer` و reload پس از `nginx -t` انجام می‌شود؛ Nginx برای صدور/تمدید متوقف نمی‌شود.
- Config جدید قبل از reload تست می‌شود؛ در صورت شکست config قبلی بازیابی می‌شود.
- اگر دامنه با `server_name` سایت دیگر، wildcard یا regex موجود تداخل داشته باشد، دپلوی متوقف می‌شود. regexهای Nginx به طور محافظه‌کارانه نیاز به بررسی اپراتور دارند.
- اگر رکورد Cloudflare موجود proxied است، تنظیم SSL باید **Full (strict)** باشد. pipeline تنظیم SSL کل zone را تغییر نمی‌دهد چون ممکن است سایت‌های دیگری در آن باشند.
- رکورد جدید DNS-only ساخته می‌شود؛ پس از نصب موفق می‌توانید proxy Cloudflare را با تنظیم صحیح SSL فعال کنید.
- Cache Purge فقط URL صفحات همین اپلیکیشن را پاک می‌کند؛ assetها نسخه‌دار هستند. zone-wide purge انجام نمی‌شود.

## ورود به وردپرس

آدرس ورود: `APP_URL/wp-admin/`، نام کاربری پیش‌فرض `safetunnels_admin`.

رمز تصادفی مدیر در فایل خصوصی سرور نگهداری می‌شود. برای مشاهده از SSH:

```bash
sudo cat /opt/safetunnels/secrets/admin_password
```

اگر `DEPLOY_ROOT` عوض شده مسیر را مطابق آن تغییر دهید. بعد از اولین ورود رمز را از پنل وردپرس تغییر دهید؛ pipeline در اجرای بعدی رمز حساب موجود را reset نمی‌کند. فایل فوق پس از تغییر دستی رمز، رمز اولیه را نشان می‌دهد.

## آپدیت و rollback

- pushهای بعدی وردپرس را دوباره نصب نمی‌کنند و volumeها را حذف نمی‌کنند.
- پیش از تعویض قالب، SQL snapshot با `--single-transaction` و بکاپ Compose گرفته می‌شود.
- در شکست اجرای کانتینر یا بررسی origin، mount نسخه قالب و Compose قبلی بازیابی و سرویس همان سایت بازسازی می‌شود.
- تعویض Docker app ممکن است چند ثانیه وقفه داشته باشد؛ این طراحی rolling blue/green نیست.
- دیتابیس **خودکار restore نمی‌شود**؛ این کار می‌تواند اطلاعات جدید کاربران را حذف کند. migration قالب ممکن است داده‌ها را تغییر دهد؛ SQL قبل از دپلوی برای بازیابی آگاهانه باقی می‌ماند.
- شکست اولین نصب، داده‌ها و containerهای ایجادشده را برای retry حفظ می‌کند؛ موفقیت گزارش نمی‌شود.
- شکست پاک‌سازی کش Cloudflare پس از موفقیت origin فقط warning می‌دهد و بررسی HTTPS عمومی ادامه پیدا می‌کند؛ صفحات کش‌شده ممکن است قدیمی بمانند. شکست بررسی HTTPS عمومی همچنان workflow را قرمز می‌کند اما سایت سالم origin را حذف نمی‌کند. مشکل DNS/firewall/Cloudflare را رفع و Run workflow را اجرا کنید.
- برای بازگشت دستی به کد قبل، commit مشکل‌دار را `git revert` و push کنید.
- قفل GitHub و `flock` جلوی دپلوی همزمان را می‌گیرند. `SIGKILL` یا قطع برق ممکن است مانع اجرای rollback شود؛ workflow را پس از بررسی `.env` و سرویس‌ها دوباره اجرا کنید.

volumeها با compose project منحصربه‌فرد همین `DEPLOY_ROOT` ساخته می‌شوند و با stackهای دیگر تداخل ندارند. تغییر `APP_URL` دامنه همین نصب را منتقل می‌کند و گواهی دامنه جدید را می‌سازد؛ پورت باید ثابت بماند. در شکست، فایل محیط و URL قبلی بازیابی می‌شوند. vhost قدیمی همان نصب خودکار حذف نمی‌شود؛ پس از انتقال موفق می‌توان آن را حذف یا به دامنه جدید redirect کرد. URLهای ذخیره‌شده در متن مطالب ممکن است نیازمند migration جداگانه باشند. پوشه موجود بدون فایل مالکیت `.safetunnels-managed` بازنویسی نمی‌شود.

## بررسی سرویس‌ها از SSH

```bash
APP_DEPLOY_ROOT=/opt/safetunnels
APP_PROJECT="safetunnels-$(printf '%s' "$APP_DEPLOY_ROOT" | sha256sum | cut -c1-12)"
sudo docker compose --project-name "$APP_PROJECT" --project-directory "$APP_DEPLOY_ROOT" --file "$APP_DEPLOY_ROOT/compose.yml" ps
sudo docker compose --project-name "$APP_PROJECT" --project-directory "$APP_DEPLOY_ROOT" --file "$APP_DEPLOY_ROOT/compose.yml" logs --tail 100 wordpress
sudo certbot certificates
sudo systemctl status certbot.timer
sudo nginx -t
```

رمزها در پوشه `secrets` با parent permission `700` نگهداری و فقط فایل مورد نیاز در هر کانتینر mount می‌شود. بکاپ SQL `600` است. imageها در `images.env` pin هستند. نسخه‌ها و SQLهای گذشته خودکار حذف نمی‌شوند؛ سیاست retention و بکاپ خارج از این سرور را مطابق نیاز تعریف کنید. updates امنیتی معمول وردپرس غیرفعال نشده‌اند؛ تغییر major core/image و بازیابی کامل دیتابیس کار maintenance جداگانه با بکاپ است.

در اجرای واقعی production هرگز `docker compose down --volumes` استفاده نمی‌شود؛ این دستور فقط در تست disposable CI برای پاک‌سازی داده آزمایشی است.

## فعال‌سازی و محدودیت اعتبارسنجی

Secrets موجود + `SERVER_KNOWN_HOSTS` را آماده کنید؛ به `WP_ROOT` و رمز دیتابیس جداگانه نیاز نیست. برای هشدارهای SSL و بازیابی حساب، `ADMIN_EMAIL` واقعی را تنظیم کنید. فایل‌ها را commit و push کنید یا workflow را روی `main` اجرا کنید.

در محیط توسعه فعلی daemon Docker و سرور production در دسترس نبودند. Compose و workflow اعتبارسنجی شده‌اند و تست‌های provisioning/rollback با boundaryهای خارجی شبیه‌سازی‌شده اجرا شده‌اند. تست کانتینر واقعی در CI اضافه شده و اولین push آن را اجرا می‌کند؛ صدور واقعی گواهی نیازمند دسترسی به دامنه و سرور است.

## مراجع

- [Docker Engine on Ubuntu](https://docs.docker.com/engine/install/ubuntu/)
- [Official WordPress Docker image](https://hub.docker.com/_/wordpress/)
- [Certbot webroot and renewal](https://eff-certbot.readthedocs.io/en/stable/using.html)
- [GitHub deployment environments](https://docs.github.com/en/actions/concepts/workflows-and-actions/deployment-environments)
