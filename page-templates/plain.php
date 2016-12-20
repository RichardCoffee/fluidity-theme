<?php
/*
 * Template Name: No Sidebar (theme)
 * File Name: plain.php
 *
 */

get_header();

log_entry('in plain template');

define('TCC_NO_SIDEBAR');
fluid_index_page(get_page_slug());

get_footer(); ?>
