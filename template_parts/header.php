<?php

/*
 * fluidity/template_parts/header.php
 *
 */

?>

<div class="header-topmenu">
  <div class="<?php echo container_type('header-topmenu'); ?>">
    <div class="row"><?php
      do_action('tcc_top_menu_bar'); ?>
    </div>
  </div>
</div>

<div class="head-body">
  <div class="<?php echo container_type('header-body'); ?>">
    <div class="row"><?php
      do_action('tcc_main_header'); ?>
    </div>
  </div>
</div>

<div class="header-menubar">
  <div class="<?php echo container_type('header-menubar'); ?>">
    <div class="row"><?php
      do_action('tcc_main_menubar'); ?>
    </div>
  </div>
</div>
