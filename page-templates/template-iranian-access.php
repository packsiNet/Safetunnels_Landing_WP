<?php
/**
 * Template Name: SafeTunnels — Persian Guide
 *
 * @package SafeTunnels
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="content" class="st-fa-guide" dir="rtl" lang="fa">
	<article class="inner st-fa-guide-body">
		<nav aria-label="مسیر صفحه"><a href="<?php echo esc_url( safetunnels_page_url( 'home' ) ); ?>">سیف‌تانلز</a> / <?php echo esc_html( get_the_title() ); ?></nav>
		<h1><?php echo esc_html( get_the_title() ); ?></h1>
		<p class="st-fa-lead">برخی اپ‌ها و وب‌سایت‌های ایرانی هنگام اتصال از خارج کشور به‌درستی باز نمی‌شوند یا دسترسی را محدود می‌کنند. SafeTunnels برای استفاده از سرویس‌های ایرانی در اندروید، مسیر اتصال به ایران فراهم می‌کند.</p>
		<h2>از کجا شروع کنم؟</h2>
		<ol>
			<li><a href="<?php echo esc_url( safetunnels_page_url( 'download' ) ); ?>">برنامه SafeTunnels</a> را برای اندروید دریافت و نصب کنید.</li>
			<li>برنامه را باز کنید و مراحل ساخت حساب یا ورود را دنبال کنید.</li>
			<li>اتصال را فعال کنید، سپس اپ ایرانی موردنظر را باز کنید.</li>
			<li>اگر اتصال برقرار است اما اپ مقصد کار نمی‌کند، یک بار اپ را ببندید و دوباره باز کنید و سپس <a href="<?php echo esc_url( safetunnels_page_url( 'support' ) ); ?>">راهنمای پشتیبانی</a> را ببینید.</li>
		</ol>
		<h2>چه استفاده‌هایی دارد؟</h2>
		<p>این سرویس برای دسترسی به اپ‌ها و سایت‌هایی طراحی شده که به اتصال از ایران نیاز دارند؛ از جمله دسته‌های بانکی، پرداخت، خرید و خدمات آنلاین. <a href="<?php echo esc_url( safetunnels_page_url( 'apps' ) ); ?>">فهرست اپ‌های پشتیبانی‌شده</a> را پیش از نصب بررسی کنید.</p>
		<h2>چه محدودیت‌هایی را باید بدانم؟</h2>
		<p>عملکرد هر سرویس مقصد به سیاست‌ها و وضعیت فنی همان سرویس هم بستگی دارد. وجود تانل، پذیرش اتصال یا کارکرد تمام قابلیت‌های یک اپ را تضمین نمی‌کند. برخی خدمات ممکن است احراز هویت، شماره تلفن ایرانی یا شرایط دیگری داشته باشند. در حال حاضر راهنمای دانلود این سایت برای نسخه اندروید است.</p>
		<h2>آیا تمام اینترنت از تانل عبور می‌کند؟</h2>
		<p>SafeTunnels برای مسیریابی ترافیک اپ‌های ایرانی طراحی شده است؛ درباره شیوه کار و تفاوت آن با تغییر مسیر همه ترافیک، <a href="<?php echo esc_url( safetunnels_page_url( 'iran_tunnel' ) ); ?>">راهنمای تانل ایران</a> را بخوانید.</p>
		<div class="st-fa-guide-cta">
			<h2>آماده شروع هستید؟</h2>
			<p>نسخه اندروید را دریافت کنید و مراحل اتصال را در برنامه دنبال کنید.</p>
			<a href="<?php echo esc_url( safetunnels_page_url( 'download' ) ); ?>">دریافت SafeTunnels برای اندروید</a>
		</div>
		<p class="st-fa-guide-related">مطالعه بیشتر: <a href="<?php echo esc_url( safetunnels_page_url( 'iran_tunnel' ) ); ?>">iran_tunnel_LABEL</a> · <a href="<?php echo esc_url( safetunnels_page_url( 'apps' ) ); ?>">فهرست اپ‌های ایرانی</a> · <a href="<?php echo esc_url( safetunnels_page_url( 'faq' ) ); ?>">پرسش‌های متداول</a></p>
	</article>
</main>
<?php get_footer();
