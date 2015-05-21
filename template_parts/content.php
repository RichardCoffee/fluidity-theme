<?php

/*
 *  tcc-fluidity/template_parts/content.php
 *
 */

global $micro;

who_am_i(); ?>

<article id="post-<?php the_ID(); ?> " <?php post_class('content'); ?> <?php $micro->BlogPosting(); ?>>

  <h4 class='text-center'><?php
    the_title(); ?>
  </h4><?php

  if (has_post_thumbnail()) { ?>
    <a href='<?php echo get_the_permalink(); ?>'><?php
      the_post_thumbnail('', array('class' => 'img-responsive listpost-img')); ?>
    </a><?php
  } ?>

  <div itemprop="text"><?php
    the_excerpt(); ?>
  </div>

</article>
