<?php
/*
 * Template Name: Sidebar on Right (theme)
 * File Name: sidebar-right.php
 *
 */

get_header();

log_entry('in sidebar-right template');

define('TCC_RIGHT_SIDEBAR');
fluid_index_page(get_page_slug());

get_footer(); ?>
