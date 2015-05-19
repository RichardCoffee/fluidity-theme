<?php

/*
 * fluidity/template_parts/footer.php
 *
 */

global $micro; ?>

<div class="ribbon"></div>

<div class="footer"><?php
  who_am_i(); ?>
  <div class="col-lg-3 col-md-3 hidden-sm hidden-xs" itemprop='author' itemscope itemtype='http://schema.org/Organization'><?php
    $logo = tcc_design('logo');
    if ($logo) { ?>
      <a href="<?php bloginfo('url'); ?>/">
        <img itemprop="logo" style="width:200px;  margin:0 auto;" class="img-responsive" src='<?php echo $logo; ?>' alt="<?php bloginfo('name'); ?>">
      </a><?php
    } ?>
    <h4 class="text-center" itemprop="name"><?php bloginfo('title'); ?></h4>
    <!-- FIXME: address needs to be editable option in theme options -->
    <address class="text-center" <?php $micro->PostalAddress(); ?>>
      <span itemprop="streetAddress">123 Main Street</span><br>
      <span itemprop="addressLocality">Van</span> <span itemprop="addressRegion">TX</span>, <span itemprop="postalCode">12345</span><br>
      Office: <span itemprop="telephone">888 555 1212</span><br>
      Email: <a href="mailto:<?php echo get_option('admin_email'); ?>"><?php bloginfo ('title');?> </a>
    </address>
  </div>
  <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12" <?php $micro->SideBar(); ?>><?php
    get_sidebar('footer'); ?>
  </div>
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
    <p class="text-center"><?php
      $format = _x('Copyright %1$s %2$s, All rights reserved.','First string will be a year, Second string is the site name','tcc-fluid');
      echo sprintf($format,site_copyright_dates(),$micro->get_bloginfo('name')); ?>
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
