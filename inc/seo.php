<?php
/**
 * On-page SEO: titles, descriptions, canonicals, robots directives, Open
 * Graph, Twitter cards, pagination rels, sitemap tuning and robots.txt.
 *
 * Everything is self-contained — the theme needs no SEO plugin — but it also
 * steps aside automatically when Yoast, Rank Math or SEOPress is active, so a
 * site that later adds one does not end up with duplicate tags.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

/**
 * Language of the Persian search guides.
 *
 * @return string
 */
function safetunnels_content_language() {
	return in_array( safetunnels_current_page_key(), array( 'iranian_access', 'iran_tunnel' ), true ) ? 'fa-IR' : get_bloginfo( 'language' );
}

/**
 * Whether a dedicated SEO plugin is handling meta output.
 *
 * @return bool
 */
function safetunnels_seo_plugin_active() {
	return (
		defined( 'WPSEO_VERSION' )
		|| defined( 'RANK_MATH_VERSION' )
		|| defined( 'SEOPRESS_VERSION' )
		|| class_exists( 'All_in_One_SEO_Pack' )
	);
}

/**
 * Title separator — matches the React app's `Title · SafeTunnels`.
 *
 * @return string
 */
function safetunnels_title_separator() {
	return '·';
}
add_filter( 'document_title_separator', 'safetunnels_title_separator' );

/**
 * Applies a per-page SEO title override and keeps the site name suffix
 * consistent across every view.
 *
 * @param array $parts Title parts.
 * @return array
 */
function safetunnels_document_title_parts( $parts ) {
	if ( safetunnels_seo_plugin_active() ) {
		return $parts;
	}

	if ( is_singular() ) {
		$custom = get_post_meta( get_queried_object_id(), '_safetunnels_title', true );
		if ( $custom ) {
			$parts['title'] = $custom;
		}
	}

	if ( 'home' === safetunnels_current_page_key() ) {
		$parts['title']   = __( 'Iran VPN & Iranian IP for Apps Abroad', 'safetunnels' ) . ' | ' . safetunnels_opt( 'app_name' );
		$parts['tagline'] = '';
		unset( $parts['site'] );
	}

	return $parts;
}
add_filter( 'document_title_parts', 'safetunnels_document_title_parts' );

/**
 * Resolves the meta description for the current view.
 *
 * @return string
 */
function safetunnels_meta_description() {
	$description = '';

	if ( is_singular() ) {
		$id     = get_queried_object_id();
		$custom = get_post_meta( $id, '_safetunnels_description', true );

		if ( $custom ) {
			$description = $custom;
		} elseif ( has_excerpt( $id ) ) {
			$description = get_the_excerpt( $id );
		} else {
			$description = wp_strip_all_tags( strip_shortcodes( (string) get_post_field( 'post_content', $id ) ) );
		}
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$description = term_description();
	} elseif ( is_author() ) {
		$description = get_the_author_meta( 'description', (int) get_query_var( 'author' ) );
	} elseif ( is_search() ) {
		/* translators: %s: search term. */
		$description = sprintf( __( 'Search results for “%s” on the SafeTunnels site.', 'safetunnels' ), get_search_query() );
	} elseif ( is_home() ) {
		$posts_page = (int) get_option( 'page_for_posts' );
		$description = $posts_page ? (string) get_post_meta( $posts_page, '_safetunnels_description', true ) : '';
	}

	if ( ! $description ) {
		$description = get_bloginfo( 'description', 'display' );
	}

	// Refresh the theme's previous default on existing installations while
	// preserving descriptions entered by the site owner.
	if ( 'home' === safetunnels_current_page_key() && 0 === strpos( $description, 'Access every Iranian app from anywhere in the world.' ) ) {
		$description = 'Need an Iranian IP address abroad? SafeTunnels is an Iran VPN for Android that helps you access supported Iranian apps and websites from outside Iran.';
	}

	$description = wp_strip_all_tags( (string) $description );
	$description = preg_replace( '/\s+/u', ' ', $description );
	$description = trim( (string) $description );

	// 155 characters is where Google starts truncating on desktop; cutting on
	// a word boundary keeps the snippet readable rather than clipped mid-word.
	if ( mb_strlen( $description ) > 158 ) {
		$description = mb_substr( $description, 0, 158 );
		$space       = mb_strrpos( $description, ' ' );
		if ( false !== $space && $space > 90 ) {
			$description = mb_substr( $description, 0, $space );
		}
		$description .= '…';
	}

	/**
	 * Filters the meta description.
	 *
	 * @param string $description Resolved description.
	 */
	return apply_filters( 'safetunnels_meta_description', $description );
}

/**
 * The canonical URL of the current view.
 *
 * @return string
 */
