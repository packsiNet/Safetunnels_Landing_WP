<?php
/**
 * The full Help Center FAQ, ported from `src/features/legal/data/faq.data.ts`.
 *
 * The same array feeds the /faq page, the landing page accordion, and the
 * FAQPage JSON-LD emitted by inc/schema.php — so the rich result Google shows
 * can never drift from what a visitor actually reads.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

/**
 * FAQ grouped by category.
 *
 * @return array<int, array{key:string, label:string, items:array<int, array{q:string, a:string}>}>
 */
function safetunnels_faq_categories() {
	$categories = array(
		array(
			'key'   => 'general',
			'label' => 'General',
			'items' => array(
				array(
					'q' => 'What is SafeTunnels?',
					'a' => 'SafeTunnels is an Android application that establishes an encrypted tunnel between your device and SafeTunnels servers, giving you reliable access to supported apps and services from anywhere in the world.',
				),
				array(
					'q' => 'How does SafeTunnels work?',
					'a' => 'When you tap Connect, the app opens an encrypted tunnel to a SafeTunnels server. Traffic to supported destinations is routed through that tunnel; everything else can continue to use your normal connection, depending on your plan.',
				),
				array(
					'q' => 'Do I need to create an account?',
					'a' => 'No. SafeTunnels does not require you to register with a name, email address, or phone number. On first launch, the app automatically creates an internal, randomly generated identifier that represents your installation.',
				),
				array(
					'q' => 'Why does the app mention an "internal email" if I never entered one?',
					'a' => 'To manage subscriptions on the backend, SafeTunnels automatically generates a system-internal email address tied to your anonymous account identifier. You never type or see a real email during setup, and this internal address is not used to contact you personally.',
				),
				array(
					'q' => 'Is SafeTunnels free to use?',
					'a' => 'Yes, SafeTunnels offers a free plan with unlimited bandwidth for supported apps and services, supported by ads. A paid Premium plan removes ads and unlocks additional apps, streaming, and general browser traffic.',
				),
				array(
					'q' => 'What platforms is SafeTunnels available on?',
					'a' => 'SafeTunnels is currently available for Android 8.0 and later, distributed as a direct APK download and through Google Play.',
				),
			),
		),
		array(
			'key'   => 'privacy',
			'label' => 'Privacy & Data',
			'items' => array(
				array(
					'q' => 'What information does SafeTunnels collect?',
					'a' => 'SafeTunnels collects an anonymous device identifier, your subscription status, aggregate traffic usage (bandwidth consumed), and basic in-app preferences. It does not collect your name, phone number, contacts, messages, photos, files, or precise location.',
				),
				array(
					'q' => 'Does SafeTunnels track my browsing history?',
					'a' => 'No. SafeTunnels does not log, inspect, or store the websites you visit or the content of your traffic. Traffic usage is recorded only as an aggregate data amount, used solely to enforce plan limits and manage subscriptions.',
				),
				array(
					'q' => 'Does SafeTunnels sell my data?',
					'a' => 'No. SafeTunnels does not sell user information to anyone, and does not share your data with advertisers.',
				),
				array(
					'q' => 'Why do I see ads in the app?',
					'a' => 'The free plan is supported by advertising served through Google AdMob. AdMob may collect certain information according to Google’s own Privacy Policy. Upgrading to Premium removes ads entirely.',
				),
				array(
					'q' => 'Does SafeTunnels use Firebase Analytics or Crashlytics?',
					'a' => 'No. SafeTunnels does not integrate Firebase Analytics or Crashlytics. The only third-party services used are Google Play Services and Google AdMob.',
				),
				array(
					'q' => 'Can I request that my data be deleted?',
					'a' => 'Yes. Because your account is tied only to an anonymous device identifier, you can contact support and request that your associated data be removed. See our Privacy Policy and Contact page for details.',
				),
			),
		),
		array(
			'key'   => 'account',
			'label' => 'Account & Devices',
			'items' => array(
				array(
					'q' => 'How do I restore my Premium subscription?',
					'a' => 'Open SafeTunnels, go to Settings → Restore Purchases, and confirm. The app matches your purchase to your device identifier through Google Play and reactivates Premium automatically.',
				),
				array(
					'q' => 'I changed my phone. How do I keep my subscription?',
					'a' => 'Because SafeTunnels has no traditional login, subscription restoration is normally tied to the same Google Play account. If Restore Purchases does not recover your Premium status on the new device, contact support with your purchase receipt or order ID and we will help recover your account.',
				),
				array(
					'q' => 'I uninstalled and reinstalled the app — did I lose my subscription?',
					'a' => 'Reinstalling on the same device usually restores your subscription automatically through Google Play. If it does not, use Restore Purchases from the Settings menu, or contact support if the issue persists.',
				),
				array(
					'q' => 'Can I use SafeTunnels on multiple devices?',
					'a' => 'Each SafeTunnels installation is tied to its own anonymous device identifier. Premium entitlement follows your Google Play account, so it can generally be restored on devices signed in with the same Play account, subject to Google Play’s purchase rules.',
				),
			),
		),
		array(
			'key'   => 'billing',
			'label' => 'Subscriptions & Billing',
			'items' => array(
				array(
					'q' => 'What is included in the Free plan?',
					'a' => 'The Free plan includes access to supported banking and public-service apps with unlimited bandwidth, at no cost and with no credit card required. It does not include specialized apps, streaming apps, or general browser traffic, and it displays ads.',
				),
				array(
					'q' => 'What does Premium add?',
					'a' => 'Premium unlocks the full catalog of supported apps, streaming and VOD apps, general browser traffic, unlimited bandwidth, priority relay nodes, and removes ads entirely.',
				),
				array(
					'q' => 'How do I cancel my subscription?',
					'a' => 'Subscriptions are billed and managed through Google Play. You can cancel anytime from Google Play → Subscriptions, or via the link in the app’s Settings screen. Your Premium access continues until the end of the paid billing period.',
				),
				array(
					'q' => 'Do you offer refunds?',
					'a' => 'Refund eligibility follows Google Play’s billing policies, since all payments are processed by Google. Contact support if you believe you were charged in error and we will help you through the process.',
				),
				array(
					'q' => 'How much free traffic (bandwidth) do I get?',
					'a' => 'The Free plan includes unlimited bandwidth for supported free-tier apps. Traffic usage figures shown in the app are for your own visibility and for plan enforcement — they are never used to build a browsing profile.',
				),
			),
		),
		array(
			'key'   => 'connection',
			'label' => 'Connection & Troubleshooting',
			'items' => array(
				array(
					'q' => 'Why won’t SafeTunnels connect?',
					'a' => 'Check your internet connection first, then confirm the app has the requested VPN permission. If the issue continues, try toggling airplane mode, restarting the app, or reinstalling. If it still fails, contact support with your device model and Android version.',
				),
				array(
					'q' => 'Why is my connection slow?',
					'a' => 'Speed can vary with your local network conditions and overall server load. Premium accounts are routed through priority relay nodes, which typically improves speed and stability during peak hours.',
				),
				array(
					'q' => 'Does SafeTunnels inspect or modify my traffic content?',
					'a' => 'No. SafeTunnels forwards your encrypted traffic between the app and SafeTunnels servers without inspecting, logging, or modifying its content.',
				),
				array(
					'q' => 'What Android permissions does SafeTunnels request, and why?',
					'a' => 'SafeTunnels requests the Android VPN (VpnService) permission, which is required to establish the encrypted tunnel. The app does not request access to your contacts, camera, microphone, SMS, or precise location.',
				),
			),
		),
	);

	/**
	 * Filters the Help Center FAQ.
	 *
	 * @param array $categories FAQ categories.
	 */
	return apply_filters( 'safetunnels_faq_categories', $categories );
}

