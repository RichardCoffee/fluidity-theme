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
    $theme_slug = strtolower(str_replace(' ','_',wp_get_theme()));
    $theme_mods = get_option('theme_mods_'.$theme_slug);
    $updated    = false;
    foreach($menus as $key=>$title) {
      if (count($items[$key])==0) continue;
      if (!is_nav_menu($key)) {
#log_entry("menu slug: $key");
        $menu_id = wp_create_nav_menu($title);
        if ($menu_id===(int)$menu_id) {
          foreach($items[$key] as $item) {
            $result = wp_update_nav_menu_item($menu_id,0,$item);
            if (!$result===(int)$result) {
              log_entry("Error creating $title item {$items[$key]['menu-item-title']}",$result);
              return;
            }
          }
#        if (!isset($theme_mods['nav_menu_locations']))
#          $theme_mods['nav_menu_locations'] = array();
#        if (!isset($theme_mods['nav_menu_locations'][$key])) {
#          $theme_mods['nav_menu_locations'][$key] = $menu_id;
#          $updated = true;
#        }
        } else {
          log_entry("Error creating $title",$menu_id);
        }
      }
    }
    if ($updated) update_option('theme_mods_'.$theme_slug,$theme_mods);
  }
  add_action('init','tcc_register_nav_menu');
}