function safetunnels_canonical_url() {
	$url = '';

	if ( is_singular() ) {
		$custom = get_post_meta( get_queried_object_id(), '_safetunnels_canonical', true );
		$url    = $custom ? $custom : get_permalink( get_queried_object_id() );
	} elseif ( is_front_page() ) {
		$url = home_url( '/' );
	} elseif ( is_home() ) {
		$url = get_permalink( (int) get_option( 'page_for_posts' ) );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$url = get_term_link( get_queried_object() );
	} elseif ( is_author() ) {
		$url = get_author_posts_url( (int) get_query_var( 'author' ) );
	} elseif ( is_search() ) {
		$url = get_search_link();
	} elseif ( is_year() || is_month() || is_day() ) {
		$url = home_url( add_query_arg( array() ) );
	}

	if ( is_wp_error( $url ) || ! $url ) {
		$url = home_url( add_query_arg( array() ) );
	}

	$page = (int) get_query_var( 'paged' );
	if ( $page > 1 ) {
		$url = trailingslashit( $url ) . 'page/' . $page . '/';
	}

	/**
	 * Filters the canonical URL.
	 *
	 * @param string $url Canonical URL.
	 */
	return apply_filters( 'safetunnels_canonical_url', $url );
}

/**
 * The social sharing image for the current view.
 *
 * @return array{url:string, width:int, height:int, alt:string}
 */
function safetunnels_share_image() {
	$image = array(
		'url'    => '',
		'width'  => 1200,
		'height' => 630,
		'alt'    => get_bloginfo( 'name' ),
	);

	if ( is_singular() ) {
		$id     = get_queried_object_id();
		$custom = get_post_meta( $id, '_safetunnels_og_image', true );

		if ( $custom ) {
			$image['url'] = $custom;
			$image['alt'] = get_the_title( $id );

			return $image;
		}

		if ( has_post_thumbnail( $id ) ) {
			$thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'safetunnels-og' );
			if ( $thumb ) {
				$image['url']    = $thumb[0];
				$image['width']  = (int) $thumb[1];
				$image['height'] = (int) $thumb[2];
				$image['alt']    = get_the_title( $id );

				return $image;
			}
		}
	}

	$fallback     = safetunnels_opt( 'og_image' );
	$image['url'] = $fallback ? $fallback : safetunnels_asset( 'img/og-default.png' );

	return $image;
}

/**
 * Robots directives for the current view.
 *
 * @return string
 */
function safetunnels_robots_directives() {
	$directives = array( 'index', 'follow', 'max-snippet:-1', 'max-image-preview:large', 'max-video-preview:-1' );

	$noindex = is_search() || is_404() || is_paged() && is_front_page();

	if ( is_singular() && get_post_meta( get_queried_object_id(), '_safetunnels_noindex', true ) ) {
		$noindex = true;
	}

	if ( '0' === (string) get_option( 'blog_public' ) ) {
		$noindex = true;
	}

	if ( $noindex ) {
		$directives[0] = 'noindex';
	}

	/**
	 * Filters the robots directives.
	 *
	 * @param string[] $directives Directive list.
	 */
	return implode( ', ', apply_filters( 'safetunnels_robots_directives', $directives ) );
}

/**
 * Prints the full SEO head block.
 *
 * @return void
 */
