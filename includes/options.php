<?php

/*
 *  File:  includes/options.php
 *
 */

if ( ! function_exists( 'fluid_rest_dispatch_request' ) ) {
	function fluid_rest_dispatch_request( $result, $request, $route_schema, $handler ) {
		log_entry( $result, $request, $route_schema, $handler );
	}
	add_filter( 'rest_dispatch_request', 'fluid_rest_dispatch_request', 10, 4 );
}

if (!function_exists('fluid_stop_heartbeat')) {
  function fluid_stop_heartbeat() {
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
          $settings['interval']        = 600; // default is 15 sec
          return $settings; } );
    }
  }
  add_action( 'init', 'fluid_stop_heartbeat', 1 );
}

if (!function_exists('tcc_bootstrap')) {
	function tcc_bootstrap( $option ) {
		static $data;
		if (empty($data)) { $data = get_option('tcc_options_bootstrap'); }
		if (isset($data[$option])) { return $data[$option]; }
		return '';
	}
}

if (!function_exists('tcc_design')) {
  function tcc_design($option) {
    static $data;
    if (empty($data)) { $data = get_option('tcc_options_design'); }
    if (isset($data[$option])) { return $data[$option]; }
    return '';
  }
}

if ( ! function_exists( 'tcc_excerpt_length' ) ) {
	function tcc_excerpt_length( $length ) {
		$new = intval( tcc_option( 'exlength', 'content' ), 10 );
		return ( $new ) ? $new : $length;
	}
	add_filter( 'excerpt_length', 'tcc_excerpt_length', 999 );
}


if (!function_exists('tcc_font_size')) {
  function tcc_font_size() {
    $size = intval(tcc_design('size'),10);
    if (($size>0) || ($size=18)) { # set default value if needed
      echo "\nbody { font-size: {$size}px }\n";
    }
  }
  add_action('tcc_custom_css','tcc_font_size');
}

if ( ! function_exists( 'tcc_layout' ) ) {
	function tcc_layout( $option, $value = 'excerpt' ) {
		static $data;
		if ( empty( $data ) ) {
			$data = get_option( 'tcc_options_layout' );
		}
		if ( isset( $data[ $option ] ) ) {
			$value = $data[ $option ];
		}
		return $value;
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
				$opts = TCC_Options_Fluidity::instance()->get_options();
				foreach( $opts as $key => $options ) {
					if ( isset( $options[ $option ] ) ) {
						return $options[ $option ];
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
    if (empty($data)) { $data = get_option('tcc_options_admin'); }
    if (isset($data[$option])) { return $data[$option]; }
    return '';
  }
}
