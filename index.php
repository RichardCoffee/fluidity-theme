<?php
/*
 *  File Name: index.php
 *
 */

get_header();
$micro = microdata();
who_am_i(); ?>

<div id="fluid-index" class="<?php echo container_type('post'); ?>" role="main" <?php $micro->Blog(); ?>>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs 12"><?php
      echo 'registration: '.get_site_option('registration');
      sidebar_layout('standard');
      if (have_posts()) {
        while(have_posts()) {
          the_post();
          $slug = apply_filters('tcc-excerpt-slug',get_post_type());
          $slug = apply_filters('tcc-index-excerpt-slug',$slug);
          get_template_part('template-parts/excerpt',$slug);
        }
      }
      fluid_navigation('below'); ?>
    </div><!-- .col-*-* -->
  </div><!-- .row -->
</div><!-- .container-* --><?php

get_footer();
