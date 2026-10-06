<?php
/**
 * Marketing copy for the landing page sections.
 *
 * Every string here is ported from TunnelNetworkPage.tsx so the WordPress
 * build reads identically to the React one; keeping them in PHP arrays (rather
 * than hard-coded in the templates) means they are translatable and filterable.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

/**
 * The three "Why Tunnel Network" cards.
 *
 * @return array<int, array{icon:string, title:string, desc:string}>
 */
function safetunnels_why_cards() {
	return apply_filters(
		'safetunnels_why_cards',
		array(
			array(
				'icon'  => 'ti-shield-check',
				'title' => __( 'Strong Encryption', 'safetunnels' ),
				'desc'  => __( 'AES-256 encryption with perfect forward secrecy helps keep every connection protected end to end.', 'safetunnels' ),
			),
			array(
				'icon'  => 'ti-bolt',
				'title' => __( 'Lightning Fast', 'safetunnels' ),
				'desc'  => __( 'WireGuard-based protocol with smart routing delivers sub-20ms latency across 40+ relay nodes in 50+ countries.', 'safetunnels' ),
			),
			array(
				'icon'  => 'ti-eye-off',
				'title' => __( 'Privacy-First by Design', 'safetunnels' ),
				'desc'  => __( 'We never inspect or store the content of your traffic — your browsing stays yours alone.', 'safetunnels' ),
			),
		)
	);
}

/**
 * The nine-tile feature grid inside the dark feature block.
 *
 * @return array<int, array{icon:string, title:string, desc:string}>
 */
function safetunnels_feature_items() {
	return apply_filters(
		'safetunnels_feature_items',
		array(
			array(
				'icon'  => 'ti-lock',
				'title' => __( 'Strong Encryption', 'safetunnels' ),
				'desc'  => __( 'AES-256-GCM + ChaCha20 help keep every packet encrypted between you and the destination.', 'safetunnels' ),
			),
			array(
				'icon'  => 'ti-eye-off',
				'title' => __( 'Minimal Data Retention', 'safetunnels' ),
				'desc'  => __( 'We don\'t inspect or store the content of your traffic. See our Privacy Policy for exactly what limited data we retain.', 'safetunnels' ),
			),
			array(
				'icon'  => 'ti-switch',
				'title' => __( 'Kill Switch', 'safetunnels' ),
				'desc'  => __( 'If the tunnel drops, your connection cuts instantly. No leaks, no exposure, no exceptions — ever.', 'safetunnels' ),
			),
			array(
				'icon'  => 'ti-world',
				'title' => __( 'Iranian Apps Only', 'safetunnels' ),
				'desc'  => __( 'Only Iranian app traffic routes through the tunnel. Everything else uses your direct connection at full speed.', 'safetunnels' ),
			),
			array(
				'icon'  => 'ti-bolt',
				'title' => __( 'Fast &amp; Stable', 'safetunnels' ),
				'desc'  => __( 'WireGuard with smart failover keeps speeds high and drops close to zero — even on mobile networks.', 'safetunnels' ),
			),
			array(
				'icon'  => 'ti-device-mobile',
				'title' => __( 'Works Worldwide', 'safetunnels' ),
				'desc'  => __( 'Android, iOS, Windows, macOS, Linux — one account, unlimited simultaneous connected devices.', 'safetunnels' ),
			),
			array(
				'icon'  => 'ti-route',
				'title' => __( 'Adaptive Routing', 'safetunnels' ),
				'desc'  => __( 'Automatically selects the best available path to help maintain a stable, low-latency connection.', 'safetunnels' ),
			),
			array(
				'icon'  => 'ti-refresh',
				'title' => __( 'Auto-Update', 'safetunnels' ),
				'desc'  => __( 'Security patches ship silently in the background. You\'re always running the latest, most hardened version.', 'safetunnels' ),
			),
			array(
				'icon'  => 'ti-devices',
				'title' => __( 'Start Free, Right Now', 'safetunnels' ),
				'desc'  => __( 'No payment required, no credit card — download now and access your favorite Iranian apps instantly.', 'safetunnels' ),
			),
		)
	);
}

/**
 * The three "How It Works" steps.
 *
 * @return array<int, array{icon:string, title:string, desc:string}>
 */
function safetunnels_steps() {
	return apply_filters(
		'safetunnels_steps',
		array(
			array(
				'icon'  => 'ti-download',
				'title' => __( 'Download &amp; Install', 'safetunnels' ),
				'desc'  => __( 'Get the SafeTunnels app for your device. Available for Android today — free to start, no card required.', 'safetunnels' ),
			),
			array(
				'icon'  => 'ti-plug',
				'title' => __( 'Activate the VPN', 'safetunnels' ),
				'desc'  => __( 'Open the app and tap once — that\'s it. No settings, no config, no extra steps. Everything is automatically ready to go.', 'safetunnels' ),
			),
			array(
				'icon'  => 'ti-world',
				'title' => __( 'Use Without Limits', 'safetunnels' ),
				'desc'  => __( 'Access every Iranian app, website and service you need — fast, private, and completely unrestricted.', 'safetunnels' ),
			),
		)
	);
}

/**
 * Install instructions for the Android panel plus the "coming soon" tabs.
 *
 * @return array<string, mixed>
 */
