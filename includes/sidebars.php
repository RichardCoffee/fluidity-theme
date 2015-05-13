<?php

//This sets up the widget area sidebars

if (!function_exists('register_fluid_sidebars')) {
  function register_fluid_sidebars() {

    $color    = tcc_color_scheme('sidebars');
    $b_widget = "<div class='panel panel-$color'>";
    $b_title  = "<div class='panel-heading'><h3 class='panel-title text-center'>";
    $a_title  = "</h3></div><div class='panel-body'>";
    $a_widget = "</div></div>";

    $sidebars = array();

    $sidebars[] = array('name'          => __('Standard Page Sidebar','tcc-fluid'),
                        'id'            => 'standard',
                        'before_widget' => $b_widget,
                        'before_title'  => $b_title,
                        'after_title'   => $a_title,
                        'after_widget'  => $a_widget);

    $sidebars[] = array('name'          => __('Front Page Sidebar','tcc-fluid'),
                        'id'            => 'front',
                        'before_widget' => $b_widget,
                        'before_title'  => $b_title,
                        'after_title'   => $a_title,
                        'after_widget'  => $a_widget);

    $sidebars[] = array('name'          => __('Horizontal Sidebar (3 col)','tcc-fluid'),
                        'id'            => 'three_column',
                        'before_widget' => "<div class='col-lg-4 col-md-4 col-sm-12 col-xs-12'>$b_widget",
                        'before_title'  => $b_title,
                        'after_title'   => $a_title,
                        'after_widget'  => "$a_widget</div>"); //*/

    $sidebars[] = array('name'          => __('Footer Widget Area (3 col)','tcc-fluid'),
                        'id'            => 'footer',
                        'before_widget' => "<div class='col-lg-4 col-md-4 col-sm-12 col-xs-12'>$b_widget",
                        'before_title'  => $b_title,
                        'after_title'   => $a_title,
                        'after_widget'  => "$a_widget</div>");

    $sidebars = apply_filters('tcc_register_sidebars',$sidebars);

    foreach($sidebars as $sidebar) {
      register_sidebar($sidebar);
    }

  }
}
register_fluid_sidebars();
?>
