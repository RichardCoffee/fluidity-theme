<?php
/*
 *
Template Name: Standard Page
 *
 * tcc-fluidity/page.php
 *
 */

get_header();

$micro = microdata();
$col_primary = "col-lg-8 col-md-8 col-sm-12 col-sm-12";
$sidebar     = "standard";
if ($has_sidebar=is_active_sidebar($sidebar)) {
  $sidebar_side = fluidity_sidebar_side($sidebar);
} else {
  $col_primary = "col-lg-12 col-md-12 col-sm-12 col-xs-12";
} ?>


<div class="<?php echo container_type('page'); ?>">
  <div class="row"><?php
    if ($has_sidebar) { ?>
      <div class="col-lg-4 col-md-4 hidden-sm hidden-xs"><?php
        get_sidebar('standard'); ?>
      </div><?php
    } ?>
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
