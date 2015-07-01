<?php

/*
 * includes/menus.php
 *
 * Sources: http://codex.wordpress.org/Navigation_Menus
 *          http://catapultdesign.co.uk/programmatically-create-menu-in-wordpress/
 *
 */

if (!function_exists('tcc_register_nav_menu')) {
  function tcc_register_nav_menu() {
    $menus = array('primary' => __('Primary Menu','tcc-fluid'),
                   'header'  => __('Header Menu','tcc-fluid'),
                   'footer'  => __('Footer Menu','tcc-fluid'));
    $items = array('primary' => array(array('menu-item-title'  => __('Home','tcc-fluid'),
                                            'menu-item-url'    => home_url('/'),
                                            'menu-item-status' => 'publish')),
                   'header'  => array(),
                   'footer'  => array());
    register_nav_menus($menus);
    foreach($menus as $key=>$title) {
      if (count($items[$key])==0) continue;
      if (has_nav_menu($title)) {
      } else {
        log_entry("$key menu not found");
        $menu_id = wp_create_nav_menu($title);
        if (is_wp_error($menu_id)) {
          log_entry("error creating menu $key",$menu_id);
          log_entry("registered menus",get_registered_nav_menus());
          log_entry("menu locations",get_nav_menu_locations());
        } else {
          foreach($items[$key] as $item) {
            $result = wp_update_nav_menu_item($menu_id,0,$item);
            if (is_wp_error($result)) {
              log_entry("Error creating $title item {$items[$key]['menu-item-title']}",$result);
            }
          }
          $locations = get_theme_mod('nav_menu_locations');
          $locations[$key] = $menu_id;
          set_theme_mod('nav_menu_locations',$locations);
        }
      }
    }
  }
  add_action('init','tcc_register_nav_menu');
}
