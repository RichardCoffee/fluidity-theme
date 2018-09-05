<?php
/**
 *  includes/cookies.php
 *
 *  This is intended for developmental use only
 *
 * @since 20170204
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/includes/cookies.php
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 */

if ( ! is_user_logged_in() ) { wp_clear_auth_cookie(); }

/**
 *  list the user's cookies
 *
 * @since 20170204
 * @link https://artiss.blog/2012/05/wordpress-function-to-list-site-cookies/
 * @param array  $atts
 * @param string $content
 * @return string
 */
if ( ! function_exists( 'fluid_list_cookies' ) ) {
	function fluid_list_cookies( $atts, $content = '' ) {
		$cookie  = $_COOKIE;
		ksort( $cookie );
		ob_start(); ?>
		<ul class="cookie-list"><?php
		foreach ( $cookie as $key => $val ) { ?>
			<li class="cookie-list-item"><?php
				e_esc_html( $key . ' : ' . $val ); ?>
			</li><?php
		} ?>
		</ul><?php
		return ob_get_clean();
	}
	add_shortcode( 'fluid_list_cookies', 'fluid_list_cookies' );
}
