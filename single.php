<?php

/*
 *  File Name:  single.php
 *
 */

get_header();
$micro = microdata();
who_am_i(); ?>

<div id="fluid-content" class="fluid-single <?php echo container_type('single'); ?>" <?php $micro->Blog(); ?>>
  <div class="row pad05perc">
    <div class="col-lg-12 col-md-12 col-sm-12 col-sx-12">
      <div class="fluid-sidebar hidden-sm hidden-xs"><?php
        sidebar_layout('single'); ?>
      </div>
      <div id="content" role="main" tabindex="-1"><?php
        if (have_posts()) {
          while (have_posts()) {
            the_post();
            get_template_part('template-parts/content',fluid_content_slug('single'));
          }
        } ?>
      </div><!-- #content -->
      <div class="fluid-sidebar visible-sm visible-xs"><?php
        sidebar_layout('single'); ?>
      </div>
    </div><!-- col-*-12 -->
  </div><!-- .row -->
</div><!-- .container --><?php

get_footer();
