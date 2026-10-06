<?php
/**
 * The page registry — every page the theme ships with, created automatically
 * the first time the theme is activated and resolvable by key from anywhere.
 *
 * Auto-creation matters for SEO: the internal link graph (footer, nav,
 * breadcrumbs, schema, sitemap) is only complete if these pages exist, so the
 * theme refuses to depend on the administrator building them by hand.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

const SAFETUNNELS_PAGES_OPTION = 'safetunnels_pages';

/**
 * Definition of every page the theme manages.
 *
 * @return array<string, array<string, mixed>>
 */
function safetunnels_page_registry() {
	$app = safetunnels_opt( 'app_name' );

	$pages = array(
		'home'           => array(
			'title'    => $app,
			'slug'     => 'home',
			'template' => 'page-templates/template-home.php',
			'front'    => true,
			'nav'      => false,
			'desc'     => 'Access every Iranian app from anywhere in the world. SafeTunnels routes only Iranian traffic through an AES-256 encrypted WireGuard tunnel — banking, shopping, streaming — free to start on Android.',
		),
		'download'       => array(
			'title'    => 'Download',
			'slug'     => 'download',
			'template' => 'page-templates/template-download.php',
			'nav'      => true,
			'desc'     => 'Download SafeTunnels for Android — direct APK or Google Play. Android 8.0 and later, free plan with unlimited bandwidth, no credit card required.',
		),
		'pricing'        => array(
			'title'    => 'Pricing',
			'slug'     => 'pricing',
			'template' => 'page-templates/template-pricing.php',
			'nav'      => true,
			'desc'     => 'SafeTunnels pricing — a permanently free plan, Premium from $0.99 per week, and a one-time lifetime ad-free unlock. Pay by card on the web or through Google Play. Cancel anytime.',
		),
		'apps'           => array(
			'title'    => 'Iranian Apps',
			'slug'     => 'iranian-apps',
			'template' => 'page-templates/template-apps.php',
			'nav'      => true,
			'desc'     => 'The full catalogue of Iranian apps SafeTunnels supports — banking, neobanks, payments, brokerages, insurance, marketplaces, operators and services — with direct download links.',
		),
		'faq'            => array(
			'title'    => 'FAQ',
			'slug'     => 'faq',
			'template' => 'page-templates/template-faq.php',
			'nav'      => true,
			'desc'     => 'Answers to common SafeTunnels questions covering general use, privacy and data, accounts and devices, subscriptions and billing, and connection troubleshooting.',
		),
		'support'        => array(
			'title'    => 'Support',
			'slug'     => 'support',
			'template' => 'page-templates/template-support.php',
			'nav'      => true,
			'desc'     => 'Get help with SafeTunnels — subscription issues, restoring purchases, changing devices, traffic questions, and connection troubleshooting.',
		),
		'contact'        => array(
			'title'    => 'Contact Us',
			'slug'     => 'contact',
			'template' => 'page-templates/template-contact.php',
			'nav'      => true,
			'desc'     => 'Get in touch with the SafeTunnels team for support, privacy requests, account recovery, or billing questions. We answer every message within 48 hours.',
		),
		'privacy'        => array(
			'title'    => 'Privacy Policy',
			'slug'     => 'privacy',
			'template' => 'page-templates/template-privacy.php',
			'nav'      => false,
			'desc'     => 'How SafeTunnels collects, uses, and protects information — including what we explicitly do not collect, our use of an anonymous device identifier, and our Google AdMob advertising disclosure.',
		),
		'terms'          => array(
			'title'    => 'Terms of Service',
			'slug'     => 'terms',
			'template' => 'page-templates/template-terms.php',
			'nav'      => false,
			'desc'     => 'The terms that govern your use of SafeTunnels, including subscription plans, billing, refunds, acceptable use, and limitation of liability.',
		),
		'refund'         => array(
			'title'    => 'Refund Policy',
			'slug'     => 'refund-policy',
			'template' => 'page-templates/template-refund.php',
			'nav'      => false,
			'desc'     => 'When a SafeTunnels subscription can be refunded, how to request one for a web card payment or a Google Play purchase, and how long it takes.',
		),
		'cookies'        => array(
			'title'    => 'Cookie Policy',
			'slug'     => 'cookie-policy',
			'template' => 'page-templates/template-cookies.php',
			'nav'      => false,
			'desc'     => 'Which cookies and local storage keys the SafeTunnels website uses, what each one does, and how to clear them.',
		),
		'payment_result' => array(
			'title'    => 'Payment Result',
			'slug'     => 'payment-result',
			'template' => 'page-templates/template-payment-result.php',
			'nav'      => false,
			'noindex'  => true,
			'desc'     => 'Your SafeTunnels checkout result.',
		),
	);

	/**
	 * Filters the theme's page registry.
	 *
	 * @param array $pages Page definitions keyed by registry key.
	 */
	return apply_filters( 'safetunnels_page_registry', $pages );
}

