<?php
/*
 * File Name: page.php
 * Template Name: Standard Page
 *
 */

get_header();

$micro  = microdata();
who_am_i(); ?>

<div id="fluid-page" class="<?php echo container_type('page'); ?>" role="main" <?php $micro->Blog(); ?>>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php

      sidebar_layout('standard');

      if (have_posts()) {
        while(have_posts()) {
          the_post(); ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php $micro->BlogPosting(); ?>>
            <h1 class="text-center"><?php
              echo fluid_title(); // inserts a space
              edit_post_link(__('{Edit}','tcc-fluid'), ' '); ?>
            </h1>
            <h3 class="text-center"><?php
              echo sprintf(__('Posted on %1$s by %2$s','tcc-fluid'),get_the_date(),$micro->get_the_author(true)); ?>
            </h3>
            <div class="article"><?php
              the_content(); ?>
            </div>
          </article><?php
          if (next_post_exists()) echo "<hr class='padbott'>";
        }
      } ?>

    </div><!-- .col-*-* -->
  </div><!-- .row -->
</div><!-- .container-* --><?php

get_footer(); ?>