/**
 * Every FAQ entry, flattened.
 *
 * @return array<int, array{q:string, a:string}>
 */
function safetunnels_faq_flat() {
	$items = array();
	foreach ( safetunnels_faq_categories() as $category ) {
		foreach ( $category['items'] as $item ) {
			$items[] = $item;
		}
	}

	return $items;
}

/**
 * The six-question accordion on the landing page — ported from FAQ_ITEMS in
 * TunnelNetworkPage.tsx.
 *
 * @return array<int, array{q:string, a:string}>
 */
function safetunnels_landing_faq() {
	$items = array(
		array(
			'q' => 'What VPN protocol does Tunnel Network use?',
			'a' => 'Tunnel Network uses WireGuard as its primary protocol — a modern, widely reviewed protocol that\'s typically faster than OpenVPN or IKEv2. We also support an alternate connection mode designed to stay stable on networks with strict traffic filtering.',
		),
		array(
			'q' => 'How do I pay?',
			'a' => 'You can pay right here on the website with any international debit or credit card through our secure checkout, or through Google Play using the payment method already on file with your Google account. We never handle or store your card details directly.',
		),
		array(
			'q' => 'How is my traffic data handled?',
			'a' => 'We do not inspect, monitor, or store the content of your traffic, and we don\'t log the websites or apps you visit. We retain a limited amount of technical data — such as an anonymous device identifier and aggregate bandwidth usage — solely to manage your plan and subscription. See our Privacy Policy for full details.',
		),
		array(
			'q' => 'What is the kill switch and why do I need it?',
			'a' => 'The kill switch is a safety net — if your tunnel connection drops unexpectedly, it immediately blocks all internet traffic until the tunnel is restored. This prevents any accidental data exposure during reconnection.',
		),
		array(
			'q' => 'What if I want to cancel my subscription?',
			'a' => 'Cancel anytime through Google Play → Subscriptions, or via the link in the app\'s Settings screen — no forms required. Your Premium access continues until the end of the current billing period, and you won\'t be charged again after that.',
		),
		array(
			'q' => 'Is there a free trial?',
			'a' => 'There\'s no time-limited trial — the Free plan is available permanently at no cost, with access to supported banking and public-service apps. Upgrade to Premium anytime for the full app catalog and an ad-free experience.',
		),
	);

	/** This filter is documented in inc/data/faq.php */
	return apply_filters( 'safetunnels_landing_faq', $items );
}
