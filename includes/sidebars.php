<?php

//This sets up the widget area sidebars

if (!function_exists('fluidity_register_sidebars')) {
  function fluidity_register_sidebars() {
    $color = (is_admin()) ? 'primary' : tcc_color_scheme('sidebars');
    $before_widget = "<div class='panel panel-$color'>";
    $before_title  = "<div class='panel-heading'><h3 class='panel-title text-center'><b>";
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
                        'before_widget' => "<div class='col-lg-3 col-md-3 col-sm-6 col-xs-12'>$before_widget",
                        'before_title'  => $before_title,
                        'after_title'   => $after_title,
                        'after_widget'  => "$after_widget</div>");
    #  apply filters
    $sidebars = apply_filters('tcc_register_sidebars',$sidebars);
    foreach($sidebars as $sidebar) {
      register_sidebar($sidebar);
    }
  }
  add_action('widgets_init','fluidity_register_sidebars');
}

if (!function_exists('fluidity_get_sidebar')) {
  function fluidity_get_sidebar($sidebar='standard') {
    get_template_part('sidebar',$sidebar);
  }
}

if (!function_exists('fluidity_load_sidebar')) {
  function fluidity_load_sidebar($args) {
    $sidebars = (array)$args;
    foreach($sidebars as $sidebar) {
      if (is_active_sidebar($sidebar)) {
        if (dynamic_sidebar($sidebar)) {
          return true;
        } else { /*echo "<p>$sidebar non-dynamic</p>";*/ }
      } else { /*echo "<p>$sidebar not active</p>";*/ }
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

if (!function_exists('fluidity_load_sidebar')) {
  function fluidity_sidebar_side($sidebar) {
    return 'left';
  }
}
?>
