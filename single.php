<?php

/*
 * fluidity/single.php
 *
 */

get_header(); ?>

<div class="container">
  <div class="row">

    <div class="col-md-8">
      <div id="content" role="main"><?php
        who_am_i(__FILE__);
        if (have_posts()) {
          while (have_posts()) {
            the_post();
            $string = __('Permanent Link to %s','tcc-realty');
            $title  = sprintf($string,the_title_attribute()); ?>

            <div <?php post_class() ?> id="post-<?php the_ID(); ?>">
              <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php echo $title; ?>"><?php the_title(); ?></a></h2>
              <small><?php the_time('F jS, Y') ?> <!-- by <?php the_author() ?> --></small>

              <div class="entry"><?php
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

    <div class="col-md-4"><?php
      get_sidebar('single'); ?>
    </div>

  </div><!-- .row -->
</div><!-- .container --><?php

get_footer();

?>
