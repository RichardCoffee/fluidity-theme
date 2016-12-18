<?php

//This sets up the widget area sidebars

if (!function_exists('fluidity_register_sidebars')) {
  function fluidity_register_sidebars() {
    $widget = tcc_layout('widget');
    $before_widget = "<div class='panel panel-fluidity'>";
    $before_title  = "<div class='panel-heading'";
    $before_title .= ($widget==='closed') ? " data-collapse='1'>" : ">";
    $fa_sign       = ($widget==='open')   ? "fa-minus" : "fa-plus";
    $before_title .= ($widget==='perm')   ? "" : "<i class='fa $fa_sign pull-right panel-sign'></i>";
    $before_title .= "<h3 class='panel-title text-center scroll-this pointer'><b>";
    $after_title   = "</b></h3></div><div class='panel-body'>";
    $after_widget  = "</div></div>";
    #$before_widget = apply_filters('tcc_before_widget',$before_widget);
    #$before_title  = apply_filters('tcc_before_title', $before_title);
    #$after_title   = apply_filters('tcc_after_title',  $after_title);
    #$after_widget  = apply_filters('tcc_after_widget', $after_widget);
    $sidebars   = array();
    #  Standard Page
    $sidebars['standard'] = array('name'=> esc_html__('Standard Page Sidebar','tcc-fluid'),
                        'id'            => 'standard',
                        'before_widget' => $before_widget,
                        'before_title'  => $before_title,
                        'after_title'   => $after_title,
                        'after_widget'  => $after_widget);
    #  Home Page
    $sidebars['home'] = array('name'    => esc_html__('Home Page Sidebar','tcc-fluid'),
                        'id'            => 'home',
                        'before_widget' => $before_widget,
                        'before_title'  => $before_title,
                        'after_title'   => $after_title,
                        'after_widget'  => $after_widget);
    #  Header sidebar
    $sidebars['three'] = array('name'   => esc_html__('Horizontal Sidebar (3 col)','tcc-fluid'),
                        'id'            => 'three_column',
                        'before_widget' => "<div class='col-lg-4 col-md-4 col-sm-12 col-xs-12'>$before_widget",
                        'before_title'  => $before_title,
                        'after_title'   => $after_title,
                        'after_widget'  => "$after_widget</div>"); //*/
    #  Footer sidebar
    $sidebars['four'] = array('name'    => esc_html__('Footer Widget Area (4 col)','tcc-fluid'),
                        'id'            => 'footer4',
                        'before_widget' => "<div class='col-lg-3 col-md-3 col-sm-6 col-xs-12'><div class='panel panel-fluidity'><div class='panel-body'>",
                        'before_title'  => '',
                        'after_title'   => '',
                        'after_widget'  => "</div></div></div>");
	 #  Footer sidebar
    $f2_before = "<div class='col-lg-6 col-md-6 col-sm-6 col-xs-12'><div class='panel panel-fluidity'><div class='panel-body back-fluidity'>";
    $sidebars['two'] = array('name'     => esc_html__('Front page Footer (2 col)','tcc-fluid'),
                        'id'            => 'footer5',
                        'before_widget' => $f2_before,
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

if (!function_exists('fluidity_get_sidebar')) {
  #  This function works in tandem with fluidity_sidebar_parameter()
  function fluidity_get_sidebar($sidebar='standard') {
    get_template_part('sidebar',$sidebar);
  }
}

if (!function_exists('fluidity_load_sidebar')) {
  function fluidity_load_sidebar($args,$force=false) {
    $sidebars = ($force) ? (array)$args : array_merge((array)$args,array('standard','home'));
    foreach($sidebars as $sidebar) {
      if (is_active_sidebar($sidebar)) {
        if (dynamic_sidebar($sidebar)) {
          return true;
        } else { /*echo "<p>$sidebar non-dynamic</p>";*/ }
      } else {   /*echo "<p>$sidebar not active</p>";*/ }
    }
    return $force;
  }
}

if (!function_exists('fluidity_sidebar_parameter')) {
  function fluidity_sidebar_parameter() {
    $trace = debug_backtrace();
    foreach($trace as $item) {
      if ($item['function']=='fluidity_get_sidebar') {
			return $item['args'][0]; }
      if (($item['function']=='get_template_part') && ($item['args'][0]=='sidebar')) {
			if (!empty($item['args'][1])) { return $item['args'][1]; }
		}
    }
    return '';
  }
}

if (!function_exists('fluidity_sidebar_layout')) {
  function fluidity_sidebar_layout($sidebar='standard',$side='') {
    if ($sidebar==='plain') { return; }
    $side = ($side) ? $side : tcc_layout('sidebar');
    if ($side!=='none') {
      $sidebar_class = 'col-lg-4 col-md-4 col-sm-12 col-xs-12 margint1e'.(($side=='right') ? ' pull-right' : ''); ?>
      <aside class="<? echo $sidebar_class; ?>" <?php microdata()->WPSideBar(); ?> role="complementary"><?php
        get_template_part('sidebar',$sidebar); ?>
      </aside><?php
    }
  }
} //*/
