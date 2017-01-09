<?php

if (!function_exists('fluid_archive_page_title')) {
	function fluid_archive_page_title() {
		the_archive_title( '<h1 class="page-title">', '</h1>' );
		the_archive_description( '<div class="taxonomy-description">', '</div>' );
	}
	add_action('fluid_archive_page_title','fluid_archive_page_title');
}

if (!function_exists('fluid_archive_page_noposts')) {
	function fluid_archive_page_noposts() {
		$text = esc_html__( 'Apologies, but no results were found for the requested Archive. Perhaps searching will help find a related post.','tcc-fluid' );
		fluid_noposts_page($text);
	}
	add_action('fluid_archive_page_noposts','fluid_archive_page_noposts');
}
