<?php
/*
 *  tcc-fluidity/index.php
 *
 */

get_header();

$layout = ""; ?>

<div class="<?php echo container_type('post'); ?>">
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs 12"><?php

      $sidebar_class = "col-lg-4 col-md-4 col-sm-12 col-xs-12";
      $sidebar_class.= ($layout=='sidebar-right') ? ' pull-right' : ''; ?>
      <div class="<? echo $sidebar_class; ?> collapse-auto"><?php
        fluidity_get_sidebar('standard'); ?>
      </div><?php

      who_am_i(__FILE__);
      if (have_posts()) {
        while(have_posts()) {
          the_post();
          $format  = __('Permanent Link to %s','tcc-fluid');
          $tooltip = sprintf($format,get_the_title()); ?>
          <h1 class="text-center">
            <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo $tooltip; ?>"><?php echo fluid_title(20); ?></a>
          </h1>
          <h3 class="text-center"><?php echo sprintf(__('Posted on %1$s by %2$s','tcc-fluid'),get_the_date(),get_the_author()); ?></h3>
          <div class="article"><?php
            the_content(); ?>
          </div><?php
          comments_template();
        }
      } ?>

    </div><!-- .col-*-* -->
  </div><!-- .row -->
</div><?php

get_footer(); ?>
