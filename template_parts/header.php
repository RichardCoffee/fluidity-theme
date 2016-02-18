<?php

/*
 * tcc-fluidity/template_parts/header.php
 *
 */

$micro = microdata();
$color_scheme = tcc_color_scheme();
do_action('tcc_pre_header'); ?>

<div id="fluid-header" class="<?php echo container_type('fluid-header'); ?>" <?php $micro->WPHeader(); ?> role="banner">

  <div id="header-body" class="row"><?php
    if (has_action('tcc_header_top_menubar')) { ?>
      <div id="header-topmenu" class="navbar navbar-<?php echo $color_scheme; ?>" <?php $micro->SiteNavigationElement(); ?> role="navigation"><?php
        do_action('tcc_header_top_menubar'); ?>
      </div><?php
    }
    do_action('tcc_header_body_content'); ?>
  </div><?php

  if (has_action('tcc_header_menubar')) { ?>
    <div id="header-menubar"><?php
      do_action('tcc_header_menubar'); ?>
    </div><?php
  } ?>

</div><?php

do_action('tcc_post_header');
