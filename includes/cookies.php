<?php

if ( ! function_exists( 'list_cookies' ) ) {
	#	https://artiss.blog/2012/05/wordpress-function-to-list-site-cookies/
	function list_cookies( $paras = '', $content = '' ) {
		$novalue   = ( strtolower( $paras[ 0 ] ) === 'novalue' ) ? true : false;
		$separator = ( empty( $content ) ) ? ' : ' : $content;
		$cookie    = $_COOKIE;
		ksort( $cookie );
		$content = '<ul class="cookie-list">';
		foreach ( $cookie as $key => $val ) {
			$content .= '<li class="cookie-list-item">' . $key;
			$content .= ( ! $novalue ) ? $separator . $val : '';
			$content .= "</li>";
		}
		$content .= "</ul>";
		return do_shortcode( $content );
	}
	call_user_func( 'add_shortcode', 'cookies', 'list_cookies' ); # FIXME: hack
}
