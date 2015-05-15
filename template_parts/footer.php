<?php

/*
 * fluidity/template_parts/footer.php
 *
 */ ?>

<div class="ribbon"></div>

<div class="footer">
  <div class="<?php echo container_type('footer'); ?> ">
    <div class="row"><?php
      who_am_i(__FILE__); ?>
      <div class="col-lg-3 col-md-3 hidden-sm hidden-xs "><?php
        $logo = tcc_design('logo');
        if ($logo) { ?>
          <a href="<?php echo home_url(); ?>/">
            <img style="width:200px;  margin:0 auto;" class="img-responsive" src='<?php echo $logo; ?>' alt="<?php bloginfo('name'); ?>">
          </a><?php
        } ?>
        <h4 class="text-center"><?php echo get_bloginfo('title'); ?></h4>
        <address class="text-center"><!-- FIXME: this needs to be editable option in theme options -->
          123 Main Street<br>
          Van TX, 12345<br>
          Office: 888 555 1212<br>
          Email: <a href="mailto:<?php echo get_option('admin_email'); ?>"><?php echo get_bloginfo ('title');?> </a>
        </address>
      </div>
      <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12"><?php
        get_sidebar('footer'); ?>
      </div>
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 "><?php
        $format = _x('Copyright %1$s %2$s, All rights reserved.','First string will be a year, Second string is the site name','tcc-fluid'); ?>
        <p class="text-center"><?php
          echo sprintf($format,site_copyright_dates(),get_bloginfo('name')); ?>
          <br><?php
          $foot_menu = array();
          if (page_exists('conditions')) $foot_menu[] = array('conditions', __('Terms & Conditions','tcc-fluid'));
          if (page_exists('privacy'))    $foot_menu[] = array('privacy',    __('Privacy Policy',    'tcc-fluid'));
          if (page_exists('security'))   $foot_menu[] = array('security',   __('Security Policy',   'tcc-fluid'));
          $foot_menu = apply_filters('tcc_bottom_menu',$foot_menu);
          if ($foot_menu) {
            $string = '';
            foreach($foot_menu as $option) {
              $string.= "<a href='/{$option[0]}/'> {$option[1]} </a> | "; }
            echo substr($string,0,-3);
          }  ?>
        </p>
      </div>
    </div><!-- .row -->
  </div><!-- .container -->
</div><!-- .footer -->
