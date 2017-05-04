<?php

#	https://developer.wordpress.org/rest-api/using-the-rest-api/frequently-asked-questions/

if ( ! function_exists( 'fluidity_rest_authentication' ) ) {
	function fluidity_rest_authentication( $result ) {
		if ( ! empty( $result ) ) {
			return $result;
		}
		$status = tcc_option( 'status', 'apicontrol' );
		if ( ( $status === 'users' ) && ! is_user_logged_in() ) {
			return new WP_Error( 'rest_not_logged_in', 'You are not currently logged in.', array( 'status' => rest_authorization_required_code() ) );
		}
		return $result;
	}
	add_filter( 'rest_authentication_errors', 'fluidity_rest_authentication' );
}
