<?php
/**
 * 404 — a port of the React app's TunnelsNotFoundPage.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="content">
	<?php
	get_template_part(
		'template-parts/legal/hero',
		null,
		array(
			'eyebrow'  => __( 'Error', 'safetunnels' ),
			'title'    => __( 'Page Not Found', 'safetunnels' ),
			'subtitle' => __( 'The page you were looking for may have moved or never existed.', 'safetunnels' ),
		)
	);
	?>

	<div class="inner lg-content">
		<div class="nf-wrap glass">
			<div class="nf-code" aria-hidden="true">404</div>
			<p class="nf-desc"><?php esc_html_e( 'Here are a few places that will get you back on track.', 'safetunnels' ); ?></p>

			<div class="nf-links">
				<a class="legal-btn" href="<?php echo esc_url( safetunnels_page_url( 'home' ) ); ?>">
					<?php safetunnels_icon( 'ti-arrow-left' ); ?>
					<?php
					printf(
						/* translators: %s: app name. */
						esc_html__( 'Back to %s', 'safetunnels' ),
						esc_html( safetunnels_opt( 'app_name' ) )
					);
					?>
				</a>
				<a class="legal-btn out" href="<?php echo esc_url( safetunnels_page_url( 'support' ) ); ?>">
					<?php safetunnels_icon( 'ti-headset' ); ?> <?php esc_html_e( 'Get Support', 'safetunnels' ); ?>
				</a>
			</div>

			<div class="st-search-box">
				<?php get_search_form(); ?>
			</div>

			<div class="nf-shortcuts">
				<a href="<?php echo esc_url( safetunnels_page_url( 'download' ) ); ?>"><?php esc_html_e( 'Download', 'safetunnels' ); ?></a>
				<a href="<?php echo esc_url( safetunnels_page_url( 'pricing' ) ); ?>"><?php esc_html_e( 'Pricing', 'safetunnels' ); ?></a>
				<a href="<?php echo esc_url( safetunnels_page_url( 'privacy' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'safetunnels' ); ?></a>
				<a href="<?php echo esc_url( safetunnels_page_url( 'terms' ) ); ?>"><?php esc_html_e( 'Terms of Service', 'safetunnels' ); ?></a>
				<a href="<?php echo esc_url( safetunnels_page_url( 'faq' ) ); ?>"><?php esc_html_e( 'FAQ', 'safetunnels' ); ?></a>
				<a href="<?php echo esc_url( safetunnels_page_url( 'contact' ) ); ?>"><?php esc_html_e( 'Contact', 'safetunnels' ); ?></a>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
