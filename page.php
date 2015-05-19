<?php
/*
 * page.php
 *
 */

get_header();

$col_primary = "col-md-8";
$sidebar     = "standard";
$has_sidebar = is_active_sidebar($sidebar);
if (!$has_sidebar) $col_primary = "col-md-12"; ?>

<div class="container">
  <div class="row">
    <div class="<?php echo $col_primary; ?>"><?php
      who_am_i(__FILE__);
      if (have_posts()) {
        while(have_posts()) {
          the_post(); ?>
          <div class="inner-padding article"><?php
            the_content(); ?>
          </div><?php
        }
      } ?>
    </div><?php
    if ($has_sidebar) { ?>
      <div class="col-md-4"><?php
        get_sidebar('standard'); ?>
      </div><?php
    } ?>
  </div>
</div><?php

get_footer(); ?>
