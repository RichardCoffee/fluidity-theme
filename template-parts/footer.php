<?php

/*
 *  File Name:  template-parts/footer.php
 *
 */

global $micro; ?>

<div class="ribbon"></div>

<div class="footer"><?php
  who_am_i(); ?>
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" <?php $micro->WPSideBar(); ?>><?php
    fluidity_get_sidebar('footer'); ?>
  </div>
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
    <p class="text-center"><?php
      $format = _x('Copyright %1$s %2$s, All rights reserved.','First string will be a year, Second string is the site name','tcc-fluid');
      echo sprintf($format,fluid_copyright_dates(),$micro->get_bloginfo('name')); ?>
      <br><?php
      $foot_menu = array();
      if (page_exists('terms'))      $foot_menu[] = array('terms',      __('Terms & Conditions','tcc-fluid'));
      if (page_exists('conditions')) $foot_menu[] = array('conditions', __('Terms & Conditions','tcc-fluid'));
      if (page_exists('privacy'))    $foot_menu[] = array('privacy',    __('Privacy Policy',    'tcc-fluid'));
      if (page_exists('security'))   $foot_menu[] = array('security',   __('Security Policy',   'tcc-fluid'));
      $foot_menu = apply_filters('tcc_bottom_menu',$foot_menu);
      if ($foot_menu) {
        $string = '<span '.$micro->SiteNavigationElement().'>';
        foreach($foot_menu as $option) {
          $string.= "<a href='/{$option[0]}/'> {$option[1]} </a> | "; }
        echo substr($string,0,-3).'</span>';
      }  ?>
    </p>
  </div>
</div><!-- .footer -->
