<?php
/** Deployment probe: checks the code actually served by the web PHP process. */
defined( 'ABSPATH' ) || exit;

add_action( 'rest_api_init', function () {
	register_rest_route( 'safetunnels/v1', '/deployment', array(
		'methods'             => 'GET',
		'permission_callback' => '__return_true',
		'callback'            => function () {
			$revision = defined( 'SAFETUNNELS_DEPLOYED_REVISION' ) ? SAFETUNNELS_DEPLOYED_REVISION : 'development';
			$response = new WP_REST_Response( array( 'revision' => $revision, 'theme' => get_stylesheet() ), 200 );
			$response->header( 'Cache-Control', 'no-store, max-age=0' );
			return $response;
		},
	) );
} );
