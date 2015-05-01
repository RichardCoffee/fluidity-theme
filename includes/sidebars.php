<?php

//This sets up the widget area sidebars

if (!function_exists('register_fluid_sidebars')) {
  function register_fluid_sidebars() {
    $sidebars = array();
    $sidebars[] = array('name'          => __('Three Column Sidebar','tcc-fluid'),
                        'id'            => 'three_column',
                        'before_widget' => '<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">',
                        'after_widget'  => '</div>',
                        'before_title'  => '<h3 class="text-center">',
                        'after_title'   => '</h3>'); //*/
    $sidebars[] = array('name'          => __('Front Page Sidebar','tcc-fluid'),
                        'id'            => 'front',
                        'before_widget' => '<div class="sidebar-item">',
                        'after_widget'  => '</div>',
                        'before_title'  => '<h3 class="text-center">',
                        'after_title'   => '</h3>');
    $sidebars[] = array('name'          => __('Standard Page Sidebar','tcc-fluid'),
                        'id'            => 'standard',
                        'before_widget' => '<div class="sidebar-item">',
                        'after_widget'  => ' </div>',
                        'before_title'  => '<h3 class="text-center">',
                        'after_title'   => '</h3>');
    $sidebars[] = array('name'          => 'Footer Widget Area (3 col)',
                        'id'            => 'footer',
                        'before_widget' => '<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">',
                        'after_widget'  => '</div>',
                        'before_title'  => '<h4 class="text-center">',
                        'after_title'   => '</h4>');

    $sidebars = apply_filters('tcc_register_sidebars',$sidebars);
    foreach($sidebars as $sidebar) {
      register_sidebar($sidebar);
    }
  }
}
register_fluid_sidebars();
?>
