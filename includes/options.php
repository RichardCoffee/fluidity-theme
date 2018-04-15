<?php
/*
 *  File:  includes/options.php
 *
 */
/*
if ( ! function_exists( 'fluid_rest_dispatch_request' ) ) {
	function fluid_rest_dispatch_request( $result ) {
#		log_entry( func_get_args() );
#		log_entry( 'stack' );
#		log_entry( $_SERVER );
#		log_entry( $result );
		$filter = current_filter();
log_entry(0,$filter);
		return $result;
	}
#	add_filter( 'rest_dispatch_request',      'fluid_rest_dispatch_request', 10, 4 );
	add_filter( 'rest_authentication_errors', 'fluid_rest_dispatch_request', 20 );
	add_filter( 'rest_send_nocache_headers', 'fluid_rest_dispatch_request' );
	add_filter( 'rest_jsonp_enabled', 'fluid_rest_dispatch_request' );
	add_filter( 'rest_post_dispatch', 'fluid_rest_dispatch_request' );
	add_filter( 'rest_pre_serve_request', 'fluid_rest_dispatch_request' );
	add_filter( 'rest_post_dispatch', 'fluid_rest_dispatch_request' );
	add_filter( 'rest_envelope_response', 'fluid_rest_dispatch_request' );
	add_filter( 'rest_endpoints', 'fluid_rest_dispatch_request' );
	add_filter( 'rest_pre_dispatch', 'fluid_rest_dispatch_request' );
	add_filter( 'rest_request_before_callbacks', 'fluid_rest_dispatch_request' );
	add_filter( 'rest_dispatch_request', 'fluid_rest_dispatch_request' );
	add_filter( 'rest_request_after_callbacks', 'fluid_rest_dispatch_request' );
	add_filter( 'rest_index', 'fluid_rest_dispatch_request' );
	add_filter( 'rest_namespace_index', 'fluid_rest_dispatch_request' );
	add_filter( 'rest_endpoints_description', 'fluid_rest_dispatch_request' );
	add_filter( 'rest_route_data', 'fluid_rest_dispatch_request' );
	add_filter( 'rest_endpoints', 'fluid_rest_dispatch_request' );
	add_filter( 'rest_endpoints', 'fluid_rest_dispatch_request' );
} //*/

add_action( 'wp_enqueue_scripts', array( 'TCC_Options_Typography', 'load_google_font' ) );
add_action( 'tcc_custom_css',     array( 'TCC_Options_Typography', 'typography_styles' ), 1 );

if ( ! function_exists( 'fluid_stop_heartbeat' ) ) {
	function fluid_stop_heartbeat() {
		$heart = tcc_settings( 'heart', 'on' );
		if ( $heart == 'off' ) {
			global $pagenow;
			$authorized = array( 'post.php', 'post-new.php', 'admin.php' );
			if ( ! in_array( $pagenow, $authorized ) ) {
				add_action( 'admin_enqueue_scripts', function() { wp_deregister_script( 'heartbeat' ); }, 999);
				add_action( 'wp_enqueue_scripts',    function() { wp_deregister_script( 'heartbeat' ); }, 999);
				add_filter( 'heartbeat_settings', function ( $settings ) {
					$settings['minimalInterval'] = 600;
					$settings['autostart']       = false; // default is true
					$settings['interval']        = 600; // default is 15 sec
					return $settings;
				} );
			}
		}
	}
	add_action( 'init', 'fluid_stop_heartbeat', 1 );
}

if ( ! function_exists( 'fluid_load_post_classes_admin' ) ) {
	function fluid_load_post_classes_admin() {
		global $pagenow;
		if ( in_array( $pagenow, array( 'post.php', 'post-new.php' ), true ) ) {
			new TCC_MetaBox_GalleryView( array( 'type' => 'post' ) );
			new TCC_MetaBox_PostDate( array( 'type' => 'post' ) );
		}
	}
	add_action( 'admin_init', 'fluid_load_post_classes_admin' );
}

if ( ! function_exists( 'fluid_load_post_classes' ) ) {
	function fluid_load_post_classes() {
		if ( is_page() || is_single() ) {
			new TCC_MetaBox_GalleryView( array( 'type' => 'post' ) );
		}
	}
	add_action( 'init', 'fluid_load_post_classes' );
}

if (!function_exists('tcc_bootstrap')) {
	function tcc_bootstrap( $option ) {
		static $data;
		if (empty($data)) { $data = get_option('tcc_options_bootstrap'); }
		if (isset($data[$option])) { return $data[$option]; }
		return '';
	}
}

if ( ! function_exists( 'tcc_content' ) ) {
	function tcc_content( $option, $default = '' ) {
		static $data;
		if ( empty( $data ) ) { $data = get_option( 'tcc_options_content' ); }
		if ( isset( $data[ $option ] ) ) { return $data[ $option ]; }
		return $default;
	}
}

if ( ! function_exists( 'tcc_design' ) ) {
	function tcc_design( $option, $default = '' ) {
		static $data;
		if ( empty( $data ) ) { $data = get_option( 'tcc_options_design' ); }
		if ( isset( $data[ $option ] ) ) { return $data[ $option ]; }
		return $default;
	}
}

if ( ! function_exists( 'tcc_excerpt_length' ) ) {
	function tcc_excerpt_length( $length ) {
		$stored  = tcc_option( 'exlength', 'content', 55 );
		$calced  = intval( $stored, 10 );
		return ( $calced ) ? $calced : $length;
	}
	add_filter( 'excerpt_length', 'tcc_excerpt_length', 999 );
}

if ( ! function_exists( 'tcc_layout' ) ) {
	function tcc_layout( $option, $value = '' ) {
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

if ( ! function_exists( 'tcc_option' ) ) {
	function tcc_option( $option, $section = '', $value = 'incompatible data' ) {
		if ( $section ) {
			$tcc_func = "tcc_$section";
			if ( function_exists( $tcc_func ) ) {
				$value = $tcc_func( $option, $value );
			} else {
				$data = get_option( "tcc_options_$section" );
				if ( isset( $data[ $option ] ) ) {
					$value = $data[ $option ];
				}
			}
		} else {
			$opts = fluid_options()->get_options();
			foreach( $opts as $key => $options ) {
				if ( isset( $options[ $option ] ) ) {
					$value = $options[ $option ];
					break;
				}
			}
		}
		return $value;
	}
}

if ( ! function_exists( 'tcc_settings' ) ) {
	function tcc_settings( $option, $default = '' ) {
		static $data;
		if ( empty( $data ) ) { $data = get_option( 'tcc_options_admin' ); }
		if ( isset( $data[ $option ] ) ) { return $data[ $option ]; }
		return $default;
	}
}
