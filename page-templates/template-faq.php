<?php
/**
 * Template Name: SafeTunnels — FAQ
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$categories = safetunnels_faq_categories();

get_header();
?>

<main id="content">
	<?php
	get_template_part(
		'template-parts/legal/hero',
		null,
		array(
			'eyebrow'  => __( 'Help Center', 'safetunnels' ),
			'title'    => __( 'Frequently Asked Questions', 'safetunnels' ),
			'subtitle' => __( 'Everything you need to know about how SafeTunnels works, what data it handles, and how subscriptions and devices are managed.', 'safetunnels' ),
		)
	);
	?>

	<div class="inner lg-content">
		<nav class="faq-jump" aria-label="<?php esc_attr_e( 'FAQ categories', 'safetunnels' ); ?>">
			<?php foreach ( $categories as $category ) : ?>
				<a href="#<?php echo esc_attr( $category['key'] ); ?>"><?php echo esc_html( $category['label'] ); ?></a>
			<?php endforeach; ?>
		</nav>

		<?php foreach ( $categories as $category ) : ?>
			<section id="<?php echo esc_attr( $category['key'] ); ?>" class="faq-category">
				<h2 class="faq-category-title"><?php echo esc_html( $category['label'] ); ?></h2>
				<div class="faq-list">
					<?php foreach ( $category['items'] as $index => $item ) : ?>
						<?php $answer_id = 'faq-' . $category['key'] . '-' . $index; ?>
						<div class="faq-item">
							<button type="button" class="faq-q" aria-expanded="false" aria-controls="<?php echo esc_attr( $answer_id ); ?>">
								<?php echo esc_html( $item['q'] ); ?>
								<?php safetunnels_icon( 'ti-plus' ); ?>
							</button>
							<div class="faq-a" id="<?php echo esc_attr( $answer_id ); ?>">
								<?php echo esc_html( $item['a'] ); ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endforeach; ?>

		<div class="support-cta glass spaced">
			<h2><?php esc_html_e( 'Didn\'t find your answer?', 'safetunnels' ); ?></h2>
			<p><?php esc_html_e( 'Our support team is happy to help with anything not covered here.', 'safetunnels' ); ?></p>
			<div class="legal-cta-row center">
				<a class="legal-btn" href="<?php echo esc_url( safetunnels_mailto( 'SafeTunnels FAQ Follow-up' ) ); ?>">
					<?php safetunnels_icon( 'ti-mail' ); ?> <?php esc_html_e( 'Email Support', 'safetunnels' ); ?>
				</a>
				<a class="legal-btn out" href="<?php echo esc_url( safetunnels_page_url( 'support' ) ); ?>">
					<?php safetunnels_icon( 'ti-headset' ); ?> <?php esc_html_e( 'Browse support topics', 'safetunnels' ); ?>
				</a>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
