<?php

if ( ! function_exists( 'tcc_privacy' ) ) {
	function tcc_privacy( $option, $default = '' ) {
		static $data;
		if ( empty( $data ) ) {
			$data = get_option( 'tcc_options_privacy' );
		}
		if ( isset( $data[ $option ] ) ) {
			$default = $data[ $option ];
		}
		return $default;
	}
}
