<?php

/*
 *  File:  includes/options.php
 *
 */

if (!function_exists('stop_heartbeat')) {
  function stop_heartbeat() {
    $heart = tcc_settings('heart');
    if ($heart=='off') {
      global $pagenow;
      $authorized = array('post.php','post-new.php','admin.php');
      if (!in_array($pagenow,$authorized))
        add_action('admin_enqueue_scripts', function() { wp_deregister_script('heartbeat'); }, 999);
        add_action('wp_enqueue_scripts',    function() { wp_deregister_script('heartbeat'); }, 999);
        add_filter('heartbeat_settings', function ( $settings ) {
          $settings['minimalInterval'] = 600;
          $settings['autostart']       = false; // default is true
          $settings['interval']        = 600; // 15 sec by default
          return $settings; } );
    }
  }
  add_action( 'init', 'stop_heartbeat', 1 );
}

if (!function_exists('tcc_color_scheme')) {
  function tcc_color_scheme($location='') {
/*
 *    blue: primary
 *   green: success
 * lt blue: info
 *  orange: warning
 *     red: danger
 *   white: default
 */
    static $color = null;
    if (!$color) {
      $colors = array('primary','success','success-inverse','info','info-inverse','warning','warning-inverse','danger','danger-inverse');
      $index  = rand(0,count($colors)-1);
      $color  = $colors[$index];
    }
    return apply_filters('tcc_color_scheme',$color);
  }
}

if (!function_exists('tcc_currency_symbol')) {
  function tcc_currency_symbol() {
    $set = array('default'=>'$','group'=>'general','name'=>'currency_symbol','text'=>__('Currency Symbol','tcc-fluid'),'css'=>'small-text');
    $ins = new Admin_Field($set);
  }
  add_action('admin_init','tcc_currency_symbol',5);
}

if (!function_exists('tcc_design')) {
  function tcc_design($option) {
    static $data;
    if (empty($data)) { $data = get_option('tcc_options_design'); }
    if (isset($data[$option])) { return $data[$option]; }
    return '';
  }
}

if (!function_exists('tcc_font_size')) {
  function tcc_font_size() {
    $size = intval(tcc_design('size'),10);
    if (($size>0) || ($size=18)) { # set default value if needed
      echo "body { font-size: {$size}px }";
    }
  }
  add_action('tcc_custom_css','tcc_font_size');
}

if (!function_exists('tcc_layout')) {
  function tcc_layout($option) {
    static $data;
    if (empty($data)) { $data = get_option('tcc_options_layout'); }
    if (isset($data[$option])) { return $data[$option]; }
    return '';
  }
}

if (!function_exists('tcc_option')) {
  function tcc_option($option='',$section='') {
    if ($option) {
      if ($section) {
        $tcc_func = "tcc_$section";
        if (function_exists($tcc_func)) {
          $retval = $tcc_func($option);
        } else {
          $data = get_option("tcc_options_$section");
          if (isset($data[$option])) return $data[$option];
        }
      } else {
        $opts = TCC_Theme_Options_Values::options_menu_array();
        foreach($opts as $key=>$options) {
          foreach($options as $opt=>$layout) {
            if ($opt==$option) {
              $data = get_option("tcc_options_$key");
              return (isset($data[$option])) ? $data[$option] : $layout['default'];
            }
          }
        }

      }
    }
    return 'incompatible data';
  }
}

if (!function_exists('tcc_settings')) {
  function tcc_settings($option) {
    static $data;
    if (empty($data)) { $data = get_option('tcc_options_settings'); }
    if (isset($data[$option])) { return $data[$option]; }
    return '';
  }
}
