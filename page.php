<?php
/*
 * File Name: page.php
 * Template Name: Standard Page
 *
 */

get_header();

log_entry(wp_get_theme()->get_page_templates());
fluid_index_page('page');

get_footer(); ?>
