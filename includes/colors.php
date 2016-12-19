<?php

if (!function_exists('fluid_color_body_class')) {
	function fluid_color_body_class($classes) {
		$color = fluid_color_scheme();
		if ($color) {
		$classes[] = "fluid-color-$color";
		}
		return $classes;
	}
	add_filter('body_class','fluid_color_body_class');
}

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
    static $color; // = 'danger-inverse';
    if (!$color) {
      $colors = array('primary','success','success-inverse','info','info-inverse','warning','warning-inverse','danger','danger-inverse','fire-engine');
      $index  = rand(0,count($colors)-1);
      $color  = $colors[$index];
    }
    return apply_filters('tcc_color_scheme',$color);
  }
}

#  function assumes calling function is wrapping with css script tags
function tcc_custom_colors() {
  $colors = get_option('tcc_options_colors');
  if ($colors) {
    foreach($colors as $key=>$color) {
      if ((empty($color)) || ($color=='none')) continue;
      echo "$key { color: $color; }";
    }
  }
}

if (!function_exists('tcc_parallax')) {
	function tcc_parallax() {
		$paras = get_option('tcc_options_parallax');
log_entry(0,$paras);
		if ($paras) {
			$string = '.para-img-%1$s { background-image: url("%2$s"); height:400px; }';
			foreach($paras as $page=>$para) {
				echo sprintf($string,$key,$para);
			}
		}
	}
log_entry('paral: '.tcc_design('paral'));
	if (tcc_design('paral')==='yes') {
		add_action('tcc_custom_css','tcc_parallax');
	}
}
