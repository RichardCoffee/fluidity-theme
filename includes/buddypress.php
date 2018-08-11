<?php

/**
 *  support for BuddyPress plugin
 *
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 */

/**
 *  mainly to check for errors when redirecting for buddypress
 *
 * @since 20170210
 * @param string $redirect_to
 * @param string $request
 * @param WP_User/WP_Error $user
 * @return string
 */
if ( ! function_exists( 'fluidity_buddypress_login_redirect' ) ) {
	function fluidity_buddypress_login_redirect( $redirect_to, $request, $user ) {
		if ( ! $user ) {
			$redirect_to = home_url();
		} else if ( ! is_object( $user ) ) {
			fluid()->log( 'user var is not an object', $user, 'stack' );
		} else if ( get_class( $user ) === 'WP_Error' ) {
			fluid()->log( 'user var is a WP_Error object', $user, $redirect_to );
		} else {
			$user_name   = $user->data->user_nicename;
			$redirect_to = home_url( "members/$user_name/profile/" ) ;
fluid()->log(
	'   redirect_to:  ' . func_get_arg(0),
	'       request:  ' . $request,
	'wp_get_referer:  ' . wp_get_referer(),
	'       new url:  ' . $redirect_to,
	$user
);
		}
		return $redirect_to;
	}
	add_filter( 'login_redirect', 'fluidity_buddypress_login_redirect', 10, 3 );
}

/**
 *   inserts css needed to support having a fluid sidebar
 *
 * @since 20180810
 */
if ( ! function_exists( 'fluidity_buddypress_sidebar_fluid_styling' ) ) {
	function fluidity_buddypress_sidebar_fluid_styling() {
		echo "\nform#members-directory-form.dir-form {\n\tclear: inherit;\n}\n";
		echo "\nform#members-directory-form div.pagination {\n\twidth: auto;\n}\n";
		echo "\nform#members-directory-form ul.item-list {\n\tclear: left;\n}\n";
	}
	add_action( 'fluidity_sidebar_fluid_styling', 'fluidity_buddypress_sidebar_fluid_styling' );
}
