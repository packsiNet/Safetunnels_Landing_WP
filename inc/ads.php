<?php
/** Serve the theme's ads.txt at the site root. */
defined( 'ABSPATH' ) || exit;

function safetunnels_serve_ads_txt() {
	$path = wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH );
	$ads_path = wp_parse_url( home_url( '/Ads.txt' ), PHP_URL_PATH );

	if ( ! is_string( $path ) || 0 !== strcasecmp( $path, $ads_path ) ) {
		return;
	}

	status_header( 200 );
	header( 'Content-Type: text/plain; charset=utf-8' );
	readfile( get_theme_file_path( 'Ads.txt' ) );
	exit;
}
add_action( 'template_redirect', 'safetunnels_serve_ads_txt', 0 );
