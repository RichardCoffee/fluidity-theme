<?php

/*
 * includes/menus.php
 *
 */

if (!function_exists('tcc_register_nav_menu')) {
  function tcc_register_nav_menu() {
    $menus = array(array('slug'  => 'primary',
                         'title' => __('Primary Menu','tcc-fluid'),
                         'items' => array()),
                   array('slug'  => 'header',
                         'title' => __('Header Menu','tcc-fluid'),
                         'items' => array()),
                   array('slug'  => 'footer',
                         'title' => __('Footer Menu','tcc-fluid'),
                         'items' => array()));
    foreach($menus as $menu) {
      tcc_check_nav_menu($menu);
    }
$mods = get_theme_mods();
log_entry('theme mods',$mods);
  }
  add_action('after_setup_theme','tcc_register_nav_menu');
}

if (!function_exists('tcc_check_nav_menu')) {
  function tcc_check_nav_menu($menu) {
    if (!is_nav_menu($menu['slug'])) {
log_entry("menu not found at {$menu['slug']}");
    }
  }
}

/*function catapult_check_menus() {

 // If the Primary Menu doesn't exist, let's create it
 if ( ! is_nav_menu( 'primary-menu' ) ) {
 
  // Create menu
  $menu_id = wp_create_nav_menu ( 'Primary Menu' );
 
  // Add menu items
  wp_update_nav_menu_item ( $menu_id, 0, array(
   'menu-item-title' => __('Home'),
   'menu-item-url' => home_url( '/' ), 
   'menu-item-status' => 'publish' ) );

  wp_update_nav_menu_item ( $menu_id, 0, array (
   'menu-item-title' => __('Shop'),
   'menu-item-url' => home_url( '/shop/' ), 
   'menu-item-status' => 'publish' ) );
 
  // Lower case theme_name
  $theme = strtolower ( str_replace ( ' ', '_', wp_get_theme() ) );
 
  // Get the theme options
  $theme_mods = get_option ( 'theme_mods_' . $theme );
 
  // Set the location of the primary menu
  $theme_mods['nav_menu_locations'] = array ( 'primary-menu' => $menu_id );
 
  // Update the theme options
  update_option ( 'theme_mods_' . $theme, $theme_mods );
 
 }

}//*/
