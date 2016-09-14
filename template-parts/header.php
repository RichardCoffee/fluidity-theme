<?php

/*
 *  File Name:  template-parts/header.php
 *
 */

$color_scheme = tcc_color_scheme(); ?>

<div id="fluid-header" class="<?php echo container_type('fluid-header'); ?>" <?php microdata()->WPHeader(); ?> role="banner"><?php

  do_action('tcc_pre_header');

  if (has_action('tcc_header_top_menubar')) { ?>
    <div id="header-topmenu" class="navbar navbar-<?php echo $color_scheme; ?>"><?php
      do_action('tcc_header_top_menubar'); ?>
    </div><?php
  } ?>

  <div id="header-body" class="row">
    <div class="width-<?php echo tcc_layout('width'); ?>"><?php
      do_action('tcc_header_body_content'); ?>
    </div>
  </div><?php

  if (has_action('tcc_header_menubar')) { ?>
    <div id="header-menubar" class="navbar navbar-<?php echo $color_scheme; ?>">
      <div class="width-<?php echo tcc_layout('width'); ?>"><?php
        do_action('tcc_header_menubar'); ?>
      </div>
    </div><?php
  }

  do_action('tcc_post_header'); ?>

</div><?php

do_action('tcc_after_header');
