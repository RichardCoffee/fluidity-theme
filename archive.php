<?php
/*
 *  File Name: archive.php
 *
 */

get_header();

who_am_i();

add_action('fluid_archive_page_title','fluid_archive_page_title');
add_action('fluid_archive_page_noposts','fluid_archive_page_noposts');
fluid_index_page('archive');

get_footer(); ?>
