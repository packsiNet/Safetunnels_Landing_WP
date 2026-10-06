<?php
/**
 * Customizer panel — everything an administrator can change without editing
 * a file: identity, backend, download links, and the full pricing/checkout
 * table (so a new RevenueCat Purchase Link never needs a deploy).
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sanitises a checkbox.
 *
 * @param mixed $value Raw value.
 * @return bool
 */
function safetunnels_sanitize_checkbox( $value ) {
	return (bool) $value;
}

/**
 * Registers the panel.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 * @return void
 */
function safetunnels_customize_register( $wp_customize ) {
	$wp_customize->add_panel(
		'safetunnels',
		array(
			'title'       => __( 'SafeTunnels', 'safetunnels' ),
			'description' => __( 'Every configurable fact about the app, the backend, and the checkout.', 'safetunnels' ),
			'priority'    => 20,
		)
	);

	$defaults = safetunnels_defaults();

	// ── Identity ───────────────────────────────────────────────────────
	$wp_customize->add_section(
		'safetunnels_identity',
		array(
			'title' => __( 'Identity', 'safetunnels' ),
			'panel' => 'safetunnels',
		)
	);

	$identity = array(
		'app_name'       => array( __( 'App name', 'safetunnels' ), 'text' ),
		'brand_name'     => array( __( 'Navigation brand', 'safetunnels' ), 'text' ),
		'developer_name' => array( __( 'Developer / publisher', 'safetunnels' ), 'text' ),
		'support_email'  => array( __( 'Support email', 'safetunnels' ), 'email' ),
		'twitter_handle' => array( __( 'X / Twitter handle', 'safetunnels' ), 'text' ),
	);

	foreach ( $identity as $key => $field ) {
		$wp_customize->add_setting(
			'safetunnels_' . $key,
			array(
				'default'           => $defaults[ $key ],
				'sanitize_callback' => 'email' === $field[1] ? 'sanitize_email' : 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'safetunnels_' . $key,
			array(
				'label'   => $field[0],
				'section' => 'safetunnels_identity',
				'type'    => 'email' === $field[1] ? 'email' : 'text',
			)
		);
	}

	$wp_customize->add_setting(
		'safetunnels_og_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'safetunnels_og_image',
			array(
				'label'       => __( 'Default social image', 'safetunnels' ),
				'description' => __( '1200×630. Used for Open Graph and Twitter cards when a page has no featured image.', 'safetunnels' ),
				'section'     => 'safetunnels_identity',
			)
		)
	);

	// ── Backend ────────────────────────────────────────────────────────
	$wp_customize->add_section(
		'safetunnels_backend',
		array(
			'title'       => __( 'Backend &amp; Tracking', 'safetunnels' ),
			'description' => __( 'The SafeTunnels API that serves reviews, key verification and download statistics.', 'safetunnels' ),
			'panel'       => 'safetunnels',
		)
	);

	$wp_customize->add_setting(
		'safetunnels_api_base_url',
		array(
			'default'           => $defaults['api_base_url'],
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		'safetunnels_api_base_url',
		array(
			'label'       => __( 'API base URL', 'safetunnels' ),
			'description' => __( 'No trailing slash, e.g. https://core.safetunnels.net', 'safetunnels' ),
			'section'     => 'safetunnels_backend',
			'type'        => 'url',
		)
	);

	$wp_customize->add_setting(
		'safetunnels_use_api_proxy',
		array(
			'default'           => $defaults['use_api_proxy'],
			'sanitize_callback' => 'safetunnels_sanitize_checkbox',
		)
	);
	$wp_customize->add_control(
		'safetunnels_use_api_proxy',
		array(
			'label'       => __( 'Proxy API calls through WordPress', 'safetunnels' ),
			'description' => __( 'Recommended. Keeps every browser request same-origin, so the API never needs a CORS entry for this domain.', 'safetunnels' ),
			'section'     => 'safetunnels_backend',
			'type'        => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'safetunnels_reviews_ttl',
		array(
			'default'           => $defaults['reviews_ttl'],
			'sanitize_callback' => 'absint',
		)
	);
	$wp_customize->add_control(
		'safetunnels_reviews_ttl',
		array(
			'label'       => __( 'Review cache lifetime (seconds)', 'safetunnels' ),
			'section'     => 'safetunnels_backend',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 30,
				'step' => 30,
			),
		)
	);

	// ── Download ───────────────────────────────────────────────────────
	$wp_customize->add_section(
		'safetunnels_download',
		array(
			'title' => __( 'Download', 'safetunnels' ),
			'panel' => 'safetunnels',
		)
	);

	$download = array(
		'apk_url'         => array( __( 'Direct APK URL', 'safetunnels' ), 'url' ),
		'apk_package'     => array( __( 'Android package name', 'safetunnels' ), 'text' ),
		'apk_version'     => array( __( 'APK version (optional)', 'safetunnels' ), 'text' ),
		'apk_size'        => array( __( 'APK size, e.g. 24 MB (optional)', 'safetunnels' ), 'text' ),
		'min_android'     => array( __( 'Minimum Android version', 'safetunnels' ), 'text' ),
		'google_play_url' => array( __( 'Google Play URL (optional)', 'safetunnels' ), 'url' ),
		'app_store_url'   => array( __( 'App Store URL (optional)', 'safetunnels' ), 'url' ),
	);

	foreach ( $download as $key => $field ) {
		$wp_customize->add_setting(
			'safetunnels_' . $key,
			array(
				'default'           => $defaults[ $key ],
				'sanitize_callback' => 'url' === $field[1] ? 'esc_url_raw' : 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'safetunnels_' . $key,
			array(
				'label'   => $field[0],
				'section' => 'safetunnels_download',
				'type'    => $field[1],
			)
		);
	}

	// ── Pricing & checkout ─────────────────────────────────────────────
	$wp_customize->add_section(
		'safetunnels_pricing',
		array(
			'title'       => __( 'Pricing &amp; Checkout', 'safetunnels' ),
			'description' => __( 'Prices shown on the site and the RevenueCat hosted Purchase Link each plan sends the buyer to. The account key is appended as the app_user_id path segment automatically.', 'safetunnels' ),
			'panel'       => 'safetunnels',
		)
	);

	$plan_defaults = safetunnels_plan_defaults();

	$wp_customize->add_setting(
		'safetunnels_plan_default',
		array(
			'default'           => 'quarterly',
			'sanitize_callback' => 'sanitize_key',
		)
	);
	$wp_customize->add_control(
		'safetunnels_plan_default',
		array(
			'label'   => __( 'Pre-selected billing period', 'safetunnels' ),
			'section' => 'safetunnels_pricing',
			'type'    => 'select',
			'choices' => wp_list_pluck( $plan_defaults, 'label' ),
		)
	);

	foreach ( $plan_defaults as $key => $plan ) {
		$fields = array(
			'price'      => array( 'text', __( 'price', 'safetunnels' ), 'sanitize_text_field' ),
			'badge'      => array( 'text', __( 'badge', 'safetunnels' ), 'sanitize_text_field' ),
			'package_id' => array( 'text', __( 'package id', 'safetunnels' ), 'sanitize_text_field' ),
			'link'       => array( 'url', __( 'purchase link', 'safetunnels' ), 'esc_url_raw' ),
		);

		foreach ( $fields as $field => $spec ) {
			$wp_customize->add_setting(
				"safetunnels_plan_{$key}_{$field}",
				array(
					'default'           => $plan[ $field ],
					'sanitize_callback' => $spec[2],
				)
			);
			$wp_customize->add_control(
				"safetunnels_plan_{$key}_{$field}",
				array(
					/* translators: 1: plan label, 2: field name. */
					'label'   => sprintf( __( '%1$s — %2$s', 'safetunnels' ), $plan['label'], $spec[1] ),
					'section' => 'safetunnels_pricing',
					'type'    => $spec[0],
				)
			);
		}

		$wp_customize->add_setting(
			"safetunnels_plan_{$key}_disabled",
			array(
				'default'           => $plan['disabled'],
				'sanitize_callback' => 'safetunnels_sanitize_checkbox',
			)
		);
		$wp_customize->add_control(
			"safetunnels_plan_{$key}_disabled",
			array(
				/* translators: %s: plan label. */
				'label'   => sprintf( __( '%s — mark as “Soon” (not purchasable)', 'safetunnels' ), $plan['label'] ),
				'section' => 'safetunnels_pricing',
				'type'    => 'checkbox',
			)
		);
	}

	$noads = array(
		'noads_price'      => array( __( 'Lifetime No-Ads — price', 'safetunnels' ), 'text', 'sanitize_text_field', '$9.99' ),
		'noads_package_id' => array( __( 'Lifetime No-Ads — package id', 'safetunnels' ), 'text', 'sanitize_text_field', '$rc_lifetime' ),
		'noads_link'       => array( __( 'Lifetime No-Ads — purchase link', 'safetunnels' ), 'url', 'esc_url_raw', 'https://pay.rev.cat/jqqpmijwfwbsjznm' ),
	);

	foreach ( $noads as $key => $field ) {
		$wp_customize->add_setting(
			'safetunnels_' . $key,
			array(
				'default'           => $field[3],
				'sanitize_callback' => $field[2],
			)
		);
		$wp_customize->add_control(
			'safetunnels_' . $key,
			array(
				'label'   => $field[0],
				'section' => 'safetunnels_pricing',
				'type'    => $field[1],
			)
		);
	}

	// ── Legal ──────────────────────────────────────────────────────────
	$wp_customize->add_section(
		'safetunnels_legal',
		array(
			'title' => __( 'Legal', 'safetunnels' ),
			'panel' => 'safetunnels',
		)
	);

	$legal = array(
		'legal_effective' => __( 'Effective date (YYYY-MM-DD)', 'safetunnels' ),
		'legal_updated'   => __( 'Last updated (YYYY-MM-DD)', 'safetunnels' ),
		'privacy_version' => __( 'Privacy Policy version', 'safetunnels' ),
		'terms_version'   => __( 'Terms version', 'safetunnels' ),
	);

	foreach ( $legal as $key => $label ) {
		$wp_customize->add_setting(
			'safetunnels_' . $key,
			array(
				'default'           => $defaults[ $key ],
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'safetunnels_' . $key,
			array(
				'label'   => $label,
				'section' => 'safetunnels_legal',
				'type'    => 'text',
			)
		);
	}

	// ── Hero counters ──────────────────────────────────────────────────
	$wp_customize->add_section(
		'safetunnels_hero',
		array(
			'title' => __( 'Hero counters', 'safetunnels' ),
			'panel' => 'safetunnels',
		)
	);

	$hero = array(
		'stat_countries'  => __( 'Countries', 'safetunnels' ),
		'stat_encryption' => __( 'Encryption', 'safetunnels' ),
		'stat_support'    => __( 'Support', 'safetunnels' ),
	);

	foreach ( $hero as $key => $label ) {
		$wp_customize->add_setting(
			'safetunnels_' . $key,
			array(
				'default'           => $defaults[ $key ],
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			'safetunnels_' . $key,
			array(
				'label'   => $label,
				'section' => 'safetunnels_hero',
				'type'    => 'text',
			)
		);
	}
}
add_action( 'customize_register', 'safetunnels_customize_register' );
