<?php
/**
 * Compact page header used by the landing-shell secondary pages.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$st_eyebrow  = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$st_title    = isset( $args['title'] ) ? $args['title'] : get_the_title();
$st_subtitle = isset( $args['subtitle'] ) ? $args['subtitle'] : '';
$st_actions  = isset( $args['actions'] ) ? $args['actions'] : array();
?>
<header class="st-page-hero">
	<div class="inner">
		<?php safetunnels_breadcrumbs(); ?>
		<?php if ( $st_eyebrow ) : ?>
			<div class="sec-label"><?php echo esc_html( $st_eyebrow ); ?></div>
		<?php endif; ?>
		<h1 class="st-page-title"><?php echo esc_html( $st_title ); ?></h1>
		<?php if ( $st_subtitle ) : ?>
			<p class="st-page-sub"><?php echo esc_html( $st_subtitle ); ?></p>
		<?php endif; ?>

		<?php if ( $st_actions ) : ?>
			<div class="hero-ctas st-page-actions">
				<?php foreach ( $st_actions as $st_action ) : ?>
					<a class="<?php echo esc_attr( $st_action['class'] ); ?>" href="<?php echo esc_url( $st_action['url'] ); ?>"
						<?php echo ! empty( $st_action['download'] ) ? ' download' : ''; ?>
						<?php echo ! empty( $st_action['package'] ) ? safetunnels_track_attrs( $st_action['package'], $st_action['label'] ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
						<?php safetunnels_icon( $st_action['icon'] ); ?> <?php echo esc_html( $st_action['label'] ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</header>
