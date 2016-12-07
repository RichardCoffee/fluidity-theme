<?php

if (!function_exists('fluid_color_scheme')) {
  function fluid_color_scheme() {
    $color = tcc_color_scheme();
    if (file_exists(get_template_directory() . "/css/colors/$color.css")) { return $color; }
    if (file_exists(get_stylesheet_directory()."/css/colors/$color.css")) { return $color; }
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
    static $color = 'primary';
    if (!$color) {
      $colors = array('primary','success','success-inverse','info','info-inverse','warning','warning-inverse','danger','danger-inverse');
      $index  = rand(0,count($colors)-1);
      $color  = $colors[$index];
    }
    return apply_filters('tcc_color_scheme',$color);
  }
}

#  function assumes calling function is wrapping with script tags
function tcc_custom_colors() {
  $colors = get_option('tcc_options_colors');
  if ($colors) {
    foreach($colors as $key=>$color) {
      if ((empty($color)) || ($color=='none')) continue;
      echo "$key { color: $color; }";
    }
  }
}
