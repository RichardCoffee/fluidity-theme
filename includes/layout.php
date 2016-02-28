<?php

/*
 *  File Name:  includes/layout.php
 *
 */

if (!function_exists('fluid_layout')) {
  function fluid_layout($sidebar) {
    $layout = new stdClass();
    $layout->sidebar    = $sidebar;
    $layout->sb_locate  = fluidity_sidebar_side($sidebar);
    $layout->sb_class   = 'col-lg-4  col-md-4  col-sm-12 col-xs-12';
    $layout->main_class = ($layout->sb_locate=='none') ? 'col-lg-12 col-md-12 col-sm-12 col-xs-12' : 'col-lg-8  col-md-8  col-sm-12 col-sm-12';
    return apply_filters('fluid_layout',$layout);
  }
}

if (!function_exists('sidebar_layout')) {
  function sidebar_layout($sidebar='standard') {
    $layout = fluid_layout($sidebar);
    if ($layout->sb_locate!=='none') {
      $sidebar_class.= $layout->sb_class.(($layout->sb_locate=='right') ? ' pull-right' : ''); ?>
      <aside class="<? echo $sidebar_class; ?>" <?php $micro->WPSideBar(); ?>><?php
        fluidity_get_sidebar($sidebar); ?>
      </aside><?php
    }
  }
}
