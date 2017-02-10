<?php

/*
 *  includes/third-party.php
 *
 */


/**  bbPress  **/

#  bugfix from http://www.rewweb.co.uk/bbpress-wp4-fix2/
add_filter('bbp_show_lead_topic', '__return_true'); // FIXME:  has this bug been fixed yet?


/**  BuddyPress  **/

if ( class_exists( 'BuddyPress' ) ) {
	function fluidity_buddypress_login_redirect( $redirect_to, $request, $user ) {
		$new_url = $redirect_to;
		if ( ! $user ) {
			$new_url = home_url();
		} else if ( ! is_object( $user ) ) {
			log_entry( 'user var is not an object', $user, 'dump' );
#			$new_url = $redirect_to;
		} else if ( get_class( $user ) === 'WP_Error' ) {
#			$new_url = $redirect_to;
		} else {
			$user_name = $user->data->user_nicename;
			$new_url   = home_url( "members/$user_name/profile/" ) ;
log_entry(
	'   redirect_to:  ' . $redirect_to,
	'       request:  ' . $request,
	'wp_get_referer:  ' . wp_get_referer(),
	"       new url:  $new_url",
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

if (!defined('FLUID_MC4WP_LOG_LEVEL')) { define('FLUID_MC4WP_LOG_LEVEL','info'); }
add_filter( 'mc4wp_debug_log_level', function() { return FLUID_MC4WP_LOG_LEVEL; } );  # possible values are: debug, info, warning, error(default)

if (!defined('FLUID_MC4WP_LOG_FILE')) { define('FLUID_MC4WP_LOG_FILE', WP_CONTENT_DIR . '/debug.log'); }
add_filter( 'mc4wp_debug_log_file', function( $file ) { return FLUID_MC4WP_LOG_FILE; } );  # default is the download directory (huh?)


/**  S2member  **/

if (class_exists('c_ws_plugin__s2member_login_redirects') && !function_exists('tcc_s2member_login_redirect')) {
	function tcc_s2member_login_redirect($redirect, $vars = array()) {
log_entry('s2member default redirect: '.$redirect,$vars);
		// If you want s2Member to perform the redirect, return true.
		// return true;

		// Or, if you do NOT want s2Member to perform the redirect, return false.
		return false;

		// Or, if you want s2Member to redirect, but to a custom URL, return that URL.
		// return 'http://www.example.com/reset-password-please/';

		// Or, just return what s2Member already says about the matter.
		// return $redirect;
	}
	add_filter("ws_plugin__s2member_login_redirect", "tcc_s2member_login_redirect", 10, 2);
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
    $attributes = array('icons' => 'twitter,facebook,google-plus,pinterest,linkedin', // FIXME: can we assign list from options data?
                        'shape' => 'square', 'inverse' => 'yes', 'size' => 'lg', 'loadfa' => 'no'); ?>
    <div class="fluidity-social-icons"><?php
      echo wpfai_social($attributes); ?>
    </div><?php
  }
  add_action('fluidity_social_icons','fluid_wpfai_social');
}
