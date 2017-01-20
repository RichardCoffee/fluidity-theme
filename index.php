<?php
/*
 *  File Name: index.php
 *
 */

get_header();

log_entry(wp_login_form(array('echo' => false)));

fluid_index_page(get_page_slug());

get_footer();
