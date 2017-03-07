<?php

if ( ! function_exists( 'fluidity_check_update' ) ) {
	function fluidity_check_update() {
		$version = get_option( 'tcc_theme_version', FLUIDITY_VERSION );
log_entry($version, FLUIDITY_VERSION);
		if ( $version !== FLUIDITY_VERSION ) {
			$func = 'fluidity_update_' . implode( '_', explode( '.', FLUIDITY_VERSION ) );
			if ( function_exists( $func ) ) {
				$func( $version );
			}
		}
		update_option( 'tcc_theme_version', FLUIDITY_VERSION );
	}
}

if ( ! function_exists( 'fluidity_update_future_version_number' ) ) {
	function fluidity_update_future_version_number( $version ) {
		if ( $version !== '2.2.2' ) { fluidity_update_2_2_2( $version ); }
		//  future update code here
	}
}

if ( ! function_exists( 'fluidity_update_2_2_2' ) ) {
	function fluidity_update_2_2_2( $version ) {
		$content = get_option( 'tcc_options_content', array() );
		if ( ! isset( $content['postdate'] ) ) {
			$settings = get_option( 'tcc_options_admin', array() );
			if ( isset( $settings['postdate'] ) ) {
				$content['postdate'] = $settings['postdate'];
			}
		}
		$checks = array( 'content', 'exdate', 'exlength' );
		$layout = get_option( 'tcc_options_layout', array() );
		foreach ( $checks as $check ) {
			if ( ! isset( $content[ $check ] ) ) {
				if ( isset( $layout[ $check ] ) ) {
					$content[ $check ] = $layout[ $check ];
				}
			}
		}
		update_option( 'tcc_options_content', $content );
	}
}

fluidity_check_update();
