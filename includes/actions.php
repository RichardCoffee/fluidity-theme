<?php

if ( ! function_exists( 'fluid_content_header' ) ) {
	function fluid_content_header() {
		$page = get_page_slug();
		do_action( "fluid_content_header_$page" );
	}
	add_action( 'fluid_content_header', 'fluid_content_header' );
}
