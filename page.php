<?php
/*
 * File Name: page.php
 * Template Name: Standard Page
 *
 */

get_header();

$micro  = microdata();
who_am_i(); ?>

<div id="fluid-content" class="fluid-page <?php echo container_type('page'); ?>" role="main" <?php $micro->Blog(); ?>>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php
      sidebar_layout('standard'); ?>
      <div id="content" role="main" tabindex="-1"><?php
        if (have_posts()) {
          while(have_posts()) {
            the_post();
            get_template_part('template-parts/content',fluid_content_slug('page'));
            if (fluid_next_post_exists()) echo "<hr class='padbott'>";
          }
        } ?>
      </div>
    </div><!-- .col-*-* -->
  </div><!-- .row -->
</div><!-- .container-* --><?php

get_footer(); ?>
