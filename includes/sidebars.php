<?php

//This sets up the widget area sidebars

if (!function_exists('fluidity_register_sidebars')) {
  function fluidity_register_sidebars() {
    $color = (is_admin()) ? 'primary' : tcc_color_scheme('sidebars');
    $before_widget = "<div class='panel panel-$color'>";
    $before_title  = "<div class='panel-heading'";
    $before_title .= (tcc_layout('widget')=='closed') ? " data-collapse='1'>" : ">";
    $before_title .= "<i class='fa fa-plus pull-right panel-sign'></i>";
    $before_title .= "<h3 class='panel-title text-center scroll-this pointer'><b>";
    $after_title   = "</b></h3></div><div class='panel-body'>";
    $after_widget  = "</div></div>";
    $sidebars   = array();
    #  Standard Page
    $sidebars[] = array('name'          => __('Standard Page Sidebar','tcc-fluid'),
                        'id'            => 'standard',
                        'before_widget' => $before_widget,
                        'before_title'  => $before_title,
                        'after_title'   => $after_title,
                        'after_widget'  => $after_widget);
    #  Home Page
    $sidebars[] = array('name'          => __('Home Page Sidebar','tcc-fluid'),
                        'id'            => 'home',
                        'before_widget' => $before_widget,
                        'before_title'  => $before_title,
                        'after_title'   => $after_title,
                        'after_widget'  => $after_widget);
    #  Header sidebar
    $sidebars[] = array('name'          => __('Horizontal Sidebar (3 col)','tcc-fluid'),
                        'id'            => 'three_column',
                        'before_widget' => "<div class='col-lg-4 col-md-4 col-sm-12 col-xs-12'>$before_widget",
                        'before_title'  => $before_title,
                        'after_title'   => $after_title,
                        'after_widget'  => "$after_widget</div>"); //*/
    #  Footer sidebar
    $sidebars[] = array('name'          => __('Footer Widget Area (4 col)','tcc-fluid'),
                        'id'            => 'footer',
                        'before_widget' => "<div class='col-lg-3 col-md-3 col-sm-6 col-xs-12'><div class='panel panel-$color'><div class='panel-body'>",
                        'before_title'  => '',
                        'after_title'   => '',
                        'after_widget'  => "</div></div></div>");
    #  apply filters
    $sidebars = apply_filters('tcc_register_sidebars',$sidebars);
    foreach($sidebars as $sidebar) {
      register_sidebar($sidebar);
    }
  }
  add_action('widgets_init','fluidity_register_sidebars');
}

function fluidity_the_widget($widget,$instance,$args) {
  log_entry('the widget',$widget,$instance,$args);
}
add_action('the_widget','fluidity_the_widget',999,3);
/*
if (!function_exists('fluidity_sidebar_param_filter')) {
  function fluidity_sidebar_param_filter($params) {
    
  }
  add_filter('dynamic_sidebar_params','fluidity_sidebar_param_filter');
} //*/

if (!function_exists('fluidity_get_sidebar')) {
  function fluidity_get_sidebar($sidebar='standard') {
    get_template_part('sidebar',$sidebar);
  }
}

if (!function_exists('fluidity_load_sidebar')) {
  function fluidity_load_sidebar($args) {
    $sidebars = (array)$args;
    $sidebars[] = 'standard';
    $sidebars[] = 'home';
    foreach($sidebars as $sidebar) {
      if (is_active_sidebar($sidebar)) {
        if (dynamic_sidebar($sidebar)) {
          return true;
        } else { /*echo "<p>$sidebar non-dynamic</p>";*/ }
      } else {   /*echo "<p>$sidebar not active</p>";*/ }
    }
    return false;
  }
}

if (!function_exists('fluidity_sidebar_parameter')) {
  function fluidity_sidebar_parameter() {
    $trace = debug_backtrace();
    foreach($trace as $item) {
      if ($item['function']=='fluidity_get_sidebar') {
        return $item['args'][0];
      }
    }
    return '';
  }
}

if (!function_exists('sidebar_layout')) {
  function sidebar_layout($sidebar='standard',$side='') {
    $side = ($side) ? $side : tcc_layout('sidebar');
    if ($side!=='none') {
      $micro = microdata();
      $sidebar_class = 'col-lg-4 col-md-4 col-sm-12 col-xs-12'.(($side=='right') ? ' pull-right' : ''); ?>
      <aside class="<? echo $sidebar_class; ?>" <?php $micro->WPSideBar(); ?> role="complementary"><?php
        fluidity_get_sidebar($sidebar); ?>
      </aside><?php
    }
  }
}
