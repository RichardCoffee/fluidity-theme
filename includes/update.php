<?php

if ( ! function_exists( 'fluidity_check_update' ) ) {
	function fluidity_check_update() {
		$version = get_option( 'tcc_theme_version', FLUIDITY_VERSION );
		if ( version_compare( $version, FLUIDITY_VERSION, '<' ) ) {
			$func = 'fluidity_update_' . implode( '_', explode( '.', FLUIDITY_VERSION ) );
			if ( function_exists( $func ) ) {
				$func( $version );
			}
		}
		update_option( 'tcc_theme_version', FLUIDITY_VERSION );
	}
}

if ( ! function_exists( 'fluidity_update_2_2_2' ) ) {
	function fluidity_update_2_2_2( $version ) {
		$content  = get_option( 'tcc_options_content', array() );
		$settings = get_option( 'tcc_options_admin', array() );
		if ( isset( $settings['postdate'] ) ) {
			$content['postdate'] = $settings['postdate'];
		}
		$checks = array( 'content', 'exdate', 'exlength' );
		$layout = get_option( 'tcc_options_layout', array() );
		foreach ( $checks as $check ) {
			if ( isset( $layout[ $check ] ) ) {
				$content[ $check ] = $layout[ $check ];
			}
		}
		update_option( 'tcc_options_content', $content );
	}
}
