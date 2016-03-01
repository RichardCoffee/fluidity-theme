<?php

/*
 *  File Name:  category.php
 *
 */

get_header();
$micro = microdata();
who_am_i(); ?>

<div id="fluid-category" class="<?php echo container_type('category'); ?>">
  <div class="row"><?php
    $fluid->get_sidebar(); ?>
    <div class="<?php echo $fluid->primary_class; ?>" <?php $micro->Blog(); ?>>
      <h1 class="text-center"><?php single_cat_title(); ?></h1><?php
      if (have_posts()) {
        fluid_navigation('above');
        $cnt = 0;
        while (have_posts()) {
          the_post(); ?>
          <div class="<?php echo $fluid->inner_class; ?> content content-<?php echo $fluid->color_scheme; ?>" <?php $micro->BlogPosting(); ?>><?php
            $slug = apply_filters('tcc-content-slug',get_post_type());
            $slug = apply_filters('tcc-category-content-slug',$slug);
            get_template_part('template-parts/content',$slug); ?>
          </div><?php
          tcc_apply_clearfix($fluid->clearfix.'&cnt='.(++$cnt));
        }
        fluid_navigation('below');
      } else { ?>
        <div id="post-0" class="post error404 not-found">
          <h1 class="text-center"><?php _e('Not Found','tcc-fluid' ); ?></h1>
          <p><?php _e( 'Apologies, but no results were found for the requested Category. Perhaps searching will help find a related post.','tcc-fluid' ); ?></p><?php
          get_search_form(); ?>
        </div><!-- #post-0 --><?php
      } ?>
    </div>
  </div>
</div><?php

get_footer();
