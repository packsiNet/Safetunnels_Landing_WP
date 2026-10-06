<?php
/**
 * Sticky table of contents for the policy pages.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$st_sections = isset( $args['sections'] ) ? $args['sections'] : array();
$st_label    = isset( $args['label'] ) ? $args['label'] : __( 'Sections', 'safetunnels' );
?>
<nav class="legal-toc glass" aria-label="<?php echo esc_attr( $st_label ); ?>">
	<div class="legal-toc-label"><?php esc_html_e( 'On this page', 'safetunnels' ); ?></div>
	<?php foreach ( $st_sections as $st_id => $st_title ) : ?>
		<a href="#<?php echo esc_attr( $st_id ); ?>"><?php echo esc_html( $st_title ); ?></a>
	<?php endforeach; ?>
</nav>
