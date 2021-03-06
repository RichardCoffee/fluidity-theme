<?php

#	https://developer.wordpress.org/rest-api/using-the-rest-api/frequently-asked-questions/
#	https://maddisondesigns.com/2016/12/what-you-may-not-know-about-the-wp-rest-api/

if ( ! function_exists( 'fluidity_rest_authentication' ) ) {
	function fluidity_rest_authentication( $result ) {
		$status = tcc_option( 'status', 'apicontrol', 'on' );
		if ( $status === 'off' ) {
			return new WP_Error( 'rest_forbidden_context', 'Unauthorized access.', array( 'status' => rest_authorization_required_code() ) );
		}
		if ( ( ! is_user_logged_in() ) && in_array( $status, array( 'admin', 'users' ), true ) ) {
			return new WP_Error( 'rest_not_logged_in', 'You are not currently logged in.', array( 'status' => rest_authorization_required_code() ) );
		}
		if ( ( $status === 'admin' ) && ! current_user_can( 'manage_options' ) ) {
			return new WP_Error( 'rest_forbidden_context', 'You do not have access to this endpoint.', array( 'status' => rest_authorization_required_code() ) );
		}
		return $result;
	}
	add_filter( 'rest_authentication_errors', 'fluidity_rest_authentication' );
}

if ( ! function_exists( 'fluidity_disable_rest' ) ) {
	function fluidity_disable_rest( $endpoints ) {
		$status = tcc_option( 'status', 'apicontrol', 'on' );
		if ( $status === 'off' ) {
			$endpoints = array();
		}
		return $endpoints;
	}
	add_filter( 'rest_endpoints', 'fluidity_disable_rest' );
}

if ( ! function_exists( 'fluidity_filter_rest_endpoints' ) ) {
	function fluidity_filter_rest_endpoints( $endpoints ) {
		$status = tcc_option( 'status', 'apicontrol', 'on' );
		if ( ( ! empty( $endpoints) ) && ( $status === 'filter' ) ) {
			$options = TCC_Options_APIControl::instance();
			$allowed = $options->get_allowed_endpoints();
			$current = array();
			if ( $allowed ) {
				foreach( $endpoints as $key => $endpoint ) {
					if ( in_array( $key, $allowed ) ) {
						$current[ $key ] = $endpoint;
					}
				}
			}
			$endpoints = $current;
		}
		return $endpoints;
	}
	add_filter( 'rest_endpoints', 'fluidity_filter_rest_endpoints' );
}
