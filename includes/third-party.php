<?php

/*
 *  fluidity/includes/third-party.php
 *
 */

if ((function_exists('wpfai_social')) and (!function_exists('fluidity_wpfai_social'))) {
  function fluidity_wpfai_social() { ?>
    <div class="social-ico-top"><?php
      echo wpfai_social($attributes); ?>
    </div><?php
  }
  add_action('tcc_top_left_header','fluidity_wpfai_social');
}
