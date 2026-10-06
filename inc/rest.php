<?php
/**
 * Server-side bridge to the SafeTunnels backend.
 *
 * The React app called `core.safetunnels.net` straight from the browser. Doing that
 * from a WordPress install on a new domain would need the API's CORS allow-list
 * updated first, so every call is proxied through WordPress instead: same
 * endpoints, same payloads, but same-origin — plus a short transient cache in
 * front of the read endpoints so the landing page can render reviews on the
 * server (which is what makes them indexable).
 *
 * @package SafeTunnels
 */

defined( 'ABSPATH' ) || exit;

/**
 * Absolute URL of a backend endpoint.
 *
 * @param string $path Path beginning with a slash.
 * @return string
 */
function safetunnels_api_url( $path ) {
	return untrailingslashit( safetunnels_opt( 'api_base_url' ) ) . $path;
}

/**
 * Performs a backend request and normalises the result.
 *
 * @param string $method HTTP method.
 * @param string $path   Endpoint path.
 * @param array  $args   Optional. `body` for writes, `query` for reads.
 * @return array{code:int, body:mixed}|WP_Error
 */
function safetunnels_api_request( $method, $path, $args = array() ) {
	$url = safetunnels_api_url( $path );

	if ( ! empty( $args['query'] ) ) {
		$url = add_query_arg( $args['query'], $url );
	}

	$request = array(
		'method'    => $method,
		'timeout'   => 12,
		'headers'   => array( 'Accept' => 'application/json' ),
		'sslverify' => true,
	);

	if ( isset( $args['body'] ) ) {
		$request['headers']['Content-Type'] = 'application/json';
		$request['body']                    = wp_json_encode( $args['body'] );
	}

	$response = wp_remote_request( $url, $request );

	if ( is_wp_error( $response ) ) {
		return $response;
	}

	$raw  = wp_remote_retrieve_body( $response );
	$json = json_decode( $raw, true );

	return array(
		'code' => (int) wp_remote_retrieve_response_code( $response ),
		'body' => ( null === $json ) ? $raw : $json,
	);
}

/**
 * Fetches approved reviews, cached for the configured TTL.
 *
 * @param int  $page      Page number.
 * @param int  $page_size Items per page.
 * @param bool $fresh     Skip the cache.
 * @return array{totalCount:int, averageRating:float, items:array}
 */
function safetunnels_get_reviews( $page = 1, $page_size = 9, $fresh = false ) {
	$empty = array(
		'totalCount'    => 0,
		'averageRating' => 0,
		'items'         => array(),
	);

	$page      = max( 1, (int) $page );
	$page_size = min( 50, max( 1, (int) $page_size ) );
	$key       = 'safetunnels_reviews_' . $page . '_' . $page_size;

	if ( ! $fresh ) {
		$cached = get_transient( $key );
		if ( false !== $cached ) {
			return $cached;
		}
	}

	$result = safetunnels_api_request(
		'GET',
		'/api/reviews',
		array(
			'query' => array(
				'page'     => $page,
				'pageSize' => $page_size,
			),
		)
	);

	if ( is_wp_error( $result ) || $result['code'] < 200 || $result['code'] >= 300 || ! is_array( $result['body'] ) ) {
		// Cache the failure briefly so a backend outage can't turn every page
		// view into a 12-second wait.
		set_transient( $key, $empty, 60 );

		return $empty;
	}

	$data = wp_parse_args( $result['body'], $empty );
	set_transient( $key, $data, (int) safetunnels_opt( 'reviews_ttl' ) );

	return $data;
}

/**
 * Turns a two-letter country code into its flag emoji — the PHP twin of
 * `countryFlag()` in reviews.api.ts.
 *
 * @param string|null $code ISO 3166-1 alpha-2 code.
 * @return string
 */
function safetunnels_country_flag( $code ) {
	if ( ! $code || 2 !== strlen( $code ) ) {
		return '';
	}

	$flag = '';
	foreach ( str_split( strtoupper( $code ) ) as $char ) {
		$flag .= mb_convert_encoding( '&#' . ( ord( $char ) - 65 + 0x1F1E6 ) . ';', 'UTF-8', 'HTML-ENTITIES' );
	}

	return $flag;
}

/**
 * Registers the proxy routes.
 *
 * @return void
 */
