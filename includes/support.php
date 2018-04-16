<?php

if ( ! function_exists( 'fluid_load_theme_support' ) ) {
	function fluid_load_theme_support( $args = array() ) {
		$minus = array( 'custom_header', 'custom_logo' ); //, 'editor_style' );
		$args = array_diff( $args, $minus );
		return $args;
	}
	add_filter( 'fluid_load_theme_support', 'fluid_load_theme_support' );
}

if ( ! function_exists( 'fluid_custom_background' ) ) {
	function fluid_custom_background( $args = array() ) {
		$background = array(
#			'default-image'          => get_theme_file_uri( 'screenshot.jpg' ),
			'default-position-x'     => 'center',    // 'left',
			'default-size'           => 'cover',     // 'auto',
			'default-repeat'         => 'no-repeat', // 'repeat',
			'default-attachment'     => 'fixed',     // 'scroll',
		);
		$args = array_merge( $args, $background );
		return $args;
	}
	add_filter( 'fluid_support_custom_background', 'fluid_custom_background' );
}

# for bootstrap compatibility
# http://www.mavengang.com/2016/06/02/change-wordpress-custom-logo-class/
if ( ! function_exists( 'fluid_change_custom_logo_class') ) {
	function fluid_change_custom_logo_class( $html ) {
		$html = str_replace( 'custom-logo-link', 'navbar-brand', $html );
		return $html;
	}
	add_filter( 'get_custom_logo', 'fluid_change_custom_logo_class' );
}

if ( ! function_exists( 'fluid_post_formats' ) ) {
	function fluid_post_formats( $formats = array() ) {
		return array( 'link', 'quote' );
	}
	add_filter( 'fluid_support_post_formats', 'fluid_post_formats' );
}
