<?php
/**
 * "Why Tunnel Network" cards plus the dark feature block.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;
?>
<section id="features">
	<div class="inner">
		<?php
		safetunnels_section_head(
			__( 'Why Tunnel Network', 'safetunnels' ),
			esc_html__( 'Built Different. Built Better.', 'safetunnels' ),
			esc_html__( 'It\'s specifically for Iranians abroad. Simply fast, and secure.', 'safetunnels' )
		);
		?>

		<div class="why-grid">
			<?php foreach ( safetunnels_why_cards() as $st_card ) : ?>
				<div class="why-card st-reveal">
					<div class="why-ico"><?php safetunnels_icon( $st_card['icon'] ); ?></div>
					<h3><?php echo esc_html( $st_card['title'] ); ?></h3>
					<p><?php echo esc_html( $st_card['desc'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="feat-block">
			<div class="feat-block-title">
				<?php esc_html_e( 'Serious Security.', 'safetunnels' ); ?><br />
				<span><?php esc_html_e( 'Unmatched Speed.', 'safetunnels' ); ?></span>
			</div>
			<p class="feat-block-sub">
				<?php esc_html_e( 'Advanced Encryption · Routing · WireGuard · Privacy-First · Split Tunneling', 'safetunnels' ); ?>
			</p>

			<div class="feat-grid">
				<?php foreach ( safetunnels_feature_items() as $st_item ) : ?>
					<div class="feat-item st-reveal">
						<?php safetunnels_icon( $st_item['icon'] ); ?>
						<h4><?php echo wp_kses_post( $st_item['title'] ); ?></h4>
						<p><?php echo esc_html( $st_item['desc'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
