<?php

/*
 *  File Name:  includes/layout.php
 *
 */

if (!function_exists('fluid_layout')) {
  function fluid_layout() {
    static $layout;
    if (empty($layout)) {
      $layout = new stdClass();
      $layout->sb_locate  = tcc_layout('sidebar');
      $layout->sb_class   = 'col-lg-4  col-md-4  col-sm-12 col-xs-12 margint1e';
      #$layout->main_class = ($layout->sb_locate=='none') ? 'col-lg-12 col-md-12 col-sm-12 col-xs-12' : 'col-lg-8  col-md-8  col-sm-12 col-sm-12';
      $layout = apply_filters('fluid_layout',$layout);
    }
    return $layout;
  }
}

if (!function_exists('sidebar_layout')) {
  function sidebar_layout($sidebar='standard',$side=null) {
    $layout = fluid_layout();
    $side   = ($side) ? $side : $layout->sb_locate;
    if ($side!=='none') {
      $micro = microdata();
      $sidebar_class = $layout->sb_class.(($side=='right') ? ' pull-right' : ''); ?>
      <aside class="<? echo $sidebar_class; ?>" <?php microdata()->WPSideBar(); ?>><?php
        fluidity_get_sidebar($sidebar); ?>
      </aside><?php
    }
  }
}
