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
    <div class="col-lg-12 col-md-12 col-sm-12 col-sx-12"><?php
      sidebar_layout('single'); ?>
      <div id="content" role="main" tabindex="-1"><?php
        if (have_posts()) {
          while (have_posts()) {
            the_post();
            $slug = apply_filters('tcc-content-slug',get_post_type());
            $slug = apply_filters('tcc-single-content-slug',$slug);
            get_template_part('template-parts/content',$slug);
          }
        } ?>
      </div><!-- #content -->
    </div><!-- col-*-12 -->
  </div><!-- .row -->
</div><!-- .container --><?php

get_footer();
