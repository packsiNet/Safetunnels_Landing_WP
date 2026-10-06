<?php
/**
 * "How It Works" — three steps.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;
?>
<section id="how-it-works">
	<div class="inner">
		<?php
		safetunnels_section_head(
			__( 'How It Works', 'safetunnels' ),
			esc_html__( 'Three Steps. That\'s It.', 'safetunnels' ),
			esc_html__( 'From download to browsing without limits — you\'ll be connected in under two minutes.', 'safetunnels' )
		);
		?>

		<ol class="steps-grid">
			<?php foreach ( safetunnels_steps() as $st_index => $st_step ) : ?>
				<li class="step st-reveal">
					<div class="step-ico"><?php safetunnels_icon( $st_step['icon'] ); ?></div>
					<div class="step-num"><?php echo esc_html( $st_index + 1 ); ?></div>
					<h3><?php echo wp_kses_post( $st_step['title'] ); ?></h3>
					<p><?php echo esc_html( $st_step['desc'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
