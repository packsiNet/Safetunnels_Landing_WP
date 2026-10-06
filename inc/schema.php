<?php
/**
 * Structured data (JSON-LD).
 *
 * One connected `@graph` per page rather than a pile of disconnected blocks —
 * nodes reference each other by `@id`, which is what lets Google resolve the
 * publisher of a page, the app a review belongs to, and the breadcrumb trail
 * of an article without guessing.
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

/**
 * Stable @id for a graph node.
 *
 * @param string $fragment Fragment name.
 * @return string
 */
function safetunnels_schema_id( $fragment ) {
	return home_url( '/' ) . '#' . $fragment;
}

/**
 * The publisher node.
 *
 * @return array
 */
function safetunnels_schema_organization() {
	$logo = get_theme_mod( 'custom_logo' ) ? wp_get_attachment_image_url( (int) get_theme_mod( 'custom_logo' ), 'full' ) : safetunnels_asset( 'img/logo.png' );

	$node = array(
		'@type'  => 'Organization',
		'@id'    => safetunnels_schema_id( 'organization' ),
		'name'   => safetunnels_opt( 'developer_name' ),
		'url'    => home_url( '/' ),
		'email'  => safetunnels_opt( 'support_email' ),
		'logo'   => array(
			'@type' => 'ImageObject',
			'@id'   => safetunnels_schema_id( 'logo' ),
			'url'   => $logo,
		),
		'brand'  => array(
			'@type' => 'Brand',
			'name'  => safetunnels_opt( 'app_name' ),
		),
		'contactPoint' => array(
			'@type'             => 'ContactPoint',
			'contactType'       => 'customer support',
			'email'             => safetunnels_opt( 'support_email' ),
			'availableLanguage' => array( 'English', 'Persian' ),
		),
	);

	$twitter = safetunnels_opt( 'twitter_handle' );
	if ( $twitter ) {
		$node['sameAs'] = array( 'https://twitter.com/' . ltrim( $twitter, '@' ) );
	}

	return $node;
}

/**
 * The site node, with the search action that powers a sitelinks searchbox.
 *
 * @return array
 */
function safetunnels_schema_website() {
	return array(
		'@type'           => 'WebSite',
		'@id'             => safetunnels_schema_id( 'website' ),
		'url'             => home_url( '/' ),
		'name'            => get_bloginfo( 'name' ),
		'description'     => get_bloginfo( 'description' ),
		'publisher'       => array( '@id' => safetunnels_schema_id( 'organization' ) ),
		'inLanguage'      => get_bloginfo( 'language' ),
		'potentialAction' => array(
			array(
				'@type'       => 'SearchAction',
				'target'      => array(
					'@type'       => 'EntryPoint',
					'urlTemplate' => home_url( '/?s={search_term_string}' ),
				),
				'query-input' => 'required name=search_term_string',
			),
		),
	);
}

/**
 * The SoftwareApplication node — the single most valuable piece of markup on
 * the site, since it is what makes the app eligible for rich results with a
 * price and a star rating.
 *
 * @return array
 */
