<?php

/*
 *  fluidity/includes/options.php
 *
 */

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
    $colors = array('primary','success','success-inverse','info','info-inverse','warning','warning-inverse','danger','danger-inverse');
    if (!$color) {
      $index = rand(0,count($colors)-1);
      $color = $colors[$index];
    }
    return $color;
#    return 'success-inverse';
#    return 'primary';
#    return 'info';
#    return 'warning-inverse';
#    return 'danger';
  }
}
