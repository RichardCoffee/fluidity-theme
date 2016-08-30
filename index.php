<?php
/*
 *  File Name: index.php
 *
 */

get_header();
who_am_i(); ?>

<div id="fluid-content" class="fluid-index <?php echo container_type('post'); ?>" role="main" <?php microdata()->Blog(); ?>>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs 12"><?php
      sidebar_layout('standard'); ?>
      <div id="content" role="main" tabindex="-1"><?php
        if (have_posts()) {
          while(have_posts()) {
            the_post();
            get_template_part('template-parts/excerpt',fluid_content_slug('index'));
          }
        }
        fluid_navigation('below'); ?>
      </div><!-- #content -->
    </div><!-- .col-*-* -->
  </div><!-- .row -->
</div><!-- .container-* --><?php

get_footer();