function safetunnels_schema_application() {
	$periods = safetunnels_premium_periods();
	$offers  = array(
		array(
			'@type'         => 'Offer',
			'name'          => __( 'Free', 'safetunnels' ),
			'price'         => '0',
			'priceCurrency' => 'USD',
			'availability'  => 'https://schema.org/InStock',
			'url'           => safetunnels_page_url( 'pricing' ),
		),
	);

	$prices = array( 0.0 );

	foreach ( $periods as $key => $period ) {
		if ( ! empty( $period['disabled'] ) ) {
			continue;
		}

		$prices[] = (float) $period['amount'];

		$offers[] = array(
			'@type'           => 'Offer',
			'name'            => sprintf( 'Premium — %s', $period['label'] ),
			'price'           => $period['amount'],
			'priceCurrency'   => 'USD',
			'availability'    => 'https://schema.org/InStock',
			'url'             => safetunnels_page_url( 'pricing' ),
			'category'        => 'subscription',
			'eligibleDuration' => array(
				'@type'    => 'QuantitativeValue',
				'value'    => $period['duration'],
				'unitCode' => 'ANN',
			),
		);
	}

	$noads    = safetunnels_noads_plan();
	$prices[] = (float) $noads['amount'];
	$offers[] = array(
		'@type'         => 'Offer',
		'name'          => __( 'No Ads — Lifetime', 'safetunnels' ),
		'price'         => $noads['amount'],
		'priceCurrency' => 'USD',
		'availability'  => 'https://schema.org/InStock',
		'url'           => safetunnels_page_url( 'pricing' ),
	);

	$node = array(
		'@type'               => array( 'SoftwareApplication', 'MobileApplication' ),
		'@id'                 => safetunnels_schema_id( 'app' ),
		'name'                => safetunnels_opt( 'app_name' ),
		'alternateName'       => safetunnels_opt( 'brand_name' ),
		'operatingSystem'     => 'Android 8.0+',
		'applicationCategory' => 'SecurityApplication',
		'applicationSubCategory' => 'VPN',
		'url'                 => home_url( '/' ),
		'downloadUrl'         => safetunnels_apk_url(),
		'installUrl'          => safetunnels_page_url( 'download' ),
		'softwareVersion'     => safetunnels_opt( 'apk_version' ),
		'fileSize'            => safetunnels_opt( 'apk_size' ),
		'author'              => array( '@id' => safetunnels_schema_id( 'organization' ) ),
		'publisher'           => array( '@id' => safetunnels_schema_id( 'organization' ) ),
		'inLanguage'          => array( 'en', 'fa' ),
		'description'         => __( 'SafeTunnels routes only Iranian app traffic through an AES-256 encrypted WireGuard tunnel, so Iranians abroad can reach banking, payment, shopping and streaming apps while everything else keeps using the normal connection.', 'safetunnels' ),
		'featureList'         => implode( ', ', wp_list_pluck( safetunnels_feature_items(), 'title' ) ),
		'offers'              => array(
			'@type'         => 'AggregateOffer',
			'priceCurrency' => 'USD',
			'lowPrice'      => number_format( min( $prices ), 2, '.', '' ),
			'highPrice'     => number_format( max( $prices ), 2, '.', '' ),
			'offerCount'    => count( $offers ),
			'offers'        => $offers,
		),
	);

	if ( ! $node['softwareVersion'] ) {
		unset( $node['softwareVersion'] );
	}

	if ( ! $node['fileSize'] ) {
		unset( $node['fileSize'] );
	}

	$reviews = safetunnels_get_reviews( 1, 9 );

	if ( ! empty( $reviews['totalCount'] ) && ! empty( $reviews['averageRating'] ) ) {
		$node['aggregateRating'] = array(
			'@type'       => 'AggregateRating',
			'ratingValue' => round( (float) $reviews['averageRating'], 1 ),
			'reviewCount' => (int) $reviews['totalCount'],
			'bestRating'  => 5,
			'worstRating' => 1,
		);

		$items = array();
		foreach ( array_slice( (array) $reviews['items'], 0, 6 ) as $review ) {
			$item = array(
				'@type'        => 'Review',
				'reviewRating' => array(
					'@type'       => 'Rating',
					'ratingValue' => (int) $review['rating'],
					'bestRating'  => 5,
					'worstRating' => 1,
				),
				'author'       => array(
					'@type' => 'Person',
					'name'  => $review['fullName'],
				),
			);

			if ( ! empty( $review['comment'] ) ) {
				$item['reviewBody'] = $review['comment'];
			}

			if ( ! empty( $review['createdAt'] ) ) {
				$item['datePublished'] = gmdate( 'Y-m-d', strtotime( $review['createdAt'] ) );
			}

			$items[] = $item;
		}

		if ( $items ) {
			$node['review'] = $items;
		}
	}

	return $node;
}

/**
 * Breadcrumb trail for the current view, as an array of label/URL pairs.
 *
 * @return array<int, array{name:string, url:string}>
 */
