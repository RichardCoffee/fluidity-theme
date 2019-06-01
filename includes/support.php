<?php
/**
 * includes/support.php
 *
 */
/**
 * control which options the theme supports
 *
 * @param array $args Contains which options can be supported.
 * @return array Should contain which options the theme is willing to support.
 */
if ( ! function_exists( 'fluid_load_theme_support' ) ) {
	function fluid_load_theme_support( $args = array() ) {
		#  Theme will not support these features
		$minus = array( 'custom-header', 'dark-editor-style' );
		return array_diff( $args, $minus );
	}
	add_filter( 'fluid_load_theme_support', 'fluid_load_theme_support' );
}

/**
 * add custom parameters for the theme's custom background and will abort the background support if on a mobile device
 *
 * @param array $args background array defaults
 * @return bool|array
 */
if ( ! function_exists( 'fluid_custom_background' ) ) {
	function fluid_custom_background( $args = array() ) {
		if ( fluid()->is_mobile() ) {
			return false;
		}
		$background = array(
#			'default-image'          => get_theme_file_uri( 'screenshot.jpg' ),
			'default-position-x'     => 'center',    // 'left',
			'default-size'           => 'cover',     // 'auto',
			'default-repeat'         => 'no-repeat', // 'repeat',
			'default-attachment'     => 'fixed',     // 'scroll',
		);
		return array_merge( $args, $background );
	}
	add_filter( 'fluid_support_custom_background', 'fluid_custom_background' );
}

/**
 * change logo css for bootstrap compatibility
 *
 * @link http://www.mavengang.com/2016/06/02/change-wordpress-custom-logo-class/
 * @param string $html
 * @return string
 */
if ( ! function_exists( 'fluid_change_custom_logo_class') ) {
	function fluid_change_custom_logo_class( $html ) {
		return str_replace( 'custom-logo-link', 'navbar-brand', $html );
	}
	add_filter( 'get_custom_logo', 'fluid_change_custom_logo_class' );
}

/**
 * designates what post formats the theme supports
 *
 * @param array $formats which formats can be supported
 * @return array which formats actually are supported
 */
if ( ! function_exists( 'fluid_post_formats' ) ) {
	function fluid_post_formats( $formats = array() ) {
		return array( 'gallery', 'image', 'link', 'quote' );
	}
	add_filter( 'fluid_support_post_formats', 'fluid_post_formats' );
}
