<?php

/*
 *  includes/third-party.php
 *
 */


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
