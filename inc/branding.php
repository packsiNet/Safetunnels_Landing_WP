<?php
/**
 * Site icons and WordPress login branding.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

/**
 * Supplies a bundled site icon when no custom icon has been selected.
 *
 * @param string $url     Existing icon URL.
 * @param int    $size    Requested size.
 * @param int    $blog_id Requested site ID.
 * @return string
 */
function safetunnels_site_icon_url( $url, $size, $blog_id ) {
	$site_id = $blog_id ? $blog_id : get_current_blog_id();
	$icon_id = is_multisite() ? get_blog_option( $site_id, 'site_icon' ) : get_option( 'site_icon' );
	if ( $icon_id ) {
		return $url;
	}

	foreach ( array( 32, 180, 192, 270, 512 ) as $icon_size ) {
		if ( $size <= $icon_size ) {
			break;
		}
	}

	return get_theme_file_uri( 'assets/img/site-icon-' . $icon_size . '.png' );
}
add_filter( 'get_site_icon_url', 'safetunnels_site_icon_url', 10, 3 );

/**
 * Loads the login and admin toolbar branding.
 *
 * @return void
 */
function safetunnels_branding_styles() {
	wp_enqueue_style( 'safetunnels-branding', get_theme_file_uri( 'assets/css/branding.css' ), array(), wp_get_theme()->get( 'Version' ) );
}
add_action( 'login_enqueue_scripts', 'safetunnels_branding_styles' );
add_action( 'admin_enqueue_scripts', 'safetunnels_branding_styles' );

/**
 * Loads toolbar branding on the public site for logged-in users.
 *
 * @return void
 */
function safetunnels_toolbar_styles() {
	if ( is_admin_bar_showing() ) {
		safetunnels_branding_styles();
	}
}
add_action( 'wp_enqueue_scripts', 'safetunnels_toolbar_styles' );

/**
 * Links the login logo to the site homepage.
 *
 * @return string
 */
function safetunnels_login_headerurl() {
	return home_url( '/' );
}
add_filter( 'login_headerurl', 'safetunnels_login_headerurl' );

/**
 * Labels the login logo with the site's name.
 *
 * @return string
 */
function safetunnels_login_headertext() {
	return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'safetunnels_login_headertext' );
