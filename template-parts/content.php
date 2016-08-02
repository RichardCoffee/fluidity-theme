<?php

/*
 *  File Name: template-parts/content.php
 *
 *  Notes:  can only be used within The Loop
 */

$micro = microdata();
$href  = get_the_permalink();
who_am_i(); ?>

<article id="post-<?php the_ID(); ?> " <?php post_class(); ?> <?php $micro->BlogPosting(); ?>>

  <h1 class="text-center"><?php
    if (is_single()) {
      fluid_title();
    } else {
      $format  = __('Permanent Link to %s','tcc-fluid');
      $tooltip = sprintf($format,get_the_title()); ?>
      <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_attr_e($tooltip); ?>"><?php fluid_title(); ?></a><?php
    }
    fluid_edit_post_link(); ?>
  </h1>

  <h3 class="text-center"><?php
    echo sprintf(_x('Posted on %1$s by %2$s','first: formatted date string, second: user name','tcc-fluid'),get_the_date(),$micro->get_the_author(true)); ?>
  </h3><?php

log_entry('post date: '.get_the_date('U'),'modified date: '.get_the_modified_date('U'));
  if (get_the_modified_date('U')>get_the_date('U')) { ?>
    <h3 class="text-center"><?php
      echo sprintf(__('Last modified on %s','tcc_fluid'),get_the_modified_date()); ?>
    </h3><?php
  } ?>

  <div class="article" itemprop="articleBody"><?php
    if ( has_post_thumbnail() ) {
      fluid_thumbnail();
    }
    the_content(); ?>
  </div><?php

  if (is_single()) { ?>

    <p class="postmetadata"><?php
      the_tags(__('Tags','tcc-fluid').': ', ', ', '<br>');
      esc_html_e(_x('Posted in ','string will be followed by a category or list of categories','tcc-fluid'));
      the_category(', ');
      echo ' | ';
      fluid_edit_post_link(' | ');
      $comm_0 = esc_html__('No Comments','tcc-fluid');
      $comm_1 = esc_html__('1 Comment','tcc-fluid');
      $comm_2 = esc_html_x('% Comments',"This string for multiple comments,'%' will be replaced with a number",'tcc-fluid');
      comments_popup_link( $comm_0, $comm_1, $comm_2 ); ?>
    </p>

    <div class='post-links'>
      <span class='pull-left'><?php
        previous_post_link(); ?>
      </span>
      <span class='pull-right'><?php
        next_post_link(); ?>
      </span>
      <br>
    </div><?php
    if ( comments_open() || get_comments_number() ) {
      comments_template();
    }
  } ?>

</article>
