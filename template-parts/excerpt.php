<?php

/*
 *  File Name:  excerpt.php
 *
 */

who_am_i(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php microdata()->BlogPosting(); ?>><?php

  $format  = esc_html__('Permanent Link to %s','tcc-fluid');
  $tooltip = sprintf($format,get_the_title()); ?>

  <h1 class="text-center" itemprop="headline">
    <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_attr_e($tooltip); ?>"><?php fluid_title(40); ?></a>
  </h1><?php

  if (tcc_layout('exdate')==='show') { fluid_post_date(); } ?>

  <div class="article" itemprop="description"><?php
    the_excerpt(); ?>
  </div>

</article>
