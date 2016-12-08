<?php

/*
 *  File Name:  template-parts/header.php
 *
 */

$page  = fluidity_page_slug();
$color = tcc_color_scheme(); ?>
<div id="" class="header-static">
<div id="fluid-header" class="container-fluid <?php #echo container_type('fluid-header'); ?>" <?php microdata()->WPHeader(); ?> role="banner"><?php

  do_action('tcc_pre_header');
  do_action("tcc_pre_header_$page");

  if (has_action('tcc_header_top_menubar') || has_action('tcc_header_top_menubar_'.$page)) { ?>
    <div id="header-topmenu" class="navbar navbar-<?php echo $color; ?>"><?php
      do_action('tcc_header_top_menubar');
      do_action('tcc_header_top_menubar_'.$page); ?>
    </div><?php
  }

  if (has_action('tcc_header_body_content') || has_action('tcc_header_body_content_'.$page)) { ?>
    <div id="header-body" class="row nomargin hidden-xs">
      <div class="width-<?php echo tcc_layout('width'); ?>"><?php
        do_action('tcc_header_body_content');
        do_action('tcc_header_body_content_'.$page); ?>
      </div>
    </div><?php
  }

  if (has_action('tcc_header_menubar') || has_action('tcc_header_menubar_'.$page)) { ?>
    <div id="header-menubar" class="navbar navbar-<?php echo $color; ?>">
      <div class="width-<?php echo tcc_layout('width'); ?>"><?php
        do_action('tcc_header_menubar');
        do_action('tcc_header_menubar_'.$page); ?>
      </div>
    </div><?php
  }

  do_action('tcc_post_header');
  do_action("tcc_post_header_$page"); ?>

</div>
</div>
<?php

do_action('tcc_after_header');
do_action('tcc_after_header_'.$page);