/**
 * Resolves a registry key to a page ID.
 *
 * @param string $key Registry key.
 * @return int Page ID, or 0 when the page does not exist.
 */
function safetunnels_page_id( $key ) {
	$map = get_option( SAFETUNNELS_PAGES_OPTION, array() );

	if ( isset( $map[ $key ] ) && get_post_status( $map[ $key ] ) === 'publish' ) {
		return (int) $map[ $key ];
	}

	$registry = safetunnels_page_registry();
	if ( ! isset( $registry[ $key ] ) ) {
		return 0;
	}

	$page = get_page_by_path( $registry[ $key ]['slug'] );

	return $page ? (int) $page->ID : 0;
}

/**
 * Public URL of a registry page, with a slug-based fallback so links never
 * break even before the pages are generated.
 *
 * @param string $key Registry key.
 * @return string
 */
function safetunnels_page_url( $key ) {
	$id = safetunnels_page_id( $key );

	if ( $id ) {
		return get_permalink( $id );
	}

	$registry = safetunnels_page_registry();
	if ( ! isset( $registry[ $key ] ) ) {
		return home_url( '/' );
	}

	if ( ! empty( $registry[ $key ]['front'] ) ) {
		return home_url( '/' );
	}

	return home_url( '/' . $registry[ $key ]['slug'] . '/' );
}

/**
 * Registry key of the page currently being viewed, if any.
 *
 * @return string
 */
function safetunnels_current_page_key() {
	if ( ! is_page() ) {
		return '';
	}

	$id = get_queried_object_id();

	foreach ( array_keys( safetunnels_page_registry() ) as $key ) {
		if ( safetunnels_page_id( $key ) === (int) $id ) {
			return $key;
		}
	}

	return '';
}

/**
 * Creates any missing registry page, assigns its template, and points the
 * front page at the landing template. Runs once on theme activation.
 *
 * @return void
 */
function safetunnels_install_pages() {
	$registry = safetunnels_page_registry();
	$map      = get_option( SAFETUNNELS_PAGES_OPTION, array() );

	foreach ( $registry as $key => $page ) {
		$existing = isset( $map[ $key ] ) ? get_post( $map[ $key ] ) : null;

		if ( ! $existing ) {
			$found = get_page_by_path( $page['slug'] );
			if ( $found ) {
				$existing = $found;
			}
		}

		if ( $existing && 'page' === $existing->post_type ) {
			$id = (int) $existing->ID;
			if ( 'publish' !== $existing->post_status ) {
				wp_update_post(
					array(
						'ID'          => $id,
						'post_status' => 'publish',
					)
				);
			}
		} else {
			$id = wp_insert_post(
				array(
					'post_title'   => $page['title'],
					'post_name'    => $page['slug'],
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_content' => '',
					'menu_order'   => count( $map ),
				)
			);
		}

		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}

		update_post_meta( $id, '_wp_page_template', $page['template'] );
		update_post_meta( $id, '_safetunnels_page_key', $key );

		if ( ! get_post_meta( $id, '_safetunnels_description', true ) && ! empty( $page['desc'] ) ) {
			update_post_meta( $id, '_safetunnels_description', $page['desc'] );
		}

		if ( ! empty( $page['noindex'] ) ) {
			update_post_meta( $id, '_safetunnels_noindex', '1' );
		}

		$map[ $key ] = (int) $id;
	}

	update_option( SAFETUNNELS_PAGES_OPTION, $map );

	if ( isset( $map['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $map['home'] );
	}

	// A posts page keeps the blog off the landing page and gives articles a
	// crawlable archive of their own.
	$blog = get_page_by_path( 'blog' );
	if ( ! $blog ) {
		$blog_id = wp_insert_post(
			array(
				'post_title'  => __( 'Blog', 'safetunnels' ),
				'post_name'   => 'blog',
				'post_status' => 'publish',
				'post_type'   => 'page',
			)
		);
	} else {
		$blog_id = $blog->ID;
	}

	if ( $blog_id && ! is_wp_error( $blog_id ) ) {
		update_option( 'page_for_posts', (int) $blog_id );
	}

	if ( isset( $map['privacy'] ) ) {
		update_option( 'wp_page_for_privacy_policy', $map['privacy'] );
	}
}

/**
 * Builds the primary menu from the registry and assigns it, so a fresh install
 * has a working navigation without touching Appearance → Menus.
 *
 * @return void
 */
function safetunnels_install_menu() {
	$locations = get_theme_mod( 'nav_menu_locations', array() );

	if ( ! empty( $locations['primary'] ) && wp_get_nav_menu_object( $locations['primary'] ) ) {
		return;
	}

	$menu_name = __( 'SafeTunnels Primary', 'safetunnels' );
	$menu      = wp_get_nav_menu_object( $menu_name );
	$menu_id   = $menu ? (int) $menu->term_id : (int) wp_create_nav_menu( $menu_name );

	if ( ! $menu_id || is_wp_error( $menu_id ) ) {
		return;
	}

	if ( ! $menu ) {
		foreach ( safetunnels_page_registry() as $key => $page ) {
			if ( empty( $page['nav'] ) ) {
				continue;
			}

			$id = safetunnels_page_id( $key );
			if ( ! $id ) {
				continue;
			}

			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-object-id' => $id,
					'menu-item-object'    => 'page',
					'menu-item-type'      => 'post_type',
					'menu-item-status'    => 'publish',
					'menu-item-title'     => $page['title'],
				)
			);
		}
	}

	$locations['primary'] = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * One-shot setup on theme activation.
 *
 * @return void
 */
