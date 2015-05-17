<?php

/*
 * tcc-fluidity/template_parts/header.php
 *
 */

?>
<div id="fluid-header" class="<?php echo container_type('fluid-header'); ?>">

  <div class="row header-topmenu"><?php
    do_action('tcc_header_top_menubar'); ?>
  </div>

  <div class="row header-body"><?php
    do_action('tcc_header_body_content'); ?>
  </div>

  <div class="row header-menubar"><?php
    do_action('tcc_header_bottom_menubar'); ?>
  </div>

</div>
