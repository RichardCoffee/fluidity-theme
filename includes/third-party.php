<?php

/*
 *  includes/third-party.php
 *
 */


/**  MailChimp for WordPress **/

if (!defined('FLUID_MC4WP_LOG_LEVEL')) { define('FLUID_MC4WP_LOG_LEVEL','info'); }
add_filter( 'mc4wp_debug_log_level', function() { return FLUID_MC4WP_LOG_LEVEL; } );  # possible values are: debug, info, warning, error(default)

if (!defined('FLUID_MC4WP_LOG_FILE')) { define('FLUID_MC4WP_LOG_FILE', WP_CONTENT_DIR . '/debug.log'); }
add_filter( 'mc4wp_debug_log_file', function( $file ) { return FLUID_MC4WP_LOG_FILE; } );  # default is the download directory (huh?)


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

