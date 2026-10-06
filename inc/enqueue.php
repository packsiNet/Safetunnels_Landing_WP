<?php
/**
 * Asset loading.
 *
 * Styles are split by shell so a legal page never downloads the landing
 * page's 60KB of animation CSS, and vice-versa. Scripts are deferred and the
 * icon webfont is preconnected, which is what keeps LCP low enough for the
 * Core Web Vitals side of SEO to stay green.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

const SAFETUNNELS_VERSION = '1.0.0';

/**
 * URL of the Tabler icon webfont — filterable so it can be self-hosted.
 *
 * @return string
 */
function safetunnels_icon_font_url() {
	return apply_filters(
		'safetunnels_icon_font_url',
		'https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.24.0/dist/tabler-icons.min.css'
	);
}

/**
 * Cache-busting version for a theme asset — the file's mtime in dev, the theme
 * version in production.
 *
 * @param string $relative Path relative to the theme root.
 * @return string
 */
function safetunnels_asset_version( $relative ) {
	$file = get_theme_file_path( $relative );

	if ( file_exists( $file ) ) {
		return (string) filemtime( $file );
	}

	return SAFETUNNELS_VERSION;
}

/**
 * Enqueues front-end styles and scripts.
 *
 * @return void
 */
function safetunnels_enqueue_assets() {
	$shell = safetunnels_shell();

	wp_enqueue_style( 'safetunnels-icons', safetunnels_icon_font_url(), array(), null );

	wp_enqueue_style(
		'safetunnels-base',
		get_stylesheet_uri(),
		array( 'safetunnels-icons' ),
		safetunnels_asset_version( 'style.css' )
	);

	if ( 'tn' === $shell ) {
		wp_enqueue_style(
			'safetunnels-tunnels',
			safetunnels_asset( 'css/tunnels.css' ),
			array( 'safetunnels-base' ),
			safetunnels_asset_version( 'assets/css/tunnels.css' )
		);
	}

	// The document shell also carries the shared article styling used by the
	// blog, search results and 404 — so it loads on every non-landing view.
	if ( 'lg' === $shell ) {
		wp_enqueue_style(
			'safetunnels-legal',
			safetunnels_asset( 'css/legal.css' ),
			array( 'safetunnels-base' ),
			safetunnels_asset_version( 'assets/css/legal.css' )
		);
	}

	wp_enqueue_style(
		'safetunnels-theme',
		safetunnels_asset( 'css/theme.css' ),
		array( 'safetunnels-base' ),
		safetunnels_asset_version( 'assets/css/theme.css' )
	);

	wp_enqueue_script(
		'safetunnels-app',
		safetunnels_asset( 'js/app.js' ),
		array(),
		safetunnels_asset_version( 'assets/js/app.js' ),
		true
	);
	wp_script_add_data( 'safetunnels-app', 'defer', true );

	wp_localize_script( 'safetunnels-app', 'SAFETUNNELS', safetunnels_script_data() );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'safetunnels_enqueue_assets' );

/**
 * The configuration object handed to the front-end script.
 *
 * Mirrors what the React app read from `import.meta.env` plus the plan tables,
 * so the purchase and review flows behave identically.
 *
 * @return array<string, mixed>
 */
