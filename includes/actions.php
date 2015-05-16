<?php

/*
 *  fluidity/includes/actions.php
 *
 */


/*  Header functions  */

if (!function_exists('fluidity_top_menu_bar')) {
  function fluidity_top_menu_bar() { ?>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"><?php
      do_action('tcc_top_left_header'); ?>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"><?php
      do_action('tcc_top_right_header'); ?>
    </div><?php
  }
  add_action('tcc_top_menu_bar','fluidity_top_menu_bar');
}

if (!function_exists('fluidity_header_bar_login')) {
  function fluidity_header_bar_login() { ?>
    <div class="header-login"><?php
      tcc_login_form(true,true); ?>
    </div><?php
  }
  add_action('tcc_top_right_header','fluidity_header_bar_login');
}

if (!function_exists('fluidity_main_header')) {
  function fluidity_main_header() { ?>
<h1 class='text-center'>This site currently under construction</h1>
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 logo"><?php
      do_action('tcc_left_header'); ?>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12"><?php
      do_action('tcc_right_header'); ?>
    </div><?php
  }
  add_action('tcc_main_header','fluidity_main_header');
}

if (!function_exists('header_logo')) {
  function header_logo() {
    $logo = tcc_design('logo');
    if ($logo) { ?>
      <a href="<?php echo home_url(); ?>/">
        <img class="img-responsive" src='<?php echo $logo; ?>' alt="<?php bloginfo('name'); ?>" >
      </a><?php
    }
  }
  add_action('tcc_left_header','header_logo');
}

if (!function_exists('fluidity_main_menubar')) {
  function fluidity_main_menubar() {
    get_template_part('template_parts/menu',tcc_layout('menu'));
  }
  add_action('tcc_main_menubar','fluidity_main_menubar');
}


/*  Footer Functions */

if (!function_exists('fluidity_footer')) {
  function fluidity_footer() {
    get_template_part('template_parts/footer',tcc_layout('footer'));
  }
  add_action('tcc_footer','fluidity_footer');
}
