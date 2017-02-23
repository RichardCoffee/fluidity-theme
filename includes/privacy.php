<?php

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
