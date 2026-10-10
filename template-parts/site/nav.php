<?php
/**
 * Sticky navigation.
 *
 * Uses the `primary` menu when one is assigned and falls back to the theme's
 * own page registry otherwise, so navigation is never empty on a fresh
 * install. The mobile drawer is an addition on top of the React original,
 * which simply hid the links below 640px.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$st_shell   = isset( $args['shell'] ) ? $args['shell'] : safetunnels_shell();
$st_current = safetunnels_current_page_key();
$st_home    = 'home' === $st_current;

$st_links = array();

if ( $st_home ) {
	foreach ( array( 'features' => __( 'Features', 'safetunnels' ), 'download' => __( 'Download', 'safetunnels' ), 'pricing' => __( 'Pricing', 'safetunnels' ), 'faq' => __( 'FAQ', 'safetunnels' ) ) as $st_section => $st_label ) {
		$st_links[] = array( 'label' => $st_label, 'url' => '#' . $st_section, 'current' => false );
	}
} elseif ( has_nav_menu( 'primary' ) ) {
	$st_items = wp_get_nav_menu_items( get_nav_menu_locations()['primary'] );

	foreach ( (array) $st_items as $st_item ) {
		if ( (int) $st_item->menu_item_parent ) {
			continue;
		}

		$st_links[] = array(
			'label'   => $st_item->title,
			'url'     => $st_item->url,
			'current' => untrailingslashit( $st_item->url ) === untrailingslashit( safetunnels_canonical_url() ),
		);
	}
} else {
	foreach ( safetunnels_page_registry() as $st_key => $st_page ) {
		if ( empty( $st_page['nav'] ) ) {
			continue;
		}

		$st_links[] = array(
			'label'   => $st_page['title'],
			'url'     => safetunnels_page_url( $st_key ),
			'current' => $st_current === $st_key,
		);
	}
}
?>
<nav aria-label="<?php esc_attr_e( 'Primary', 'safetunnels' ); ?>">
	<div class="nav-row">
		<a class="nav-logo" href="<?php echo esc_url( safetunnels_page_url( 'home' ) ); ?>" aria-label="<?php echo esc_attr( safetunnels_opt( 'app_name' ) ); ?>">
			<span class="nav-logo-icon">
				<?php safetunnels_icon( 'tn' === $st_shell ? 'ti-route' : 'ti-shield-lock' ); ?>
			</span>
			<?php echo esc_html( 'tn' === $st_shell ? safetunnels_opt( 'brand_name' ) : safetunnels_opt( 'app_name' ) ); ?>
		</a>

		<div class="nav-links" id="st-nav-links">

			<?php foreach ( $st_links as $st_link ) : ?>
				<a
					href="<?php echo esc_url( $st_link['url'] ); ?>"
					<?php echo $st_link['current'] ? ' class="active" aria-current="page"' : ''; ?>
				><?php echo esc_html( $st_link['label'] ); ?></a>
			<?php endforeach; ?>
		</div>

		<?php if ( ! $st_home ) : ?>
			<a class="back-btn" href="<?php echo esc_url( safetunnels_page_url( 'home' ) ); ?>">
				<?php safetunnels_icon( 'ti-arrow-left' ); ?> <?php esc_html_e( 'Tunnels', 'safetunnels' ); ?>
			</a>
		<?php endif; ?>

		<a class="nav-cta" href="<?php echo esc_url( $st_home ? '#download' : safetunnels_apk_url() ); ?>" <?php if ( ! $st_home ) : ?>download
			<?php echo safetunnels_track_attrs( safetunnels_opt( 'apk_package' ), 'Tunnel Network APK' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php endif; ?>>
			<?php safetunnels_icon( 'ti-download' ); ?> <?php esc_html_e( 'Download', 'safetunnels' ); ?>
		</a>

		<button type="button" class="nav-burger" id="st-nav-burger" aria-expanded="false" aria-controls="st-nav-drawer" aria-label="<?php esc_attr_e( 'Open menu', 'safetunnels' ); ?>">
			<span></span><span></span><span></span>
		</button>
	</div>
</nav>

<div class="st-drawer" id="st-nav-drawer" hidden>
	<div class="st-drawer-inner">

		<?php foreach ( $st_links as $st_link ) : ?>
			<a href="<?php echo esc_url( $st_link['url'] ); ?>"><?php safetunnels_icon( 'ti-chevron-right' ); ?> <?php echo esc_html( $st_link['label'] ); ?></a>
		<?php endforeach; ?>

		<?php if ( ! $st_home ) : ?>
		<a href="<?php echo esc_url( safetunnels_page_url( 'support' ) ); ?>"><?php safetunnels_icon( 'ti-headset' ); ?> <?php esc_html_e( 'Support', 'safetunnels' ); ?></a>
		<?php endif; ?>

		<a class="st-drawer-cta" href="<?php echo esc_url( $st_home ? '#download' : safetunnels_apk_url() ); ?>" <?php if ( ! $st_home ) : ?>download
			<?php echo safetunnels_track_attrs( safetunnels_opt( 'apk_package' ), 'Tunnel Network APK' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php endif; ?>>
			<?php safetunnels_icon( 'ti-download' ); ?> <?php esc_html_e( 'Download for Android', 'safetunnels' ); ?>
		</a>
	</div>
</div>
