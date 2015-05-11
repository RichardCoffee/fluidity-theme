<?php

/*
 * fluidity/template_parts/header.php
 *
 */

?>

<div class="header-topmenu">
  <div class="<?php echo container_type(); ?>">
    <div class="row"><?php
      do_action('tcc_top_menu_bar'); ?>
    </div>
  </div>
</div>

<div class="mast-head">
  <div class="<?php echo container_type(); ?>">
    <div class="row"><?php
      do_action('tcc_main_header'); ?>
    </div>
  </div>
</div>

<div class="menubar">
  <div class="<?php echo container_type(); ?>">
    <div class="row"><?php
      do_action('tcc_main_menubar'); ?>
    </div>
  </div>
</div>
