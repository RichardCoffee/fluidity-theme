<?php

/*
 * fluidity/single.php
 *
 */

get_header();
$micro = microdata(); ?>

<div class="<?php echo container_type('single'); ?>" <?php $micro->Blog(); ?>>
  <div class="row pad05perc">
    <div class="col-lg-12 col-md-12 col-sm-12 col-sx-12"><?php

      sidebar_layout('single'); ?>

      <div id="content" role="main"><?php
        who_am_i(__FILE__);
        if (have_posts()) {
          while (have_posts()) {
            the_post();?>

            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php $micro->BlogPosting(); ?>>

              <h1 class="text-center"><?php
                echo fluid_title(); // inserts a space
                edit_post_link(__('{Edit}','tcc-fluid'), ' '); ?>
              </h1>

              <h3 class="text-center"><?php
                echo sprintf(__('Posted on %1$s by %2$s','tcc-fluid'),get_the_date(),$micro->get_the_author(true)); ?>
              </h3>

              <div class="article" itemprop="text"><?php
                if ( has_post_thumbnail() ) { ?>
                  <div class='logo'><?php
                    the_post_thumbnail(); ?>
                  </div><?php
                }
                the_content(); ?>
              </div>

              <p class="postmetadata"><?php
                the_tags(__('Tags','tcc-fluid').': ', ', ', '<br />');
                _ex('Posted in ','string will be followed by a category or list of categories','tcc-fluid');
                the_category(', ');
                echo ' | ';
                edit_post_link(__('Edit','tcc-fluid'), '', ' | ');
                comments_popup_link(__('No Comments','tcc-fluid'),__('1 Comment','tcc-fluid'),_x('% Comments',"This string for multiple comments,'%' will be replaced with a number",'tcc-fluid')); ?>
              </p><?php

              wp_link_pages();
              comments_template(); ?>

            </div><?php

          }
        } ?>

      </div><!-- #content -->

    </div><!-- col-*-12 -->
  </div><!-- .row -->
</div><!-- .container --><?php

get_footer();

?>
