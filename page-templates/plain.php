<?php
/*
 * Template Name: No Sidebar (theme)
 * File Name: plain.php
 *
 */

get_header();

define('TCC_NO_SIDEBAR',true);

fluid_index_page(get_page_slug());

get_footer(); ?>
