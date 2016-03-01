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
    echo fluid_title();
    edit_post_link(__('{Edit}','tcc-fluid'), ' '); # inserts a space ?>
  </h1>

  <h3 class="text-center"><?php
    echo sprintf(__('Posted on %1$s by %2$s','tcc-fluid'),get_the_date(),$micro->get_the_author(true)); ?>
  </h3>

  <div class="article" itemprop="articleBody"><?php
    if ( has_post_thumbnail() ) {
      fluid_thumbnail();
    }
    the_content(); ?>
  </div><?php

  if (is_single()) { ?>

    <p class="postmetadata"><?php
      the_tags(__('Tags','tcc-fluid').': ', ', ', '<br>');
      _ex('Posted in ','string will be followed by a category or list of categories','tcc-fluid');
      the_category(', ');
      echo ' | ';
      edit_post_link(__('Edit','tcc-fluid'), '', ' | ');
      $comm_0 = __('No Comments','tcc-fluid');
      $comm_1 = __('1 Comment','tcc-fluid');
      $comm_2 = _x('% Comments',"This string for multiple comments,'%' will be replaced with a number",'tcc-fluid');
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

    comments_template();

  } ?>

</article>
