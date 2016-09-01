<?php
/*
 *  File Name: search.php
 *
 */

get_header();

who_am_i();

add_action('fluid_search_page_title','fluid_search_page_title');
add_action('fluid_search_page_noposts','fluid_search_page_noposts');
fluid_index_page('search');

get_footer(); ?>
