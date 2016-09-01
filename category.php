<?php

/*
 *  File Name:  category.php
 *
 */

get_header();

who_am_i();

add_action('fluid_category_page_title','fluid_category_page_title');
add_action('fluid_category_page_noposts','fluid_category_page_noposts');
fluid_index_page('category');

get_footer();
