<?php

/*
 * includes/menus.php
 *
 */

if (!function_exists('tcc_register_nav_menu')) {
  function tcc_register_nav_menu() {
    register_nav_menu('primary',__('Primary Menu','tcc-fluid'));
    register_nav_menu('header',__('Header Menu','tcc-fluid'));
    register_nav_menu('footer',__('Footer Menu','tcc-fluid'));
    log_entry("theme name: ".wp_get_theme());
  }
  add_action('after_setup_theme','tcc_register_nav_menu');
}
