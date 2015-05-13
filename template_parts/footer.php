<?php

/*
 * fluidity/template_parts/footer.php
 *
 */ ?>

<div class="ribbon"></div>

<div class="footer">
  <div class="container ">
    <div class="row"><?php
      who_am_i(__FILE__); ?>
      <div class="col-lg-3 col-md-3 hidden-sm hidden-xs ">
        <a href="<?php echo home_url(); ?>/">
          <img style="width:200px;  margin:0 auto;" class="img-responsive" src='<?php echo tcc_design('logo'); ?> ' alt="<?php bloginfo('name') ?>">
        </a>
        <h4 class="text-center"><?php echo get_bloginfo ('title');?> </h4> <!--- this needs to be editable option in theme options-->
        <address class="text-center"><!-- FIXME: this needs to be editable option in theme options -->
          189 Macon Street<br>
          Brooklyn NY, 11216 Suit #2<br>
          Office: 917 548 3997<br>
          Email: <a href="mailto:<?php echo get_option('admin_email'); ?>"><?php echo get_bloginfo ('title');?> </a>
        </address>
      </div>
      <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12"><?php
        get_sidebar('footer'); ?>
      </div>
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
        <p class="text-center">Copyright <?php echo site_copyright_dates(); ?> <?php bloginfo('name'); ?>, All rights reserved.
          <br>
          <a class="text-center" href="/conditions/" >Terms & Conditions</a>   |   <a href="/privacy/" >Security & Privacy</a>
        </p>
      </div>
    </div><!-- .row -->
  </div><!-- .container -->
</div><!-- .footer -->
