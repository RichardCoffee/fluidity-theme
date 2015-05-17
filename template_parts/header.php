<?php

/*
 * tcc-fluidity/template_parts/header.php
 *
 */

?>

<div id="fluid-header" class="<?php echo container_type('fluid-header'); ?>">

  <div id="header-topmenu" class="row navbar"><?php
    do_action('tcc_header_top_menubar'); ?>
  </div>

  <div id="header-body" class="row"><?php
    do_action('tcc_header_body_content'); ?>
  </div>

  <div id="header-menubar" class="row"><?php
    do_action('tcc_header_bottom_menubar'); ?>
  </div>

</div>