function safetunnels_after_switch_theme() {
	safetunnels_install_pages();
	safetunnels_install_menu();

	// Pretty permalinks make every URL in the registry (and the sitemap)
	// resolve; flushing here saves an otherwise silent 404 on first load.
	if ( '' === get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
	}

	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'safetunnels_after_switch_theme' );

/**
 * Adds a "Regenerate SafeTunnels pages" tool under Appearance, for sites that
 * were set up before a new page joined the registry.
 *
 * @return void
 */
function safetunnels_pages_admin_menu() {
	add_theme_page(
		__( 'SafeTunnels Pages', 'safetunnels' ),
		__( 'SafeTunnels Pages', 'safetunnels' ),
		'edit_theme_options',
		'safetunnels-pages',
		'safetunnels_pages_admin_screen'
	);
}
add_action( 'admin_menu', 'safetunnels_pages_admin_menu' );

/**
 * Renders the page-repair screen.
 *
 * @return void
 */
function safetunnels_pages_admin_screen() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	if ( isset( $_POST['safetunnels_regenerate'] ) && check_admin_referer( 'safetunnels_regenerate_pages' ) ) {
		safetunnels_install_pages();
		safetunnels_install_menu();
		flush_rewrite_rules();
		echo '<div class="notice notice-success"><p>' . esc_html__( 'SafeTunnels pages regenerated.', 'safetunnels' ) . '</p></div>';
	}

	echo '<div class="wrap"><h1>' . esc_html__( 'SafeTunnels Pages', 'safetunnels' ) . '</h1>';
	echo '<p>' . esc_html__( 'These pages power the theme\'s navigation, breadcrumbs, schema and sitemap. Regenerating re-creates any that were deleted and re-assigns the correct template — existing content is never overwritten.', 'safetunnels' ) . '</p>';
	echo '<table class="widefat striped" style="max-width:900px"><thead><tr><th>' . esc_html__( 'Page', 'safetunnels' ) . '</th><th>' . esc_html__( 'URL', 'safetunnels' ) . '</th><th>' . esc_html__( 'Status', 'safetunnels' ) . '</th></tr></thead><tbody>';

	foreach ( safetunnels_page_registry() as $key => $page ) {
		$id = safetunnels_page_id( $key );
		echo '<tr><td><strong>' . esc_html( $page['title'] ) . '</strong></td><td><code>' . esc_html( safetunnels_page_url( $key ) ) . '</code></td><td>';
		if ( $id ) {
			echo '<span style="color:#008a20">' . esc_html__( 'OK', 'safetunnels' ) . '</span> — <a href="' . esc_url( get_edit_post_link( $id ) ) . '">' . esc_html__( 'Edit', 'safetunnels' ) . '</a>';
		} else {
			echo '<span style="color:#d63638">' . esc_html__( 'Missing', 'safetunnels' ) . '</span>';
		}
		echo '</td></tr>';
	}

	echo '</tbody></table><form method="post" style="margin-top:18px">';
	wp_nonce_field( 'safetunnels_regenerate_pages' );
	echo '<button type="submit" name="safetunnels_regenerate" class="button button-primary">' . esc_html__( 'Regenerate pages &amp; menu', 'safetunnels' ) . '</button>';
	echo '</form></div>';
}
