<?php
/**
 * SafeTunnels theme bootstrap.
 *
 * Load order matters: the data layer defines `safetunnels_opt()` and the page
 * registry, which every other module reads at include time.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

$safetunnels_modules = array(
	// Data layer.
	'inc/data/config.php',
	'inc/data/plans.php',
	'inc/data/apps.php',
	'inc/data/faq.php',
	'inc/pages.php',
	'inc/data/content.php',

	// Behaviour.
	'inc/setup.php',
	'inc/branding.php',
	'inc/enqueue.php',
	'inc/options.php',
	'inc/rest.php',
	'inc/deployment.php',
	'inc/seo.php',
	'inc/schema.php',
	'inc/template-tags.php',
);

foreach ( $safetunnels_modules as $safetunnels_module ) {
	$safetunnels_path = get_theme_file_path( $safetunnels_module );

	if ( file_exists( $safetunnels_path ) ) {
		require_once $safetunnels_path;
	}
}

unset( $safetunnels_modules, $safetunnels_module, $safetunnels_path );

/**
 * Escapes the parts of a marketing string that may legitimately contain
 * inline markup (a `<br>`, a `<span>`), while stripping everything else.
 *
 * @param string $html Raw string.
 * @return string
 */
function safetunnels_kses( $html ) {
	return wp_kses(
		$html,
		array(
			'br'     => array(),
			'b'      => array(),
			'strong' => array(),
			'em'     => array(),
			'span'   => array( 'class' => array() ),
			'sub'    => array(),
			'a'      => array(
				'href'   => array(),
				'class'  => array(),
				'rel'    => array(),
				'target' => array(),
			),
		)
	);
}

/**
 * Excerpt length tuned for the blog cards.
 *
 * @return int
 */
function safetunnels_excerpt_length() {
	return 28;
}
add_filter( 'excerpt_length', 'safetunnels_excerpt_length' );

/**
 * Excerpt ellipsis.
 *
 * @return string
 */
function safetunnels_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'safetunnels_excerpt_more' );

/**
 * Ships the payment-result page with `noindex` even if the meta was cleared —
 * a checkout receipt has no business in an index.
 *
 * @param string[] $directives Robots directives.
 * @return string[]
 */
function safetunnels_force_noindex_payment( $directives ) {
	if ( 'payment_result' === safetunnels_current_page_key() ) {
		$directives[0] = 'noindex';
	}

	return $directives;
}
add_filter( 'safetunnels_robots_directives', 'safetunnels_force_noindex_payment' );

/**
 * Adds `loading="lazy"` and `decoding="async"` to content images that lack it.
 *
 * @param string $content Post content.
 * @return string
 */
function safetunnels_lazy_content_images( $content ) {
	if ( is_admin() || is_feed() ) {
		return $content;
	}

	return str_replace( '<img ', '<img decoding="async" ', $content );
}
add_filter( 'the_content', 'safetunnels_lazy_content_images', 20 );
