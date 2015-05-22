<?php
/*
 * tcc-fluidity/archive.php
 *
 * Template Name:  Archive Page Template
 *
 */

$fluid = new Fluid_Layout();
$micro = $fluid->microdata;

get_header(); ?>

<div class="<?php echo container_type($fluid->sidebar_name); ?>" <?php if (is_search()) $micro->SearchResultsPage(); ?>>
  <div class="row"><?php
    who_am_i();

    $fluid->get_sidebar(); ?>

    <div class="<?php echo $fluid->primary_class; ?>" <?php $micro->Blog(); ?>>
      <h1 class="text-center"><?php the_archive_title(); ?></h1><?php
      if (have_posts()) {
        if (is_search()) { ?>
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
        if ($wp_query->max_num_pages>1) tcc_navigation('above');
        $cnt  = 0;
        while(have_posts()) {
          the_post(); ?>
          <div class="<?php echo $fluid->inner_class; ?> content content-<?php echo $fluid->color_scheme; ?>" <?php $micro->BlogPosting(); ?>><?php
            $slug = apply_filters('tcc_archive_content_slug',$wp_query->post_type);
            get_template_part('template_parts/content',$slug); ?>
          </div><?php
          apply_clearfix($fluid->clearfix.'&cnt='.(++$cnt));
        }
        if ($wp_query->max_num_pages>1) tcc_navigation('below');
        if (is_search()) { ?>
          <div class="col-lg-12 col-md-12 col-sm-12 col-sx-12"><?php
            do_action('tcc_post_search'); ?>
          </div><?php
        }
      } ?>
    </div>

  </div><!-- .row -->
</div><!-- .container --><?php

get_footer(); ?>