function safetunnels_breadcrumb_trail() {
	$trail = array(
		array(
			'name' => __( 'Home', 'safetunnels' ),
			'url'  => home_url( '/' ),
		),
	);

	if ( is_front_page() ) {
		return $trail;
	}

	if ( is_singular( 'post' ) ) {
		$posts_page = (int) get_option( 'page_for_posts' );
		if ( $posts_page ) {
			$trail[] = array(
				'name' => get_the_title( $posts_page ),
				'url'  => get_permalink( $posts_page ),
			);
		}

		$categories = get_the_category();
		if ( $categories ) {
			$trail[] = array(
				'name' => $categories[0]->name,
				'url'  => get_category_link( $categories[0]->term_id ),
			);
		}

		$trail[] = array(
			'name' => get_the_title(),
			'url'  => get_permalink(),
		);

		return $trail;
	}

	if ( is_page() ) {
		$ancestors = array_reverse( get_post_ancestors( get_queried_object_id() ) );
		foreach ( $ancestors as $ancestor ) {
			$trail[] = array(
				'name' => get_the_title( $ancestor ),
				'url'  => get_permalink( $ancestor ),
			);
		}

		$trail[] = array(
			'name' => get_the_title(),
			'url'  => get_permalink(),
		);

		return $trail;
	}

	if ( is_category() || is_tag() || is_tax() ) {
		$term    = get_queried_object();
		$trail[] = array(
			'name' => $term->name,
			'url'  => (string) get_term_link( $term ),
		);

		return $trail;
	}

	if ( is_search() ) {
		$trail[] = array(
			/* translators: %s: search term. */
			'name' => sprintf( __( 'Search: %s', 'safetunnels' ), get_search_query() ),
			'url'  => get_search_link(),
		);

		return $trail;
	}

	if ( is_home() ) {
		$trail[] = array(
			'name' => get_the_title( (int) get_option( 'page_for_posts' ) ),
			'url'  => get_permalink( (int) get_option( 'page_for_posts' ) ),
		);

		return $trail;
	}

	if ( is_404() ) {
		$trail[] = array(
			'name' => __( 'Page Not Found', 'safetunnels' ),
			'url'  => home_url( add_query_arg( array() ) ),
		);
	}

	return $trail;
}

/**
 * BreadcrumbList node.
 *
 * @return array
 */
function safetunnels_schema_breadcrumbs() {
	$items = array();
	$pos   = 1;

	foreach ( safetunnels_breadcrumb_trail() as $crumb ) {
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $pos,
			'name'     => $crumb['name'],
			'item'     => $crumb['url'],
		);
		$pos++;
	}

	return array(
		'@type'           => 'BreadcrumbList',
		'@id'             => safetunnels_canonical_url() . '#breadcrumb',
		'itemListElement' => $items,
	);
}

/**
 * FAQPage node built from a question list.
 *
 * @param array  $items Question/answer pairs.
 * @param string $id    Node id suffix.
 * @return array
 */
function safetunnels_schema_faq( $items, $id = 'faq' ) {
	$entities = array();

	foreach ( $items as $item ) {
		$entities[] = array(
			'@type'          => 'Question',
			'name'           => wp_strip_all_tags( $item['q'] ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => wp_strip_all_tags( $item['a'] ),
			),
		);
	}

	return array(
		'@type'      => 'FAQPage',
		'@id'        => safetunnels_canonical_url() . '#' . $id,
		'mainEntity' => $entities,
	);
}

/**
 * ItemList of the supported Iranian apps.
 *
 * @return array
 */
function safetunnels_schema_app_list() {
	$elements = array();
	$pos      = 1;

	foreach ( safetunnels_all_apps() as $app ) {
		$element = array(
			'@type'    => 'ListItem',
			'position' => $pos,
			'name'     => $app['name'],
		);

		if ( ! empty( $app['url'] ) ) {
			$element['url'] = $app['url'];
		}

		$elements[] = $element;
		$pos++;
	}

	return array(
		'@type'            => 'ItemList',
		'@id'              => safetunnels_canonical_url() . '#applist',
		'name'             => __( 'Iranian apps supported by SafeTunnels', 'safetunnels' ),
		'numberOfItems'    => count( $elements ),
		'itemListElement'  => $elements,
	);
}

