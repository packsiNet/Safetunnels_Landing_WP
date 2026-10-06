<?php
/**
 * Search form.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$st_id = 'st-search-' . wp_unique_id();
?>
<form role="search" method="get" class="st-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $st_id ); ?>"><?php esc_html_e( 'Search this site', 'safetunnels' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $st_id ); ?>" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search the site…', 'safetunnels' ); ?>" />
	<button type="submit">
		<?php safetunnels_icon( 'ti-search' ); ?>
		<span class="screen-reader-text"><?php esc_html_e( 'Search', 'safetunnels' ); ?></span>
	</button>
</form>