function safetunnels_script_data() {
	$periods = safetunnels_premium_periods();
	$plans   = array();

	foreach ( $periods as $key => $period ) {
		$plans[ $key ] = array(
			'label'     => $period['label'],
			'price'     => $period['price'],
			'per'       => $period['per'],
			'cadence'   => $period['cadence'],
			'weeklyEq'  => $period['weekly_eq'],
			'badge'     => $period['badge'],
			'disabled'  => (bool) $period['disabled'],
			'packageId' => $period['package_id'],
			'link'      => $period['link'],
		);
	}

	$noads = safetunnels_noads_plan();

	return array(
		'restUrl'       => esc_url_raw( rest_url( 'safetunnels/v1/' ) ),
		'nonce'         => wp_create_nonce( 'wp_rest' ),
		'apiBase'       => esc_url_raw( untrailingslashit( safetunnels_opt( 'api_base_url' ) ) ),
		'useProxy'      => (bool) safetunnels_opt( 'use_api_proxy' ),
		'apkUrl'        => safetunnels_apk_url(),
		'apkPackage'    => safetunnels_opt( 'apk_package' ),
		'defaultPeriod' => safetunnels_default_period(),
		'plans'         => $plans,
		'noads'         => array(
			'price'     => $noads['price'],
			'packageId' => $noads['package_id'],
			'link'      => $noads['link'],
		),
		'i18n'          => array(
			'noAdsName'      => __( 'No Ads (Lifetime)', 'safetunnels' ),
			'premiumName'    => __( 'Premium', 'safetunnels' ),
			'keyNotFound'    => __( 'We couldn\'t find an account with that key. Double-check you copied it from the app.', 'safetunnels' ),
			'keyInvalid'     => __( 'That doesn\'t look like a valid key.', 'safetunnels' ),
			'verifyFailed'   => __( 'Could not verify your key. Please try again.', 'safetunnels' ),
			'reviewFailed'   => __( 'Error posting comment', 'safetunnels' ),
			'reviewsFailed'  => __( 'Could not load reviews right now.', 'safetunnels' ),
			'buyingFor'      => __( 'Buying %1$s for account %2$s.', 'safetunnels' ),
			'commentsSuffix' => __( 'Based on %s reviews', 'safetunnels' ),
		),
	);
}

/**
 * Preconnects to the icon CDN and the API host so the first request to each
 * doesn't pay for DNS + TLS.
 *
 * @param string[] $hints Existing hints.
 * @param string   $relation Relation type.
 * @return string[]
 */
function safetunnels_resource_hints( $hints, $relation ) {
	if ( 'preconnect' === $relation ) {
		$hints[] = array(
			'href'        => 'https://cdn.jsdelivr.net',
			'crossorigin' => 'anonymous',
		);

		$api = wp_parse_url( safetunnels_opt( 'api_base_url' ) );
		if ( ! empty( $api['host'] ) ) {
			$hints[] = array(
				'href'        => $api['scheme'] . '://' . $api['host'],
				'crossorigin' => 'anonymous',
			);
		}
	}

	return $hints;
}
add_filter( 'wp_resource_hints', 'safetunnels_resource_hints', 10, 2 );

/**
 * Applies the `defer` flag registered above.
 *
 * @param string $tag    Script tag.
 * @param string $handle Script handle.
 * @return string
 */
function safetunnels_defer_scripts( $tag, $handle ) {
	if ( wp_scripts()->get_data( $handle, 'defer' ) && false === strpos( $tag, ' defer' ) ) {
		$tag = str_replace( ' src=', ' defer src=', $tag );
	}

	return $tag;
}
add_filter( 'script_loader_tag', 'safetunnels_defer_scripts', 10, 2 );

/**
 * Trims WordPress head output that only adds weight or leaks version numbers.
 *
 * @return void
 */
function safetunnels_clean_head() {
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );

	// The emoji polyfill costs a script, a stylesheet and a DNS lookup, and
	// every modern browser already renders emoji natively.
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	add_filter( 'emoji_svg_url', '__return_false' );
}
add_action( 'init', 'safetunnels_clean_head' );

/**
 * Drops the block-library CSS on views that render no blocks — the landing
 * page and every hard-coded template.
 *
 * @return void
 */
function safetunnels_dequeue_block_styles() {
	if ( is_singular() && ! is_front_page() ) {
		$content = get_post_field( 'post_content', get_queried_object_id() );
		if ( has_blocks( $content ) ) {
			return;
		}
	}

	if ( is_home() || is_archive() || is_search() ) {
		return;
	}

	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}
add_action( 'wp_enqueue_scripts', 'safetunnels_dequeue_block_styles', 100 );
