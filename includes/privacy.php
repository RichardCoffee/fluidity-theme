<?php

if ( ! function_exists( 'tcc_privacy' ) ) {
	function tcc_privacy( $option ) {
		static $data;
		$request = '';
		if ( empty( $data ) ) {
			$data = get_option( 'tcc_options_privacy' );
		}
		if ( isset( $data[ $option ] ) ) {
			$request = $data[ $option ];
		}
		return $request;
	}
}