/**
 * The WebPage / Article node for the current view.
 *
 * @return array
 */
function safetunnels_schema_webpage() {
	$canonical = safetunnels_canonical_url();
	$key       = safetunnels_current_page_key();

	$type = 'WebPage';

	if ( is_singular( 'post' ) ) {
		$type = 'BlogPosting';
	} elseif ( 'contact' === $key ) {
		$type = 'ContactPage';
	} elseif ( 'apps' === $key ) {
		$type = 'CollectionPage';
	} elseif ( is_home() || is_archive() ) {
		$type = 'CollectionPage';
	} elseif ( is_search() ) {
		$type = 'SearchResultsPage';
	}

	$node = array(
		'@type'      => $type,
		'@id'        => $canonical . '#webpage',
		'url'        => $canonical,
		'name'       => wp_get_document_title(),
		'description' => safetunnels_meta_description(),
		'isPartOf'   => array( '@id' => safetunnels_schema_id( 'website' ) ),
		'breadcrumb' => array( '@id' => $canonical . '#breadcrumb' ),
		'inLanguage' => get_bloginfo( 'language' ),
	);

	$image = safetunnels_share_image();
	if ( $image['url'] ) {
		$node['primaryImageOfPage'] = array(
			'@type' => 'ImageObject',
			'url'   => $image['url'],
		);
	}

	if ( is_singular( 'post' ) ) {
		$node['headline']      = get_the_title();
		$node['datePublished'] = get_the_date( DATE_W3C );
		$node['dateModified']  = get_the_modified_date( DATE_W3C );
		$node['author']        = array(
			'@type' => 'Person',
			'name'  => get_the_author(),
			'url'   => get_author_posts_url( (int) get_the_author_meta( 'ID' ) ),
		);
		$node['publisher']     = array( '@id' => safetunnels_schema_id( 'organization' ) );
		$node['mainEntityOfPage'] = $canonical;

		$words = str_word_count( wp_strip_all_tags( (string) get_post_field( 'post_content', get_queried_object_id() ) ) );
		if ( $words ) {
			$node['wordCount'] = $words;
		}
	} elseif ( is_singular() ) {
		$node['datePublished'] = get_the_date( DATE_W3C );
		$node['dateModified']  = get_the_modified_date( DATE_W3C );
	}

	return $node;
}

/**
 * Prints the JSON-LD graph.
 *
 * @return void
 */
function safetunnels_print_schema() {
	if ( safetunnels_seo_plugin_active() ) {
		return;
	}

	$graph = array(
		safetunnels_schema_organization(),
		safetunnels_schema_website(),
		safetunnels_schema_webpage(),
		safetunnels_schema_breadcrumbs(),
	);

	$key = safetunnels_current_page_key();

	// The app node belongs on the pages that actually talk about the app —
	// repeating it on every blog post would dilute, not help.
	if ( is_front_page() || in_array( $key, array( 'download', 'pricing', 'apps' ), true ) ) {
		$graph[] = safetunnels_schema_application();
	}

	if ( is_front_page() ) {
		$graph[] = safetunnels_schema_faq( safetunnels_landing_faq(), 'faq' );
	}

	if ( 'faq' === $key ) {
		$graph[] = safetunnels_schema_faq( safetunnels_faq_flat(), 'faq' );
	}

	if ( 'apps' === $key ) {
		$graph[] = safetunnels_schema_app_list();
	}

	/**
	 * Filters the JSON-LD graph before output.
	 *
	 * @param array $graph Graph nodes.
	 */
	$graph = apply_filters( 'safetunnels_schema_graph', $graph );

	$payload = array(
		'@context' => 'https://schema.org',
		'@graph'   => array_values( $graph ),
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'safetunnels_print_schema', 5 );
