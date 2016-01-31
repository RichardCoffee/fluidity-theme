<?php

/*
 *  fluidity/includes/options.php
 *
 */

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
    return $color;
  }
}

call_non_existant_fake_function(); // cause error

if (!function_exists('tcc_currency_symbol')) {
  function tcc_currency_symbol() {
    $set = array('default'=>'$','group'=>'general','name'=>'currency_symbol','text'=>__('Currency Symbol','tcc-fluid'));
    $ins = new Admin_Field($set);
  }
  add_action('setup_theme','tcc_currency_symbol');
  log_entry('action defined for currency_symbol');
}

if (!function_exists('tcc_design')) {
  function tcc_design($option) {
    static $data;
    if (empty($data)) { $data = get_option('tcc_options_design'); }
    if (isset($data[$option])) {
      return $data[$option];
    } else if (function_exists('tcc_option')) {
      return tcc_option($option);
    }
    return '';
  }
}

if (!function_exists('tcc_layout')) {
  function tcc_layout($option) {
    static $data;
    if (empty($data)) { $data = get_option('tcc_options_layout'); }
    if (isset($data[$option])) {
      return $data[$option];
    } else if (function_exists('tcc_option')) {
      return tcc_option($option);
    }
    return '';
  }
}

