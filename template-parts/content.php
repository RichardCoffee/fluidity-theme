<?php

/*
 *  File Name: template-parts/content.php
 *
 *  Notes:  can only be used within The Loop
 */

who_am_i(); ?>

<article id="post-<?php the_ID(); ?> " <?php post_class(); ?> <?php microdata()->BlogPosting(); ?>>

  <?php fluid_thumbnail();

  if (!is_page()) { ?>

    <h1 class="text-center"><?php
      if (is_single()) {
        fluid_title();
      } else {
        $format  = esc_html__('Permanent Link to %s','tcc-fluid');
        $tooltip = sprintf($format,get_the_title()); ?>
        <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_attr_e($tooltip); ?>"><?php fluid_title(); ?></a><?php
      }
      fluid_edit_post_link(); ?>
    </h1><?php

    fluid_post_date(true);
  } ?>

  <div class="article" itemprop="articleBody"><?php
#    if ( has_post_thumbnail() ) {
#      fluid_thumbnail();
#    }
    the_content(); ?>
  </div><?php

  if (is_single()) { ?>

    <p class="postmetadata noprint"><?php
      the_tags(esc_html__('Tags','tcc-fluid').': ', ', ', '<br>');
      esc_html_x('Posted in ','string will be followed by a category or list of categories','tcc-fluid');
      the_category(', ');
      echo ' | ';
      #fluid_edit_post_link(' | ');
      $comm_0 = esc_html__('No Comments','tcc-fluid');
      $comm_1 = esc_html__('1 Comment','tcc-fluid');
      $comm_2 = esc_html_x('% Comments',"This string for multiple comments,'%' will be replaced with a number",'tcc-fluid');
      comments_popup_link( $comm_0, $comm_1, $comm_2 ); ?>
    </p><?php

#    fluid_navigation();
/*
    <div class='post-links noprint'>
      <span class='pull-left'><?php
        previous_post_link(); ?>
      </span>
      <span class='pull-right'><?php
        next_post_link(); ?>
      </span>
      <br>
    </div><?php
*/
    if ( comments_open() || get_comments_number() ) {
      comments_template();
    }
  } ?>

</article>
