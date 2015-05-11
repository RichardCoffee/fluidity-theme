<?php
/*
 *  fluidity/index.php
 *
 */

get_header();

$contain = 'container-fluid'; ?>

<div class="<?php echo $contain; ?>">
  <div class="row">

    <div class="col-md-8 "><?php
      who_am_i(__FILE__);
      if (have_posts()) {
        while(have_posts()) {
          the_post(); ?>
          <div class="inner-padding article"><?php
            the_content(); ?>
          </div><?php
        }
      } ?>
    </div>

    <div class="col-md-4"><?php
      get_sidebar(); ?>
    </div>

  </div><!-- .row -->

</div><?php

get_footer(); ?>
