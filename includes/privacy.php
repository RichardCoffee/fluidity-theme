<?php

if ( ! function_exists( 'add_privacy_filters' ) ) {
	function add_privacy_filters() {
		add_filter( 'pre_site_option_blog_count', 'privacy_user_count', 100, 3 );
		add_filter( 'pre_site_option_user_count', 'privacy_blog_count', 100, 3 );
	}
	add_action( 'wp_version_check', 'add_privacy_filters' );
}

if ( ! function_exists( 'privacy_blog_count' ) ) {
	function privacy_blog_count( $count, $option, $network_id ) {
		$privacy = tcc_privacy( 'blogs' );
		if ( $privacy && ( $privacy === 'no' ) ) {
			return 1;
		}
		return $count;
	}
}

if ( ! function_exists( 'privacy_user_count' ) ) {
	function privacy_user_count( $count, $option, $network_id ) {
		$privacy = tcc_privacy( 'users' );
		if ( $privacy ) {
			switch( $privacy ) {
				case 'all':
					return false;
				case 'some':
					$users = get_user_count();
					return intval( ( $user / 10 ), 10 );
				case 'one':
					return 1;
				case 'many':
					$users = get_user_count();
					return rand( 1, ( $users * 10 ) );
			}
		}
		return get_user_count();
	}
}

if ( ! function_exists( 'tcc_privacy' ) ) {
	function tcc_privacy( $option ) {
		static $data;
		if ( empty( $data ) ) {
			$data = get_option( 'tcc_options_privacy' ); }
		if ( isset( $data[ $option ] ) ) {
			return $data[ $option ]; }
		return '';
	}
}
