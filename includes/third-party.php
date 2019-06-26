<?php
/**
 *  includes/third-party.php
 *
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 */


/**  bbPress  **/

if ( function_exists( 'is_bbpress' ) ) {
	require_once( FLUIDITY_HOME . 'includes/bbpress.php' );
}


/**  BuddyPress  **/

if ( class_exists( 'BuddyPress' ) ) {
	require_once( FLUIDITY_HOME . 'includes/buddypress.php' );
}


/** Contact Form 7 **/
if ( ( ! defined( 'WPCF7_USE_REALLY_SIMPLE_CAPTCHA' ) ) && class_exists( 'ReallySimpleCaptcha' ) {
	define( 'WPCF7_USE_REALLY_SIMPLE_CAPTCHA', true );
}


/** Essential Real Estate **/
if ( function_exists( 'ere_get_option' ) && ( ! function_exists( 'fluidity_ere_currency_symbol' ) ) ) {
	function fluidity_ere_currency_symbol( $args ) {
		$symbol = ere_get_option( 'currency_sign', $args['field_default'] );
		return $args;
	}
	add_filter( 'fluid_currency_symbol', 'fluidity_ere_currency_symbol' );
}


/**  Jetpack  **/

/*  jetpack_the_site_logo() called in includes/header.php, function fluidity_header_logo() */
if (!function_exists('fluidity_jetpack_site_logo_to_bootstrap')) {
  function fluidity_jetpack_site_logo_to_bootstrap($html) {
    // FIXME:  replace first instance of 'site-logo' with 'logo site-logo'.  Actual result should be 'logo site-logo-link'
    // FIXME:  replace second occurance of 'site-logo' with 'img-responsive site-logo'
    return $html;
  }
  add_filter('jetpack_the_site_logo','fluidity_jetpack_site_logo_to_bootstrap');
}


/**  MailChimp for WordPress **/

# FIXME: check for active plugin
if ( ! defined( 'FLUID_MC4WP_LOG_LEVEL' ) ) {
	define( 'FLUID_MC4WP_LOG_LEVEL', 'info' );  # possible values are: debug, info, warning, error(default)
}
add_filter( 'mc4wp_debug_log_level', function() {
	return FLUID_MC4WP_LOG_LEVEL;
} );

if ( ! defined( 'FLUID_MC4WP_LOG_FILE' ) ) {
	define( 'FLUID_MC4WP_LOG_FILE', WP_CONTENT_DIR . '/debug.log' );  # default is the download directory (huh?)
}
add_filter( 'mc4wp_debug_log_file', function( $file ) {
	return FLUID_MC4WP_LOG_FILE;
} );


/**  S2member  **/

if ( class_exists( 'c_ws_plugin__s2member_login_redirects' ) ) {

	if ( ! function_exists( 'tcc_s2member_login_redirect' ) ) {

		if ( class_exists( 'BuddyPress' ) ) {

			function tcc_s2member_login_redirect( $redirect, $vars = array() ) {
				#fluid()->log( 's2member default redirect: ' . $redirect, $vars );
				if ( ! empty( $vars['user'] ) && ( $vars['user'] instanceof WP_User ) ) {
					$redirect = home_url( "members/{$vars['user']->data->user_nicename}/profile/" ); // FIXME:  this needs some sort of setting, source
					#fluid()->log("redirect:  $redirect");
					#	We have to manually do the redirect since S2 does not seem to want to...
					wp_safe_redirect($redirect);
					exit;
				}
				return $redirect;
			}

		} else {

			function tcc_s2member_login_redirect( $redirect, $vars = array() ) {
				#fluid()->log( 's2member default redirect: ' . $redirect, $vars );
				// If you want s2Member to perform the redirect, return true.
				// Or, if you do NOT want s2Member to perform the redirect, return false.
				$redirect = false;
				// Or, if you want s2Member to redirect, but to a custom URL, return that URL.
				// Or, just return what s2Member already says about the matter.
				return $redirect;
			}
		}
		add_filter("ws_plugin__s2member_login_redirect", "tcc_s2member_login_redirect", 10, 2);
	}

/*	function tcc_s2member_security_badge() {
		echo do_shortcode('[s2Member-Security-Badge v="3" /]');
	}
	add_action('tcc_copyright_right','tcc_s2member_security_badge'); //*/

	add_filter("ws_plugin__s2member_lazy_load_css_js", "__return_true");

}

/**  WooCommerce  **/

if (!function_exists('has_woocommerce')) {
  function has_woocommerce() {
    return class_exists('woocommerce');
  }
}

if (has_woocommerce()) {

  add_theme_support('woocommerce');

  #  function sharing_display is part of Jetpack's sharedaddy
  if (function_exists('sharing_display') && !function_exists('woocommerce_and_jetpack_sharedaddy')) {
    function woocommerce_and_jetpack_sharedaddy() { ?>
      <div class="social"><?php echo sharing_display(); ?></div><?php
    }
    add_action('woocommerce_share','woocommerce_and_jetpack_sharedaddy');
  }

}

/**  WP Font Awesome Share Icons  **/

if ( function_exists( 'wpfai_social' ) && ( ! function_exists('fluidity_wpfai_social' ) ) ) {
	function fluidity_wpfai_social( $args = array() ) {
		$defaults = array(
			'icons'   => 'twitter,facebook,google-plus,pinterest,linkedin', // FIXME: can we assign list from options data?
			'shape'   => 'square',
			'inverse' => 'yes',
			'size'    => 'lg',
			'loadfa'  => 'no'
		);
		$attributes = array_merge( $defaults, $args ); ?>
		<div class="social-icons"><?php
			echo wpfai_social( $attributes ); ?>
		</div><?php
	}
	add_action( 'fluidity_social_icons', 'fluid_wpfai_social' );
}

/** WP Frontend Profile **/

if ( function_exists( 'wpfep_show_profile' ) ) {

	if ( ! function_exists( 'fluid_wpfep_get_edit_user_url' ) ) {
		function fluid_wpfep_get_edit_user_url( $url, $user_id, $scheme ) {
			if ( page_exists( 'Your Profile' ) ) {
				return esc_url_raw( home_url( '/your-profile' ) );
			}
			return $url;
		}
		add_filter( 'edit_profile_url', 'fluid_wpfep_get_edit_user_url', 11, 3 );
	}

	/**
	 *  handle fluid sidebar for profile page
	 *
	 * @since 20180812
	 */
	if ( ! function_exists( 'fluid_wpfep_fluid_sidebar_css' ) ) {
		function fluid_wpfep_fluid_sidebar_css() {
			echo ".wpfep-wrapper {\n\tmax-width: 73%;\n}\n";
		}
	}
	add_action( 'fluidity_sidebar_fluid_styling', 'fluid_wpfep_fluid_sidebar_css' );

}
