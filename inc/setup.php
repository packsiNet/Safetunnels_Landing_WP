<?php
/**
 * Theme supports, menus and editor configuration.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers everything WordPress needs to know about the theme.
 *
 * @return void
 */
function safetunnels_setup() {
	load_theme_textdomain( 'safetunnels', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );

	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 60,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'safetunnels' ),
			'footer'  => __( 'Footer Menu', 'safetunnels' ),
			'legal'   => __( 'Legal Menu', 'safetunnels' ),
		)
	);

	// 1200x630 is the size Open Graph consumers crop to; generating it up
	// front means social previews are never a re-scaled full-size image.
	add_image_size( 'safetunnels-og', 1200, 630, true );
	add_image_size( 'safetunnels-card', 720, 405, true );

	set_post_thumbnail_size( 960, 540, true );
}
add_action( 'after_setup_theme', 'safetunnels_setup' );

/**
 * Content width used by oEmbeds and wide images.
 *
 * @return void
 */
function safetunnels_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'safetunnels_content_width', 1100 );
}
add_action( 'after_setup_theme', 'safetunnels_content_width', 0 );

/**
 * Sidebar for the blog side of the site.
 *
 * @return void
 */
function safetunnels_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Blog Sidebar', 'safetunnels' ),
			'id'            => 'blog-sidebar',
			'description'   => __( 'Shown next to posts and archives.', 'safetunnels' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s glass">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'safetunnels_widgets_init' );

/**
 * Selects the SafeTunnels landing or document shell.
 * Drives both body classes and conditional asset loading.
 *
 * @return string `tn` or `lg`.
 */
function safetunnels_shell() {
	$landing = array( 'home', 'pricing', 'download', 'apps' );
	$key     = safetunnels_current_page_key();

	$shell = in_array( $key, $landing, true ) ? 'tn' : 'lg';

	/**
	 * Filters the visual shell for the current view.
	 *
	 * @param string $shell Either `tn` or `lg`.
	 */
	return apply_filters( 'safetunnels_shell', $shell );
}

/**
 * Adds shell and page-key classes to <body>.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function safetunnels_body_class( $classes ) {
	$classes[] = 'st-shell-' . safetunnels_shell();
	if ( in_array( safetunnels_shell(), array( 'tn', 'lg' ), true ) ) {
		$classes[] = 'st-product';
	}

	$key = safetunnels_current_page_key();
	if ( $key ) {
		$classes[] = 'st-page-' . str_replace( '_', '-', $key );
	}

	return $classes;
}
add_filter( 'body_class', 'safetunnels_body_class' );
