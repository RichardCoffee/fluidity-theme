<?php

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'fluid_page_top' ) ) {
	function fluid_page_top( $page_slug ) {
		if ( has_action( "fluid_page_top_$page_slug" ) ) {
			do_action( "fluid_page_top_$page_slug" );
		}
	}
	add_action( 'fluid_page_top', 'fluid_page_top' );
}

if ( ! function_exists( 'fluid_content_header' ) ) {
	function fluid_content_header() {
		$page_slug = get_page_slug();
		if ( has_action( "fluid_content_header_$page_slug" ) ) {
			do_action( "fluid_content_header_$page_slug" );
		}
	}
	add_action( 'fluid_content_header', 'fluid_content_header' );
}