function safetunnels_register_rest_routes() {
	register_rest_route(
		'safetunnels/v1',
		'/reviews',
		array(
			array(
				'methods'             => 'GET',
				'callback'            => 'safetunnels_rest_get_reviews',
				'permission_callback' => '__return_true',
				'args'                => array(
					'page'     => array(
						'default'           => 1,
						'sanitize_callback' => 'absint',
					),
					'pageSize' => array(
						'default'           => 9,
						'sanitize_callback' => 'absint',
					),
				),
			),
			array(
				'methods'             => 'POST',
				'callback'            => 'safetunnels_rest_submit_review',
				'permission_callback' => '__return_true',
			),
		)
	);

	register_rest_route(
		'safetunnels/v1',
		'/verify-key',
		array(
			'methods'             => 'GET',
			'callback'            => 'safetunnels_rest_verify_key',
			'permission_callback' => '__return_true',
			'args'                => array(
				'userId' => array(
					'required'          => true,
					'sanitize_callback' => 'sanitize_text_field',
				),
			),
		)
	);

	register_rest_route(
		'safetunnels/v1',
		'/track',
		array(
			'methods'             => 'POST',
			'callback'            => 'safetunnels_rest_track',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'safetunnels_register_rest_routes' );

/**
 * GET /safetunnels/v1/reviews
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response
 */
function safetunnels_rest_get_reviews( $request ) {
	$data = safetunnels_get_reviews( $request->get_param( 'page' ), $request->get_param( 'pageSize' ) );

	$response = rest_ensure_response( $data );
	$response->header( 'Cache-Control', 'public, max-age=60' );

	return $response;
}

/**
 * POST /safetunnels/v1/reviews
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response|WP_Error
 */
function safetunnels_rest_submit_review( $request ) {
	$limit = safetunnels_rate_limit( 'review', 5, HOUR_IN_SECONDS );
	if ( is_wp_error( $limit ) ) {
		return $limit;
	}

	$full_name = sanitize_text_field( (string) $request->get_param( 'fullName' ) );
	$rating    = (int) $request->get_param( 'rating' );
	$comment   = sanitize_textarea_field( (string) $request->get_param( 'comment' ) );

	if ( '' === trim( $full_name ) || $rating < 1 || $rating > 5 ) {
		return new WP_Error( 'safetunnels_invalid_review', __( 'Please provide a name and a rating between 1 and 5.', 'safetunnels' ), array( 'status' => 400 ) );
	}

	$payload = array(
		'fullName' => mb_substr( $full_name, 0, 150 ),
		'rating'   => $rating,
	);

	if ( '' !== trim( $comment ) ) {
		$payload['comment'] = mb_substr( $comment, 0, 1000 );
	}

	$result = safetunnels_api_request( 'POST', '/api/reviews', array( 'body' => $payload ) );

	if ( is_wp_error( $result ) ) {
		return new WP_Error( 'safetunnels_upstream', __( 'Error posting comment', 'safetunnels' ), array( 'status' => 502 ) );
	}

	if ( $result['code'] < 200 || $result['code'] >= 300 ) {
		$message = __( 'Error posting comment', 'safetunnels' );
		if ( is_array( $result['body'] ) && ! empty( $result['body']['error'] ) ) {
			$message = (string) $result['body']['error'];
		}

		return new WP_Error( 'safetunnels_upstream', $message, array( 'status' => 400 ) );
	}

	safetunnels_flush_review_cache();

	return rest_ensure_response( array( 'ok' => true ) );
}

/**
 * GET /safetunnels/v1/verify-key
 *
 * Pre-purchase check on the account key pasted from the app, so a typo fails
 * before a paid checkout starts instead of paying into a void.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response|WP_Error
 */
function safetunnels_rest_verify_key( $request ) {
	$limit = safetunnels_rate_limit( 'verify', 30, HOUR_IN_SECONDS );
	if ( is_wp_error( $limit ) ) {
		return $limit;
	}

	$user_id = strtolower( trim( (string) $request->get_param( 'userId' ) ) );

	if ( ! preg_match( '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $user_id ) ) {
		return new WP_Error( 'safetunnels_invalid_key', __( 'That doesn\'t look like a valid key.', 'safetunnels' ), array( 'status' => 400 ) );
	}

	$result = safetunnels_api_request( 'GET', '/api/billing/verify-key', array( 'query' => array( 'userId' => $user_id ) ) );

	if ( is_wp_error( $result ) ) {
		return new WP_Error( 'safetunnels_upstream', __( 'Could not verify your key. Please try again.', 'safetunnels' ), array( 'status' => 502 ) );
	}

	if ( 400 === $result['code'] ) {
		return new WP_Error( 'safetunnels_invalid_key', __( 'That doesn\'t look like a valid key.', 'safetunnels' ), array( 'status' => 400 ) );
	}

	if ( $result['code'] < 200 || $result['code'] >= 300 || ! is_array( $result['body'] ) ) {
		return new WP_Error( 'safetunnels_upstream', __( 'Could not verify your key. Please try again.', 'safetunnels' ), array( 'status' => 502 ) );
	}

	return rest_ensure_response(
		array(
			'exists'      => ! empty( $result['body']['exists'] ),
			'maskedEmail' => isset( $result['body']['maskedEmail'] ) ? $result['body']['maskedEmail'] : null,
		)
	);
}

/**
 * POST /safetunnels/v1/track — page views and download-link clicks.
 *
 * Fire-and-forget on both sides: a failure here must never block a download.
 *
 * @param WP_REST_Request $request Request.
 * @return WP_REST_Response
 */
function safetunnels_rest_track( $request ) {
	$type = sanitize_key( (string) $request->get_param( 'type' ) );

	if ( 'view' === $type ) {
		safetunnels_api_request( 'POST', '/api/stats/login' );

		return rest_ensure_response( array( 'ok' => true ) );
	}

	if ( 'download' === $type ) {
		$package = sanitize_text_field( (string) $request->get_param( 'packageName' ) );

		if ( '' !== $package ) {
			safetunnels_api_request( 'POST', '/api/stats/download-click', array( 'body' => array( 'packageName' => $package ) ) );
		}

		return rest_ensure_response( array( 'ok' => true ) );
	}

	return rest_ensure_response( array( 'ok' => false ) );
}

/**
 * Very small per-IP throttle for the write endpoints.
 *
 * @param string $bucket Bucket name.
 * @param int    $max    Allowed requests per window.
 * @param int    $window Window length in seconds.
 * @return true|WP_Error
 */
function safetunnels_rate_limit( $bucket, $max, $window ) {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '0';
	$key = 'st_rl_' . $bucket . '_' . md5( $ip );

	$hits = (int) get_transient( $key );

	if ( $hits >= $max ) {
		return new WP_Error( 'safetunnels_rate_limited', __( 'Too many requests — please try again in a little while.', 'safetunnels' ), array( 'status' => 429 ) );
	}

	set_transient( $key, $hits + 1, $window );

	return true;
}

/**
 * Clears every cached review page.
 *
 * @return void
 */
function safetunnels_flush_review_cache() {
	global $wpdb;

	$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_safetunnels_reviews_%' OR option_name LIKE '_transient_timeout_safetunnels_reviews_%'" );
}
