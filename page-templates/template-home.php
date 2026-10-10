<?php
/**
 * Template Name: SafeTunnels — Landing
 *
 * The full /tunnels landing page: hero, features, steps, download, the
 * Iranian app catalogue, reviews, pricing, FAQ and a closing CTA.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="content">
	<?php
	get_template_part( 'template-parts/tunnels/hero' );
	get_template_part( 'template-parts/tunnels/why' );
	get_template_part( 'template-parts/tunnels/steps' );
	get_template_part( 'template-parts/tunnels/devices' );
	get_template_part( 'template-parts/tunnels/apps' );
	get_template_part( 'template-parts/tunnels/reviews' );
	get_template_part( 'template-parts/tunnels/pricing' );
	get_template_part( 'template-parts/tunnels/faq' );
	?>
	<section class="st-editor-content" aria-labelledby="iran-access-guide">
		<div class="inner">
			<div class="st-prose glass">
				<h2 id="iran-access-guide">Iran access: connect to Iran from abroad</h2>
				<p>Need an Iranian IP address while outside Iran? SafeTunnels is an Iran VPN for Android that routes supported Iranian app traffic through an encrypted connection to Iran. It can help you access Iranian banking, payment, shopping and other supported apps from abroad while your other traffic uses its usual connection.</p>
				<h3>Looking for a VPN Iran connection?</h3>
				<p>To access Iranian websites and apps from abroad, you need a connection that routes the supported service through Iran. SafeTunnels provides this connection for supported Android apps; check the app catalogue before you connect.</p>
				<h3>How to get an Iran IP address on Android</h3>
				<ol>
					<li><a href="<?php echo esc_url( safetunnels_page_url( 'download' ) ); ?>">Download SafeTunnels for Android</a> and open the app.</li>
					<li>Connect, then open a <a href="<?php echo esc_url( safetunnels_page_url( 'apps' ) ); ?>">supported Iranian app</a>.</li>
					<li>If a service does not work, check the app list and <a href="<?php echo esc_url( safetunnels_page_url( 'support' ) ); ?>">contact support</a>.</li>
				</ol>
				<h3>Can I access blu Bank (blue bank) from abroad?</h3>
				<p>blu Bank, sometimes searched for as &ldquo;blue bank&rdquo;, is included in our <a href="<?php echo esc_url( safetunnels_page_url( 'apps' ) ); ?>">Iranian app catalogue</a>. Connect to Iran with SafeTunnels, then open the blu app. Banking access still depends on the bank&rsquo;s current requirements, your account and network availability. If it does not open, <a href="<?php echo esc_url( safetunnels_page_url( 'support' ) ); ?>">contact support</a> for help.</p>
				<p>Access depends on the destination service, its sign-in requirements and current network conditions. An Iranian IP address does not guarantee that every Iranian website or app will work. See the <a href="<?php echo esc_url( safetunnels_page_url( 'faq' ) ); ?>">FAQ</a> for connection details.</p>
			</div>
		</div>
	</section>
	<?php

	// Anything typed into the page editor renders between the FAQ and the
	// closing CTA — a place for extra indexable copy without touching code.
	$st_content = get_the_content( null, false, get_queried_object_id() );

	if ( trim( wp_strip_all_tags( (string) $st_content ) ) ) :
		?>
		<section class="st-editor-content">
			<div class="inner">
				<div class="st-prose glass">
					<?php echo apply_filters( 'the_content', $st_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</section>
		<?php
	endif;

	?>
	<section class="st-fa-guide-links inner" dir="rtl" lang="fa" aria-label="راهنماهای فارسی">
		<h2>راهنمای اتصال به سرویس‌های ایرانی</h2>
		<p>اگر خارج از ایران هستید، با روش اتصال، کاربردها و محدودیت‌های تانل ایران آشنا شوید.</p>
		<div class="st-fa-guide-grid">
			<a href="<?php echo esc_url( safetunnels_page_url( 'iranian_access' ) ); ?>">دسترسی به اپ‌ها و سایت‌های ایرانی از خارج کشور ←</a>
			<a href="<?php echo esc_url( safetunnels_page_url( 'iran_tunnel' ) ); ?>">تانل ایران چیست و چگونه کار می‌کند؟ ←</a>
		</div>
	</section>
	<?php
	get_template_part( 'template-parts/tunnels/latest-posts' );
	get_template_part( 'template-parts/tunnels/cta' );
	?>
</main>

<?php
get_footer();
