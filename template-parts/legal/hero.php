<?php
/**
 * Document-page hero (kicker + H1 + subtitle) and breadcrumbs.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$st_eyebrow  = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$st_title    = isset( $args['title'] ) ? $args['title'] : get_the_title();
$st_subtitle = isset( $args['subtitle'] ) ? $args['subtitle'] : '';
?>
<header class="lg-hero">
	<div class="inner">
		<?php safetunnels_breadcrumbs(); ?>
		<?php if ( $st_eyebrow ) : ?>
			<div class="sec-label"><?php echo esc_html( $st_eyebrow ); ?></div>
		<?php endif; ?>
		<h1 class="lg-title"><?php echo esc_html( $st_title ); ?></h1>
		<?php if ( $st_subtitle ) : ?>
			<p class="lg-subtitle"><?php echo esc_html( $st_subtitle ); ?></p>
		<?php endif; ?>
	</div>
</header>
