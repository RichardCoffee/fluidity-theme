<?php

/*
 *  File Name:  template-parts/header.php
 *
 */

$micro = microdata();
$color_scheme = tcc_color_scheme(); ?>

<div id="fluid-header" class="<?php echo container_type('fluid-header'); ?>" <?php $micro->WPHeader(); ?> role="banner"><?php

  do_action('tcc_pre_header');

  if (has_action('tcc_header_top_menubar')) { ?>
    <div id="header-topmenu"><?php
      do_action('tcc_header_top_menubar'); ?>
    </div><?php
  } ?>

  <div id="header-body" class="row"><?php
    do_action('tcc_header_body_content'); ?>
  </div><?php

  if (has_action('tcc_header_menubar')) { ?>
    <div id="header-menubar"><?php
      do_action('tcc_header_menubar'); ?>
    </div><?php
  }

  do_action('tcc_post_header'); ?>

</div><?php

