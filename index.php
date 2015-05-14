<?php
/*
 *  fluidity/index.php
 *
 */

get_header(); ?>

<div class="<?php echo container_type('post'); ?>">
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs 12">

      <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12"><?php
        who_am_i(__FILE__);
        if (have_posts()) {
          while(have_posts()) {
            the_post(); ?>
            <h1 class="text-center"><?php the_title(); ?></h1>
            <h3 class="text-center"><?php echo sprintf(__('Posted on %s','tcc-fluid'),get_the_date()); ?></h3>
            <div class="article"><?php
              the_content(); ?>
            </div><?php
          }
        } ?>
      </div>

      <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"><?php
        get_sidebar('standard'); ?>
      </div>

    </div><!-- .col-*-* -->
  </div><!-- .row -->
</div><?php

get_footer(); ?>
