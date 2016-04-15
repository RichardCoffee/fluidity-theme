<?php
/*
 *  File Name: archive.php
 *
 */

get_header();
$micro = microdata();
who_am_i(); ?>

<div id="fluid-content" class="fluid-archive <?php echo container_type('archive'); ?>" <?php if (is_search()) $micro->SearchResultsPage(); ?>>
  <div class="row"><?php
    sidebar_layout('archive'); ?>
    <div id="content" <?php $micro->Blog(); ?>>
      <h1 class="text-center"><?php the_archive_title(); ?></h1><?php
      if (have_posts()) {
        if (is_search()) {
          #global $wp_query; ?>
          <h2 class="text-center"><?php
            $format = _n('%d Search Result found','%d Search Results found',$wp_query->found_posts,'tcc-fluid');
            $string = sprintf($format,$wp_query->found_posts);
            $string = apply_filters('tcc_search_title',$string);
            echo "<span itemprop='headline'>$string</span>"; ?>
          </h2>
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php
            do_action('tcc_pre_search'); ?>
          </div><?php
        }
        fluid_navigation('above');
        #$cnt = 0;
        while(have_posts()) {
          the_post();
          $slug = apply_filters('tcc-content-slug',get_post_type());
          $slug = apply_filters('tcc-archive-content-slug',get_post_type());
          get_template_part('template-parts/content',$slug);
          #tcc_apply_clearfix('cnt='.(++$cnt));
        }
        fluid_navigation('below');
        if (is_search()) { ?>
          <div class="col-lg-12 col-md-12 col-sm-12 col-sx-12"><?php
            do_action('tcc_post_search'); ?>
          </div><?php
        }
      } else { ?>
        <div id="post-0" class="post error404 not-found">
          <h1 class="text-center"><?php _e('Not Found','tcc-fluid' ); ?></h1>
          <p><?php
            $string = __( 'Apologies, but no results were found for the requested Archive. Perhaps searching will help find a related post.','tcc-fluid' );
             esc_att_e($string); ?>
          </p><?php
          get_search_form(); ?>
        </div><!-- #post-0 --><?php
      } ?>
    </div>
  </div><!-- .row -->
</div><!-- .container --><?php

get_footer(); ?>
