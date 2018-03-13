<?php

/*
 *  includes/third-party.php
 *
 */


/**  bbPress  **/

#  bugfix from http://www.rewweb.co.uk/bbpress-wp4-fix2/
add_filter('bbp_show_lead_topic', '__return_true'); // FIXME:  has this bug been fixed yet?

if ( ! function_exists( 'fluidity_bbpress_font_size' ) ) {
	function fluidity_bbpress_font_size() {
		$fontsize = tcc_design( 'bbpsize' );
		if ( $fontsize && ( ! ( $fontsize === 12 ) ) ) {
			$css = array(
				'div#bbpress-forums',
				'div#bbpress-forums div.bbp-breadcrumb',
				'div#bbpress-forums ul.bbp-lead-topic',
				'div#bbpress-forums ul.bbp-topics',
				'div#bbpress-forums ul.bbp-forums',
				'div#bbpress-forums ul.bbp-replies',
				'div#bbpress-forums ul.bbp-search-results',
			);
			$css_tags = implode( ', ', $css );
			echo "$css_tags {";
			echo "	font-size:  {$fontsize}px;";
			echo "}";
		}
		$fontosize1 = tcc_design( 'bbposize1' );
		if ( $fontosize1 && ( ! ( $fontosize1 === 11 ) ) ) {
			$css = array(
				'div#bbpress-forums .bbp-forum-info .bbp-forum-content',
				'div#bbpress-forums p.bbp-topic-meta',
			);
			$css_tags = implode( ', ', $css );
			echo "$css_tags {";
			echo "	font-size:  {$fontosize1}px;";
			echo "}";
		}
	}
	add_action( 'tcc_custom_css', 'fluidity_bbpress_font_size' );
}


/**  BuddyPress  **/

if ( class_exists( 'BuddyPress' ) ) {
	function fluidity_buddypress_login_redirect( $redirect_to, $request, $user ) {
		$new_url = $redirect_to;
		if ( ! $user ) {
			$new_url = home_url();
		} else if ( ! is_object( $user ) ) {
			log_entry( 'user var is not an object', $user, 'stack' );
		} else if ( get_class( $user ) === 'WP_Error' ) {
			log_entry( 'user var is a WP_Error object', $user );
		} else {
			$user_name = $user->data->user_nicename;
			$new_url   = home_url( "members/$user_name/profile/" ) ;
log_entry(
	'   redirect_to:  ' . $redirect_to,
	'       request:  ' . $request,
	'wp_get_referer:  ' . wp_get_referer(),
	'       new url:  ' . $new_url,
	$user
);
		}
		return $new_url;
	}
	add_filter( 'login_redirect', 'fluidity_buddypress_login_redirect', 10, 3 );
} //*/


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

/**  mobble  **/

if ( ! function_exists( 'is_mobile' ) ) {
	function is_mobile() {
		return false;
	}
}


/**  S2member  **/

if ( class_exists( 'c_ws_plugin__s2member_login_redirects' ) ) {

	if ( ! function_exists( 'tcc_s2member_login_redirect' ) ) {

		if ( class_exists( 'BuddyPress' ) ) {

			function tcc_s2member_login_redirect( $redirect, $vars = array() ) {
				#log_entry( 's2member default redirect: ' . $redirect, $vars );
				if ( ! empty( $vars['user'] ) && ( $vars['user'] instanceof WP_User ) ) {
					$redirect = home_url( "members/{$vars['user']->data->user_nicename}/profile/" ); // FIXME:  this needs some sort of setting, source
					#log_entry("redirect:  $redirect");
					#	We have to manually do the redirect since S2 does not seem to want to...
					wp_safe_redirect($redirect);
					exit;
				}
				return $redirect;
			}

		} else {

			function tcc_s2member_login_redirect( $redirect, $vars = array() ) {
				#log_entry( 's2member default redirect: ' . $redirect, $vars );
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

if (function_exists('wpfai_social') && !function_exists('fluidity_wpfai_social')) {
	function fluidity_wpfai_social() {
#		$default    = 'twitter,facebook,google-plus,pinterest,linkedin';
#		$possible   = ''; // icons plugin provides
#		$fluid_social = tcc_social();
		$attributes = array(
			'icons'   => 'twitter,facebook,google-plus,pinterest,linkedin', // FIXME: can we assign list from options data?
			'shape'   => 'square',
			'inverse' => 'yes',
			'size'    => 'lg',
			'loadfa'  => 'no'
); ?>
    <div class="fluidity-social-icons"><?php
      echo wpfai_social($attributes); ?>
    </div><?php
  }
  add_action('fluidity_social_icons','fluid_wpfai_social');
}