function safetunnels_print_head_meta() {
	if ( safetunnels_seo_plugin_active() ) {
		return;
	}

	$description = safetunnels_meta_description();
	$canonical   = safetunnels_canonical_url();
	$image       = safetunnels_share_image();
	$site_name   = get_bloginfo( 'name' );
	$title       = wp_get_document_title();
	$type        = is_singular( 'post' ) ? 'article' : 'website';
	$locale      = 'fa-IR' === safetunnels_content_language() ? 'fa_IR' : get_locale();
	$twitter     = safetunnels_opt( 'twitter_handle' );

	echo "\n<!-- SafeTunnels SEO -->\n";

	printf( '<meta name="description" content="%s" />' . "\n", esc_attr( $description ) );
	printf( '<meta name="robots" content="%s" />' . "\n", esc_attr( safetunnels_robots_directives() ) );
	printf( '<link rel="canonical" href="%s" />' . "\n", esc_url( $canonical ) );

	printf( '<meta property="og:locale" content="%s" />' . "\n", esc_attr( $locale ) );
	printf( '<meta property="og:type" content="%s" />' . "\n", esc_attr( $type ) );
	printf( '<meta property="og:title" content="%s" />' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s" />' . "\n", esc_attr( $description ) );
	printf( '<meta property="og:url" content="%s" />' . "\n", esc_url( $canonical ) );
	printf( '<meta property="og:site_name" content="%s" />' . "\n", esc_attr( $site_name ) );

	if ( $image['url'] ) {
		printf( '<meta property="og:image" content="%s" />' . "\n", esc_url( $image['url'] ) );
		printf( '<meta property="og:image:width" content="%d" />' . "\n", (int) $image['width'] );
		printf( '<meta property="og:image:height" content="%d" />' . "\n", (int) $image['height'] );
		printf( '<meta property="og:image:alt" content="%s" />' . "\n", esc_attr( $image['alt'] ) );
	}

	if ( is_singular( 'post' ) ) {
		printf( '<meta property="article:published_time" content="%s" />' . "\n", esc_attr( get_the_date( DATE_W3C ) ) );
		printf( '<meta property="article:modified_time" content="%s" />' . "\n", esc_attr( get_the_modified_date( DATE_W3C ) ) );

		$categories = get_the_category();
		if ( $categories ) {
			printf( '<meta property="article:section" content="%s" />' . "\n", esc_attr( $categories[0]->name ) );
		}
	}

	printf( '<meta name="twitter:card" content="%s" />' . "\n", $image['url'] ? 'summary_large_image' : 'summary' );
	printf( '<meta name="twitter:title" content="%s" />' . "\n", esc_attr( $title ) );
	printf( '<meta name="twitter:description" content="%s" />' . "\n", esc_attr( $description ) );

	if ( $image['url'] ) {
		printf( '<meta name="twitter:image" content="%s" />' . "\n", esc_url( $image['url'] ) );
	}

	if ( $twitter ) {
		printf( '<meta name="twitter:site" content="@%s" />' . "\n", esc_attr( ltrim( $twitter, '@' ) ) );
		printf( '<meta name="twitter:creator" content="@%s" />' . "\n", esc_attr( ltrim( $twitter, '@' ) ) );
	}

	// Pagination rels still help crawlers walk long archives efficiently.
	$prev = get_previous_posts_page_link();
	$next = get_next_posts_page_link();

	if ( is_paged() && $prev ) {
		printf( '<link rel="prev" href="%s" />' . "\n", esc_url( $prev ) );
	}

	if ( ( is_home() || is_archive() || is_search() ) && $next && $GLOBALS['wp_query']->max_num_pages > (int) max( 1, get_query_var( 'paged' ) ) ) {
		printf( '<link rel="next" href="%s" />' . "\n", esc_url( $next ) );
	}

	printf( '<meta name="theme-color" content="%s" />' . "\n", '#050a14' );
	printf( '<meta name="apple-mobile-web-app-title" content="%s" />' . "\n", esc_attr( safetunnels_opt( 'app_name' ) ) );
	printf( '<meta name="application-name" content="%s" />' . "\n", esc_attr( safetunnels_opt( 'app_name' ) ) );

	// Tells Android that the site has a matching Play listing.
	$package = safetunnels_opt( 'apk_package' );
	if ( $package ) {
		printf( '<meta name="google-play-app" content="app-id=%s" />' . "\n", esc_attr( $package ) );
	}

	echo "<!-- /SafeTunnels SEO -->\n\n";
}
add_action( 'wp_head', 'safetunnels_print_head_meta', 1 );

/**
 * Removes the core canonical, which would otherwise duplicate ours.
 *
 * @return void
 */
function safetunnels_remove_core_canonical() {
	if ( ! safetunnels_seo_plugin_active() ) {
		remove_action( 'wp_head', 'rel_canonical' );
	}
}
add_action( 'wp', 'safetunnels_remove_core_canonical' );

/**
 * Appends the sitemap reference to robots.txt.
 *
 * @param string $output Existing robots.txt body.
 * @param string $public Whether the site is public.
 * @return string
 */
function safetunnels_robots_txt( $output, $public ) {
	if ( '1' !== (string) $public ) {
		return $output;
	}

	$lines = array(
		$output,
		'',
		'# SafeTunnels',
		'Disallow: /wp-admin/',
		'Allow: /wp-admin/admin-ajax.php',
		'Disallow: /?s=',
		'Disallow: /search/',
		'Disallow: /payment-result/',
		'',
		'Sitemap: ' . esc_url( home_url( '/wp-sitemap.xml' ) ),
	);

	return implode( "\n", $lines ) . "\n";
}
add_filter( 'robots_txt', 'safetunnels_robots_txt', 10, 2 );

/**
 * Keeps author archives — which hold nothing a search engine wants on a
 * single-author product site — out of the sitemap.
 *
 * @param array  $provider Provider instance.
 * @param string $name     Provider name.
 * @return mixed
 */
function safetunnels_sitemap_providers( $provider, $name ) {
	if ( 'users' === $name ) {
		return false;
	}

	return $provider;
}
add_filter( 'wp_sitemaps_add_provider', 'safetunnels_sitemap_providers', 10, 2 );

/**
 * Excludes noindexed pages from the sitemap.
 *
 * @param array  $args      Query args.
 * @param string $post_type Post type.
 * @return array
 */
function safetunnels_sitemap_exclude_noindex( $args, $post_type ) {
	if ( 'page' !== $post_type ) {
		return $args;
	}

	$args['meta_query'] = array(
		'relation' => 'OR',
		array(
			'key'     => '_safetunnels_noindex',
			'compare' => 'NOT EXISTS',
		),
		array(
			'key'     => '_safetunnels_noindex',
			'value'   => '1',
			'compare' => '!=',
		),
	);

	return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'safetunnels_sitemap_exclude_noindex', 10, 2 );

/**
 * Registers the per-page SEO editor.
 *
 * @return void
 */
function safetunnels_add_seo_metabox() {
	if ( safetunnels_seo_plugin_active() ) {
		return;
	}

	foreach ( array( 'page', 'post' ) as $screen ) {
		add_meta_box(
			'safetunnels-seo',
			__( 'SEO — SafeTunnels', 'safetunnels' ),
			'safetunnels_render_seo_metabox',
			$screen,
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'safetunnels_add_seo_metabox' );

/**
 * Renders the SEO editor.
 *
 * @param WP_Post $post Post being edited.
 * @return void
 */
function safetunnels_render_seo_metabox( $post ) {
	wp_nonce_field( 'safetunnels_save_seo', 'safetunnels_seo_nonce' );

	$fields = array(
		'_safetunnels_title'       => array( __( 'SEO title', 'safetunnels' ), __( 'Overrides the &lt;title&gt; tag. Aim for under 60 characters.', 'safetunnels' ), 'text' ),
		'_safetunnels_description' => array( __( 'Meta description', 'safetunnels' ), __( 'Shown in search results. Aim for 140–158 characters.', 'safetunnels' ), 'textarea' ),
		'_safetunnels_canonical'   => array( __( 'Canonical URL', 'safetunnels' ), __( 'Leave empty unless this page duplicates another URL.', 'safetunnels' ), 'url' ),
		'_safetunnels_og_image'    => array( __( 'Social image URL', 'safetunnels' ), __( 'Defaults to the featured image, then the theme default. 1200×630.', 'safetunnels' ), 'url' ),
	);

	echo '<table class="form-table"><tbody>';

	foreach ( $fields as $key => $field ) {
		list( $label, $help, $type ) = $field;
		$value                       = get_post_meta( $post->ID, $key, true );

		echo '<tr><th scope="row"><label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label></th><td>';

		if ( 'textarea' === $type ) {
			echo '<textarea id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" rows="3" class="large-text">' . esc_textarea( $value ) . '</textarea>';
		} else {
			echo '<input type="' . esc_attr( 'url' === $type ? 'url' : 'text' ) . '" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="large-text" />';
		}

		echo '<p class="description">' . wp_kses_post( $help ) . '</p></td></tr>';
	}

	$noindex = get_post_meta( $post->ID, '_safetunnels_noindex', true );
	echo '<tr><th scope="row">' . esc_html__( 'Indexing', 'safetunnels' ) . '</th><td><label><input type="checkbox" name="_safetunnels_noindex" value="1" ' . checked( $noindex, '1', false ) . ' /> ' . esc_html__( 'Ask search engines not to index this page', 'safetunnels' ) . '</label></td></tr>';

	echo '</tbody></table>';
}

/**
 * Saves the SEO editor.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function safetunnels_save_seo_metabox( $post_id ) {
	if ( ! isset( $_POST['safetunnels_seo_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['safetunnels_seo_nonce'] ) ), 'safetunnels_save_seo' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$text_fields = array( '_safetunnels_title', '_safetunnels_description' );
	foreach ( $text_fields as $key ) {
		$value = isset( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : '';
		if ( '' === $value ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}

	foreach ( array( '_safetunnels_canonical', '_safetunnels_og_image' ) as $key ) {
		$value = isset( $_POST[ $key ] ) ? esc_url_raw( wp_unslash( $_POST[ $key ] ) ) : '';
		if ( '' === $value ) {
			delete_post_meta( $post_id, $key );
		} else {
			update_post_meta( $post_id, $key, $value );
		}
	}

	if ( isset( $_POST['_safetunnels_noindex'] ) ) {
		update_post_meta( $post_id, '_safetunnels_noindex', '1' );
	} else {
		delete_post_meta( $post_id, '_safetunnels_noindex' );
	}
}
add_action( 'save_post', 'safetunnels_save_seo_metabox' );
