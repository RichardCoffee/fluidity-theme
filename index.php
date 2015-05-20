<?php
/*
 *  tcc-fluidity/index.php
 *
 */

$micro  = TCC_Microdata::get_instance();
$layout = "";
get_header(); ?>

<div class="<?php echo container_type('post'); ?>" role="main" <?php $micro->Blog(); ?>>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs 12"><?php

      $sidebar_class = "col-lg-4 col-md-4 col-sm-12 col-xs-12";
      $sidebar_class.= ($layout=='sidebar-right') ? ' pull-right' : ''; ?>
      <div class="<? echo $sidebar_class; ?> collapse-auto" <?php $micro->WPSideBar(); ?>><?php
        fluidity_get_sidebar('standard'); ?>
      </div>

      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php $micro->BlogPosting(); ?>><?php
        who_am_i(__FILE__);
        if (have_posts()) {
          while(have_posts()) {
            the_post();
            $format  = __('Permanent Link to %s','tcc-fluid');
            $tooltip = sprintf($format,get_the_title()); ?>
            <h1 class="text-center" itemprop="headline">
              <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo $tooltip; ?>"><?php echo fluid_title(20); ?></a>
            </h1>
            <h3 class="text-center"><?php
              echo sprintf(__('Posted on %1$s by %2$s','tcc-fluid'),get_the_date(),$micro->get_the_author()); ?>
            </h3>
            <div class="article" itemprop="articleBody"><?php
              the_content(); ?>
            </div><?php
            comments_template();
          }
        } ?>
      </article>

    </div><!-- .col-*-* -->
  </div><!-- .row -->
</div><?php

get_footer(); ?>
