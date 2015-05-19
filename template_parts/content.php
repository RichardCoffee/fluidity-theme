<?php

/*
 *  template_parts.content.php
 *
 */

global $micro;
$href = get_the_permalink();
who_am_i(); ?>

<div id="post-<?php the_ID(); ?> " <?php post_class(); ?>>

  <h4 class='text-center'><?php
    $micro->the_title(); ?>
  </h4><?php

  if (has_post_thumbnail()) { ?>
    <a href='<?php echo $href; ?>'><?php
      the_post_thumbnail('', array('class' => 'img-responsive listpost-img')); ?>
    </a><?php
  }

  the_excerpt(); ?>

</div>
