<?php
/**
 * Pricing plans and the RevenueCat hosted checkout wiring.
 *
 * Ported 1:1 from `src/features/tunnels/constants/premiumPlans.ts`. Every
 * Premium period unlocks the same feature set — only price and commitment
 * change. Prices, package identifiers and Purchase Links are all editable in
 * Customizer → SafeTunnels → Pricing & Checkout so the site can follow the
 * RevenueCat dashboard without a code change.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

/**
 * Hard-coded fallbacks for the Premium billing periods.
 *
 * @return array<string, array<string, mixed>>
 */
function safetunnels_plan_defaults() {
	return array(
		'weekly'    => array(
			'label'      => 'Weekly',
			'price'      => '$0.99',
			'per'        => '/ wk',
			'cadence'    => 'Billed weekly',
			'weekly_eq'  => '',
			'badge'      => '',
			'disabled'   => false,
			'package_id' => '$rc_weekly',
			'link'       => 'https://pay.rev.cat/vqxjhcjuhhbhuczs',
			'amount'     => '0.99',
			'duration'   => 'P1W',
		),
		'monthly'   => array(
			'label'      => 'Monthly',
			'price'      => '$3.99',
			'per'        => '/ mo',
			'cadence'    => 'Billed monthly',
			'weekly_eq'  => '≈ $0.92 / week',
			'badge'      => 'Save 7%',
			'disabled'   => false,
			'package_id' => '$rc_monthly',
			'link'       => 'https://pay.rev.cat/xoxazdqcjftgfgar',
			'amount'     => '3.99',
			'duration'   => 'P1M',
		),
		'quarterly' => array(
			'label'      => 'Quarterly',
			'price'      => '$9.99',
			'per'        => '/ 3 mo',
			'cadence'    => 'Billed every 3 months',
			'weekly_eq'  => '≈ $0.77 / week',
			'badge'      => 'Best Value · Save 22%',
			'disabled'   => false,
			'package_id' => '$rc_three_month',
			'link'       => 'https://pay.rev.cat/idwdswwixvfolsnv',
			'amount'     => '9.99',
			'duration'   => 'P3M',
		),
		'yearly'    => array(
			'label'      => 'Yearly',
			'price'      => '$29.99',
			'per'        => '/ yr',
			'cadence'    => 'Billed yearly',
			'weekly_eq'  => '≈ $0.58 / week',
			'badge'      => 'Save 42%',
			'disabled'   => true,
			'package_id' => '$rc_annual',
			'link'       => 'https://pay.rev.cat/nplgdapzpnlekglz',
			'amount'     => '29.99',
			'duration'   => 'P1Y',
		),
	);
}

/**
 * Premium periods with Customizer overrides applied.
 *
 * @return array<string, array<string, mixed>>
 */
function safetunnels_premium_periods() {
	$periods = safetunnels_plan_defaults();

	foreach ( $periods as $key => $period ) {
		foreach ( array( 'price', 'package_id', 'link', 'badge' ) as $field ) {
			$mod = get_theme_mod( "safetunnels_plan_{$key}_{$field}", '' );
			if ( '' !== $mod && null !== $mod ) {
				$periods[ $key ][ $field ] = $mod;
			}
		}
		$periods[ $key ]['disabled'] = (bool) get_theme_mod( "safetunnels_plan_{$key}_disabled", $period['disabled'] );
	}

	/**
	 * Filters the Premium billing periods.
	 *
	 * @param array $periods Period definitions keyed by period slug.
	 */
	return apply_filters( 'safetunnels_premium_periods', $periods );
}

/**
 * The default period pre-selected in the pricing toggle.
 *
 * @return string
 */
function safetunnels_default_period() {
	$default = get_theme_mod( 'safetunnels_plan_default', 'quarterly' );
	$periods = safetunnels_premium_periods();

	if ( ! isset( $periods[ $default ] ) || ! empty( $periods[ $default ]['disabled'] ) ) {
		foreach ( $periods as $key => $period ) {
			if ( empty( $period['disabled'] ) ) {
				return $key;
			}
		}
	}

	return $default;
}

/**
 * The lifetime "No Ads" one-time purchase.
 *
 * @return array<string, string>
 */
function safetunnels_noads_plan() {
	$plan = array(
		'price'      => get_theme_mod( 'safetunnels_noads_price', '$9.99' ),
		'amount'     => '9.99',
		'package_id' => get_theme_mod( 'safetunnels_noads_package_id', '$rc_lifetime' ),
		'link'       => get_theme_mod( 'safetunnels_noads_link', 'https://pay.rev.cat/jqqpmijwfwbsjznm' ),
	);

	/** This filter is documented in inc/data/plans.php */
	return apply_filters( 'safetunnels_noads_plan', $plan );
}

/**
 * Feature bullets for the Free plan. `false` renders as a struck-through row.
 *
 * @return array<int, array{0:string,1:bool}>
 */
function safetunnels_free_features() {
	return array(
		array( 'All banking apps', true ),
		array( 'All public service apps', true ),
		array( 'Unlimited bandwidth', true ),
		array( 'No specialized apps', false ),
		array( 'Includes ads', false ),
		array( 'No browser traffic', false ),
		array( 'No streaming apps', false ),
	);
}

/**
 * Feature bullets shared by every Premium period.
 *
 * @return string[]
 */
function safetunnels_premium_features() {
	return array(
		'All Iranian apps',
		'Specialized apps included',
		'Streaming &amp; VOD apps',
		'Browser traffic included',
		'Unlimited bandwidth',
		'No ads',
		'Priority relay nodes',
	);
}

/**
 * Builds the RevenueCat hosted checkout URL for a purchase target.
 *
 * The account key (the app's User.Id GUID) becomes the `app_user_id` path
 * segment; `/checkout` + `package_id` jumps straight to the Stripe checkout,
 * skipping RevenueCat's intermediate package-confirmation screen — identical
 * to `PurchaseModal.handleConfirmPay()` in the React app.
 *
 * @param string $kind   Either `premium` or `noads`.
 * @param string $period Billing period slug (premium only).
 * @param string $key    Normalised (lowercase) account key.
 * @return string
 */
function safetunnels_checkout_url( $kind, $period, $key ) {
	if ( 'noads' === $kind ) {
		$plan       = safetunnels_noads_plan();
		$link_base  = $plan['link'];
		$package_id = $plan['package_id'];
	} else {
		$periods = safetunnels_premium_periods();
		if ( ! isset( $periods[ $period ] ) ) {
			return '';
		}
		$link_base  = $periods[ $period ]['link'];
		$package_id = $periods[ $period ]['package_id'];
	}

	return untrailingslashit( $link_base ) . '/' . rawurlencode( $key ) . '/checkout?package_id=' . rawurlencode( $package_id );
}