function safetunnels_device_content() {
	$android = array(
		'icon'  => 'ti-brand-android',
		'name'  => sprintf( '%s Android', safetunnels_opt( 'brand_name' ) ),
		'req'   => safetunnels_opt( 'min_android' ),
		'steps' => array(
			array(
				'title' => __( 'Download the APK directly', 'safetunnels' ),
				'sub'   => __( 'No Play Store required — or install via Google Play', 'safetunnels' ),
			),
			array(
				'title' => __( 'Open the app and sign in', 'safetunnels' ),
				'sub'   => __( 'Your account is created automatically — no email, no password', 'safetunnels' ),
			),
			array(
				'title' => __( 'Tap Connect — you\'re live', 'safetunnels' ),
				'sub'   => __( 'Tunnel establishes in under 3 seconds', 'safetunnels' ),
			),
		),
	);

	$soon = array(
		array(
			'label' => 'iOS',
			'icon'  => 'ti-brand-apple',
		),
		array(
			'label' => 'Windows',
			'icon'  => 'ti-brand-windows',
		),
		array(
			'label' => 'macOS',
			'icon'  => 'ti-device-laptop',
		),
	);

	return apply_filters(
		'safetunnels_device_content',
		array(
			'android' => $android,
			'soon'    => $soon,
		)
	);
}

/**
 * Help-center topic cards on the Support page.
 *
 * @return array<int, array<string, string>>
 */
function safetunnels_support_topics() {
	return apply_filters(
		'safetunnels_support_topics',
		array(
			array(
				'icon'  => 'ti-credit-card',
				'title' => __( 'Subscription Issues', 'safetunnels' ),
				'desc'  => __( 'Trouble upgrading, being charged, or your Premium status not reflecting in the app.', 'safetunnels' ),
				'url'   => safetunnels_page_url( 'faq' ) . '#billing',
				'label' => __( 'View billing FAQ', 'safetunnels' ),
			),
			array(
				'icon'  => 'ti-refresh',
				'title' => __( 'Restore Purchases', 'safetunnels' ),
				'desc'  => __( 'Reconnect an existing Premium subscription after reinstalling the app.', 'safetunnels' ),
				'url'   => safetunnels_page_url( 'faq' ) . '#account',
				'label' => __( 'View account FAQ', 'safetunnels' ),
			),
			array(
				'icon'  => 'ti-device-mobile',
				'title' => __( 'Change Device', 'safetunnels' ),
				'desc'  => __( 'Moved to a new phone and need help carrying your subscription over.', 'safetunnels' ),
				'url'   => safetunnels_page_url( 'faq' ) . '#account',
				'label' => __( 'View account FAQ', 'safetunnels' ),
			),
			array(
				'icon'  => 'ti-chart-bar',
				'title' => __( 'Traffic Questions', 'safetunnels' ),
				'desc'  => __( 'Understanding what the traffic usage numbers in the app mean and how they\'re used.', 'safetunnels' ),
				'url'   => safetunnels_page_url( 'faq' ) . '#privacy',
				'label' => __( 'View privacy FAQ', 'safetunnels' ),
			),
			array(
				'icon'  => 'ti-plug-connected',
				'title' => __( 'Connection Issues', 'safetunnels' ),
				'desc'  => __( 'The app won\'t connect, keeps dropping, or feels slower than expected.', 'safetunnels' ),
				'url'   => safetunnels_page_url( 'faq' ) . '#connection',
				'label' => __( 'View connection FAQ', 'safetunnels' ),
			),
			array(
				'icon'  => 'ti-shield-question',
				'title' => __( 'Privacy Requests', 'safetunnels' ),
				'desc'  => __( 'Ask what data is stored about your installation, or request it be deleted.', 'safetunnels' ),
				'url'   => safetunnels_page_url( 'privacy' ) . '#s13',
				'label' => __( 'View privacy policy', 'safetunnels' ),
			),
		)
	);
}

/**
 * Contact reasons — each opens a pre-addressed email.
 *
 * @return array<int, array<string, string>>
 */
function safetunnels_contact_reasons() {
	return apply_filters(
		'safetunnels_contact_reasons',
		array(
			array(
				'icon'    => 'ti-headset',
				'title'   => __( 'General Support', 'safetunnels' ),
				'desc'    => __( 'App issues, connection problems, or anything else', 'safetunnels' ),
				'subject' => 'SafeTunnels Support Request',
			),
			array(
				'icon'    => 'ti-shield-lock',
				'title'   => __( 'Privacy Request', 'safetunnels' ),
				'desc'    => __( 'Ask what data we hold, or request deletion', 'safetunnels' ),
				'subject' => 'Privacy Request',
			),
			array(
				'icon'    => 'ti-device-mobile',
				'title'   => __( 'Account Recovery', 'safetunnels' ),
				'desc'    => __( 'Recover access after replacing your device', 'safetunnels' ),
				'subject' => 'Account Recovery Request',
			),
			array(
				'icon'    => 'ti-receipt',
				'title'   => __( 'Billing Question', 'safetunnels' ),
				'desc'    => __( 'Subscription, charges, or refund questions', 'safetunnels' ),
				'subject' => 'Billing Question',
			),
		)
	);
}
