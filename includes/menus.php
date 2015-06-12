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
$theme = strtolower(str_replace(' ','_',wp_get_theme()));
log_entry("theme name: $theme");
$theme_mods = get_option ( 'theme_mods_' . $theme );
log_entry('Theme Mods',$theme_mods);
$mods = get_theme_mods();
log_entry('theme mods',$mods);
  }
  add_action('after_setup_theme','tcc_register_nav_menu');
}
