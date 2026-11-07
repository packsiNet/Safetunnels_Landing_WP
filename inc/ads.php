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

/** Keep ads off error, search and payment receipt views. */
function safetunnels_ads_enabled() {
	return ! is_404() && ! is_search() && ! is_page_template( 'page-templates/template-payment-result.php' );
}

/** WordPress deduplicates the shared loader by its script handle. */
function safetunnels_enqueue_ads() {
	if ( ! safetunnels_ads_enabled() ) {
		return;
	}

	wp_enqueue_script(
		'safetunnels-adsense',
		'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2784419629304512',
		array(),
		null,
		false
	);
	wp_enqueue_style(
		'safetunnels-ads',
		get_theme_file_uri( 'assets/css/ads.css' ),
		array( 'safetunnels-internal' ),
		safetunnels_asset_version( 'assets/css/ads.css' )
	);
}
// Load after the shell resets so they cannot clear the ad gutters and centering.
add_action( 'wp_enqueue_scripts', 'safetunnels_enqueue_ads', 20 );

function safetunnels_adsense_script_tag( $tag, $handle ) {
	if ( 'safetunnels-adsense' === $handle ) {
		$tag = str_replace( ' src=', ' async crossorigin="anonymous" src=', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'safetunnels_adsense_script_tag', 20, 2 );
