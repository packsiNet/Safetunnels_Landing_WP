<?php
/**
 * Landing FAQ accordion.
 *
 * Answers are always in the DOM (the accordion only hides them visually), so
 * the FAQPage schema and the crawled text agree.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$st_items = isset( $args['items'] ) ? $args['items'] : safetunnels_landing_faq();
?>
<section id="faq">
	<div class="inner">
		<?php
		safetunnels_section_head(
			__( 'Help', 'safetunnels' ),
			esc_html__( 'Have Questions?', 'safetunnels' ),
			esc_html__( 'The short answers. The full Help Center covers everything else.', 'safetunnels' )
		);
		?>

		<div class="faq-list">
			<?php foreach ( $st_items as $st_index => $st_item ) : ?>
				<div class="faq-item">
					<button type="button" class="faq-q" aria-expanded="false" aria-controls="st-faq-a-<?php echo esc_attr( $st_index ); ?>">
						<?php echo esc_html( $st_item['q'] ); ?>
						<?php safetunnels_icon( 'ti-plus' ); ?>
					</button>
					<div class="faq-a" id="st-faq-a-<?php echo esc_attr( $st_index ); ?>">
						<?php echo esc_html( $st_item['a'] ); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<p class="faq-more">
			<a class="btn-ghost" href="<?php echo esc_url( safetunnels_page_url( 'faq' ) ); ?>">
				<?php safetunnels_icon( 'ti-help-circle' ); ?>
				<?php esc_html_e( 'Read the full FAQ', 'safetunnels' ); ?>
			</a>
		</p>
	</div>
</section>
