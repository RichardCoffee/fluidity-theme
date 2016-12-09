<?php

/*
 *  header for rtcenterprises
 *
 */

#  Top menubar
#add_action('tcc_top_right_menubar',  'fluidity_header_bar_login');
add_action('tcc_header_body_content','rtc_header_content');
/*
#  Body
#if (function_exists('jetpack_the_site_logo') || get_theme_mod('header_logo') || tcc_design('logo')) {
if (get_theme_mod('header_logo') || tcc_design('logo')) {
  add_action('tcc_left_header_body', 'fluidity_header_logo');
  add_action('tcc_right_header_body','show_fluid_title');
} else {
  add_action('tcc_main_header_body','show_fluid_title');
} //*/

#  Bottom menubar
#add_action('tcc_header_menubar',     'fluidity_navbar_menu');
#add_action('fluidity_menubar',       'fluidity_menubar_print_button');

function rtc_header_content() { ?>
  <div class="col-lg-4 col-md-3 col-sm-12 hidden-xs">
    <?php fluidity_header_logo(); ?>
  </div>
  <div class="col-lg-8 col-md-9 col-sm-12 col-xs-12">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <?php fluidity_header_bar_login(); ?>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <?php get_template_part('template-parts/menu'); ?>
    </div>
  </div><?php
}
/*
function show_fluid_title() {
  $site  = "<a href='".home_url()."' title='Fluidity'>";
  $site .= get_bloginfo('name');
  $site .= "</a>";
  $title = __('The Creative Collective');
  $refer = "<a href='http://the-creative-collective.com' target='TCC' title='$title'>$title</a>"; ?>
  <h1 class='text-center'><?php
#    echo "$site currently under construction by $refer";
#    echo $title;
  echo $site;
 ?>
  </h1><?php
} //*/
