<?php

/*
 *  includes/third-party.php
 *
 */


if (!function_exists('fluidity_social_icons')) {
  function fluidity_social_icons() { ?>
    <div class="fluidity-social-icons"><?php
      if (function_exists('wpfai_social')) {
        $attributes = array('icons' => 'twitter,facebook,google-plus,pinterest,linkedin',
          'shape' => 'square', 'inverse' => 'yes', 'size' => 'lg', 'loadfa' => 'no');
        echo wpfai_social($attributes);
      } ?>
    </div><?php
  }
  add_action('tcc_top_left_header','fluidity_social_icons');
}
