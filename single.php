<?php

/*
 * fluidity/single.php
 *
 */

$micro = TCC_Microdata::get_instance();

get_header();

#$layout = 'sidebar-left';
$layout = 'sidebar-right'; ?>

<div class="<?php echo container_type('single'); ?>" <?php $micro->Blog(); ?>>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-sx-12"><?php

      $sidebar_class = "col-lg-4 col-md-4 col-sm-12 col-xs-12";
      $sidebar_class.= ($layout=='sidebar-right') ? ' pull-right' : ''; ?>
      <div class="<? echo $sidebar_class; ?> collapse-auto" <?php $micro->WPSideBar(); ?>><?php
        fluidity_get_sidebar('single'); ?>
      </div>

      <div id="content" role="main"><?php
        who_am_i(__FILE__);
        if (have_posts()) {
          while (have_posts()) {
            the_post();?>

            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php $micro->BlogPosting(); ?>>

              <h1 class="text-center"><?php
                echo fluid_title(20); ?>
              </h1>

              <h3 class="text-center"><?php
                echo sprintf(__('Posted on %1$s by %2$s','tcc-fluid'),get_the_date(),$micro->get_the_author()); ?>
              </h3>

              <div class="article" itemprop="text"><?php
                the_content(); ?>
              </div>

              <p class="postmetadata"><?php
                the_tags(__('Tags','tcc-fluid').': ', ', ', '<br />');
                _ex('Posted in ','string will be followed by a category or list of categories','tcc-fluid');
                the_category(', ');
                echo ' | ';
                edit_post_link(__('Edit','tcc-fluid'), '', ' | ');
                comments_popup_link('No Comments', '1 Comment', '% Comments'); ?>
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
