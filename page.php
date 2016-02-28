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
  <div class="row"><?php

    sidebar_layout('standard'); ?>

    <div class="<?php echo $col_primary; ?>"><?php
      who_am_i();
      if (have_posts()) {
        while (have_posts()) {
          the_post(); ?>
          <div class="article"><?php
            the_content(); ?>
          </div><?php
        }
      } ?>
    </div>
  </div>
</div><?php

get_footer(); ?>
