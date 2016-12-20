<?php
/*
 * Template Name: Sidebar on Left (theme)
 * File Name: sidebar-left.php
 *
 */

get_header();

log_entry('in sidebar-left template');

define('TCC_LEFT_SIDEBAR');
fluid_index_page(get_page_slug());

get_footer(); ?>
