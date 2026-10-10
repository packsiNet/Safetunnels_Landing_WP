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
		<p class="st-fa-lead">«تانل ایران» به اتصالی گفته می‌شود که ترافیک موردنیاز را از مسیر سرور ایران عبور می‌دهد. هدف آن دسترسی به سرویس‌هایی است که اتصال خارج از ایران را محدود می‌کنند.</p>
		<h2>SafeTunnels چگونه کار می‌کند؟</h2>
		<p>برنامه روی اندروید یک اتصال رمزگذاری‌شده مبتنی بر WireGuard برقرار می‌کند. طراحی آن به‌گونه‌ای است که ترافیک اپ‌های ایرانی از مسیر تانل عبور کند و سایر ترافیک از اتصال معمول دستگاه استفاده کند. اینکه یک اپ مشخص به کدام مسیر برود، به فهرست و تنظیمات به‌روز برنامه بستگی دارد.</p>
		<h2>فرق تانل ایران با وی‌پی‌ان معمولی چیست؟</h2>
		<p>در یک وی‌پی‌ان سراسری، معمولاً تمام ترافیک دستگاه از یک مسیر عبور می‌کند. در مسیریابی انتخابی، فقط ترافیک مقصدهای تعریف‌شده از تانل می‌گذرد. این روش برای کسی مناسب است که هم‌زمان به سرویس‌های ایرانی و اتصال عادی اینترنت نیاز دارد.</p>
		<h2>برای اتصال چه چیزهایی لازم است؟</h2>
		<ul>
			<li>دستگاه اندرویدی سازگار و اتصال اینترنت فعال</li>
			<li>نصب برنامه از <a href="<?php echo esc_url( safetunnels_page_url( 'download' ) ); ?>">صفحه دانلود رسمی</a></li>
			<li>انتخاب پلن مناسب بر اساس <a href="<?php echo esc_url( safetunnels_page_url( 'pricing' ) ); ?>">شرایط و قیمت فعلی</a></li>
		</ul>
		<h2>آیا با هر اپ ایرانی کار می‌کند؟</h2>
		<p>خیر، سازگاری و دسترسی ممکن است با تغییرات اپ مقصد، شبکه و تنظیمات اتصال تغییر کند. برای نمونه‌های موجود <a href="<?php echo esc_url( safetunnels_page_url( 'apps' ) ); ?>">فهرست اپ‌ها</a> را بررسی کنید و برای رفع مشکل از <a href="<?php echo esc_url( safetunnels_page_url( 'support' ) ); ?>">پشتیبانی</a> کمک بگیرید.</p>
		<div class="st-fa-guide-cta">
			<h2>آماده شروع هستید؟</h2>
			<p>نسخه اندروید را دریافت کنید و مراحل اتصال را در برنامه دنبال کنید.</p>
			<a href="<?php echo esc_url( safetunnels_page_url( 'download' ) ); ?>">دریافت SafeTunnels برای اندروید</a>
		</div>
		<p class="st-fa-guide-related">مطالعه بیشتر: <a href="<?php echo esc_url( safetunnels_page_url( 'iranian_access' ) ); ?>">iranian_access_LABEL</a> · <a href="<?php echo esc_url( safetunnels_page_url( 'apps' ) ); ?>">فهرست اپ‌های ایرانی</a> · <a href="<?php echo esc_url( safetunnels_page_url( 'faq' ) ); ?>">پرسش‌های متداول</a></p>
	</article>
</main>
<?php get_footer();
