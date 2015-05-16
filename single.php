<?php

/*
 * fluidity/single.php
 *
 */

get_header(); ?>

<div class="<?php echo container_type('single'); ?>">
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-sx-12">

      <div class="col-lg-8 col-md-8 col-sm-12 col-sx-12">
        <div id="content" role="main"><?php
          who_am_i(__FILE__);
          if (have_posts()) {
            while (have_posts()) {
              the_post();
              $string = __('Permanent Link to %s','tcc-realty');
              $title  = sprintf($string,the_title_attribute()); ?>

              <div <?php post_class() ?> id="post-<?php the_ID(); ?>">

                <h1 class="text-center"><?php echo fluid_title(20); ?></h1>

                <h3 class="text-center"><?php echo sprintf(__('Posted on %1$s by %2$s','tcc-fluid'),get_the_date(),get_the_author()); ?></h3>

                <div class="article"><?php
                  the_content(); ?>
                </div>

                <p class="postmetadata"><?php
                  the_tags('Tags: ', ', ', '<br />'); ?> 
                  Posted in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | ');
                  comments_popup_link('No Comments &#187', '1 Comment &#187', '% Comments &#187'); ?>
                </p><?php

                wp_link_pages(  );
                comments_template(); ?>

              </div><?php

            }
          } ?>

        </div><!-- #content -->
      </div><!-- .col-md-8 -->

      <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"><?php
        get_sidebar('single'); ?>
      </div>

    </div><!-- col-*-12 -->
  </div><!-- .row -->
</div><!-- .container --><?php

get_footer();

?>
