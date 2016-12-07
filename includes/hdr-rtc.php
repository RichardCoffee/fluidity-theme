<?php

/*
 *  header for rtcenterprises
 *
 */

#  Top menubar
add_action('tcc_header_top_menubar', 'fluidity_top_menubar');
add_action('tcc_top_right_menubar',  'fluidity_header_bar_login');
add_action('tcc_header_body_content','fluidity_header_body');

#  Body
#if (function_exists('jetpack_the_site_logo') || get_theme_mod('header_logo') || tcc_design('logo')) {
if (get_theme_mod('header_logo') || tcc_design('logo')) {
  add_action('tcc_left_header_body', 'fluidity_header_logo');
  add_action('tcc_right_header_body','show_fluid_title');
} else {
  add_action('tcc_main_header_body','show_fluid_title');
} //*/

#  Bottom menubar
add_action('tcc_header_menubar',     'fluidity_main_menubar');
add_action('fluidity_menubar',       'fluidity_menubar_print_button');

function show_fluid_title() {
  $site  = "<a href='".home_url()."' title='Fluidity'>";
  $site .= get_bloginfo('name');
  $site .= "</a>";
  $title = __('The Creative Collective');
  $refer = "<a href='http://the-creative-collective.com' target='TCC' title='$title'>$title</a>"; ?>
  <h1 class='text-center'><?php
    #echo "$site currently under construction by $refer";
    echo $title; ?>
  </h1><?php
} //*/
