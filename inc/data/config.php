<?php
/**
 * Single source of truth for every configurable fact in the theme.
 *
 * Values are stored as theme mods (so they survive in the Customizer and in
 * exported/imported settings) and fall back to the defaults below — which are
 * copied verbatim from the React app's `legalMeta.ts`, `.env.production` and
 * `premiumPlans.ts` so a freshly activated theme behaves exactly like the live
 * site with zero configuration.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default value for every theme mod the theme reads.
 *
 * @return array<string, mixed>
 */
function safetunnels_defaults() {
	$defaults = array(
		// ── Identity ───────────────────────────────────────────────────
		'app_name'         => 'SafeTunnels',
		'brand_name'       => 'Tunnel Network',
		'developer_name'   => 'ZanaTech',
		'support_email'    => 'lalehmb1985@gmail.com',
		'twitter_handle'   => '',
		'og_image'         => '',

		// ── Backend ────────────────────────────────────────────────────
		// Same API the React app talks to (VITE_API_BASE_URL).
		'api_base_url'     => 'https://core.safetunnels.net',
		// Proxy browser calls through WordPress so the browser never has to
		// cross an origin (kills CORS problems on a new domain).
		'use_api_proxy'    => true,
		'reviews_ttl'      => 300,

		// ── Download ───────────────────────────────────────────────────
		'apk_url'          => 'https://safetunnels.net/downloads/safetunnels.apk',
		'apk_package'      => 'com.sagetunnel.android',
		'apk_version'      => '',
		'apk_size'         => '',
		'min_android'      => 'Android 8.0+',
		'google_play_url'  => 'https://play.google.com/store/apps/details?id=net.packsi.tunnels',
		'app_store_url'    => '',

		// ── Legal ──────────────────────────────────────────────────────
		'legal_effective'  => '2026-07-20',
		'legal_updated'    => '2026-07-20',
		'privacy_updated'  => '2026-10-07',
		'privacy_version'  => '1.1',
		'terms_version'    => '1.0',

		// ── Hero counters ──────────────────────────────────────────────
		'stat_countries'   => '50+',
		'stat_encryption'  => 'AES-256',
		'stat_support'     => '24/7',
	);

	/**
	 * Filters the theme's default configuration.
	 *
	 * @param array $defaults Default values.
	 */
	return apply_filters( 'safetunnels_defaults', $defaults );
}

/**
 * Reads one configuration value (theme mod first, default second).
 *
 * @param string $key     Setting key, without the `safetunnels_` prefix.
 * @param mixed  $fallback Optional explicit fallback.
 * @return mixed
 */
function safetunnels_opt( $key, $fallback = null ) {
	$defaults = safetunnels_defaults();
	$default  = array_key_exists( $key, $defaults ) ? $defaults[ $key ] : $fallback;
	$value    = get_theme_mod( 'safetunnels_' . $key, $default );

	if ( '' === $value || null === $value ) {
		$value = $default;
	}

	/**
	 * Filters a single configuration value.
	 *
	 * @param mixed  $value Resolved value.
	 * @param string $key   Setting key.
	 */
	return apply_filters( 'safetunnels_opt', $value, $key );
}

/**
 * Builds a `mailto:` link with a pre-filled subject — mirrors `mailto()` in
 * the React app's legalMeta.ts.
 *
 * @param string $subject Subject line.
 * @return string
 */
function safetunnels_mailto( $subject ) {
	return 'mailto:' . safetunnels_opt( 'support_email' ) . '?subject=' . rawurlencode( $subject );
}

/**
 * Absolute URL of a theme asset.
 *
 * @param string $path Path relative to assets/.
 * @return string
 */
function safetunnels_asset( $path ) {
	return get_theme_file_uri( 'assets/' . ltrim( $path, '/' ) );
}

/**
 * The direct APK download URL, routed through the theme's tracked
 * `/download/` endpoint when tracking is possible.
 *
 * @return string
 */
function safetunnels_apk_url() {
	return esc_url( safetunnels_opt( 'apk_url' ) );
}

/**
 * Third-party service URLs referenced by the Privacy Policy.
 *
 * @return array<string, string>
 */
function safetunnels_external_urls() {
	return array(
		'google_privacy' => 'https://policies.google.com/privacy',
		'google_admob'   => 'https://support.google.com/admob/answer/6128543',
		'play_store'     => 'https://play.google.com/store/apps/details?id=' . safetunnels_opt( 'apk_package' ),
	);
}
