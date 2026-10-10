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
