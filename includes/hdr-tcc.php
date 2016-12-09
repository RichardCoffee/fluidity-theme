<?php

/*
 *  header for the creative collective
 *
 */

add_action('tcc_header_body_content', 'collective_top_menubar');
add_action('tcc_top_right_menubar',  'collective_bar_login');
add_action('tcc_top_right_menubar',  'fluidity_main_menubar');
add_action('tcc_top_left_menubar',   'fluidity_social_icons');
if (get_theme_mod('header_logo') || tcc_design('logo')) {
  add_action('tcc_top_left_menubar', 'collective_logo_limiter'); }
add_action('tcc_top_left_menubar',   'fluidity_menubar_print_button');

function collective_top_menubar() { ?>
  <div class="row color-<?php echo $color; ?>">
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"><?php
      do_action('tcc_top_left_menubar'); ?>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12"><?php
      do_action('tcc_top_right_menubar'); ?>
    </div>
  </div><?php
}

function collective_bar_login() { ?>
  <div class="row">
    <?php fluidity_header_bar_login(); ?>
  </div><?php
}

function collective_logo_limiter() { ?>
  <div class="tccollective_logo">
    <?php fluidity_header_logo(); ?>
  </div><?php
}

function tccollective_logo_limiter() {
  echo "
  .constr_logo_limiter {
    margin-right: 10%;
    margin-left: 10%;
  }\n";
}
#add_action('fluid_custom_css','tccollective_logo_limiter');
