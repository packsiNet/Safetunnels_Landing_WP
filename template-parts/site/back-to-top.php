<?php
/**
 * Floating "back to top" control.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;
?>
<button type="button" class="st-to-top" id="st-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'safetunnels' ); ?>" hidden>
	<?php safetunnels_icon( 'ti-arrow-up' ); ?>
</button>
