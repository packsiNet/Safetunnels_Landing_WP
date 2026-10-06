<?php
/**
 * Template Name: SafeTunnels — Support
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
			'eyebrow'  => __( 'Help Center', 'safetunnels' ),
			'title'    => __( 'Support', 'safetunnels' ),
			'subtitle' => __( 'Answers to the most common SafeTunnels questions, and a direct line to us for everything else.', 'safetunnels' ),
		)
	);
	?>

	<div class="inner lg-content">
		<div class="support-grid">
			<?php foreach ( safetunnels_support_topics() as $topic ) : ?>
				<a class="support-card" href="<?php echo esc_url( $topic['url'] ); ?>">
					<div class="support-ico"><?php safetunnels_icon( $topic['icon'] ); ?></div>
					<h2><?php echo esc_html( $topic['title'] ); ?></h2>
					<p><?php echo esc_html( $topic['desc'] ); ?></p>
					<span class="support-link">
						<?php echo esc_html( $topic['label'] ); ?> <?php safetunnels_icon( 'ti-arrow-right' ); ?>
					</span>
				</a>
			<?php endforeach; ?>
		</div>

		<div class="support-cta glass">
			<h2><?php esc_html_e( 'Still need help?', 'safetunnels' ); ?></h2>
			<p><?php esc_html_e( 'Browse the full FAQ for detailed answers, or reach out directly — we read every message.', 'safetunnels' ); ?></p>
			<div class="legal-cta-row center">
				<a class="legal-btn" href="<?php echo esc_url( safetunnels_page_url( 'faq' ) ); ?>">
					<?php safetunnels_icon( 'ti-help-circle' ); ?> <?php esc_html_e( 'Browse Full FAQ', 'safetunnels' ); ?>
				</a>
				<a class="legal-btn out" href="<?php echo esc_url( safetunnels_mailto( 'SafeTunnels Support Request' ) ); ?>">
					<?php safetunnels_icon( 'ti-mail' ); ?> <?php esc_html_e( 'Email Support', 'safetunnels' ); ?>
				</a>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
