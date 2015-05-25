<?php

/*
 *  tcc-fluidity/includes/admin.php
 *
 */

function admin_menu_setup() {
    $title = __('Theme Options','tcc-fluid');
    $about = get_option('tcc_options_about');

    if (!$about) $about = TCC_Theme_Options_Values::options_defaults('about');

    $menu_cap  = 'manage_options';

    $menu_func = array(__CLASS__,'render_admin');

    if ($about['loca']=='appearance') {

      add_theme_page($title,$title,$menu_cap,self::$menu_slug,$menu_func);

    } else if ($about['loca']=='settings') {

      add_options_page($title,$title,$menu_cap,self::$menu_slug,$menu_func);

    } else {

      $icon_name = 'dashicons-admin-settings';

      $priority  = ($about['wp_posi']=='top') ? '1.1' : '99.9122473024';

      add_menu_page($title,$title,$menu_cap,self::$menu_slug,$menu_func,$icon_name,$priority);

    }

    do_action('tcc_admin_menu_setup');

  }


add_action('admin_menu',       array('TCC_Theme_Options_Admin',   'admin_menu_setup'));

add_action('admin_init',       array('TCC_Theme_Options_Admin',   'initialize_admin'));

add_action('tcc_admin_enqueue',array('TCC_Theme_Options_Admin',   'load_admin_scripts'));
