<?php
/*
 * File Name: page.php
 * Template Name: Standard Page
 *
 */

get_header();

$micro  = microdata();
$layout = fluid_layout(); ?>

<div class="<?php echo container_type('page'); ?>">
  <div class="row">
    <div class="col-xs-12"><?php

      sidebar_layout('standard'); ?>

      <div class="<?php echo $col_primary; ?>"><?php
        who_am_i();
        if (have_posts()) {
          while (have_posts()) {
            the_post(); ?>
            <h1 class="text-center"><?php
              echo fluid_title(); // inserts a space
              edit_post_link(__('{Edit}','tcc-fluid'), ' '); ?>
            </h1>
            <h3 class="text-center"><?php
              echo sprintf(__('Posted on %1$s by %2$s','tcc-fluid'),get_the_date(),$micro->get_the_author(true)); ?>
            </h3>
            <div class="article"><?php
              the_content(); ?>
            </div><?php
            if (have_posts()) echo "<hr class='padbott'>";
          }
        } ?>
      </div>

    </div>
  </div>
</div><?php

get_footer(); ?>
