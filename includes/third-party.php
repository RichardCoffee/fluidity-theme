<?php

/*
 *  includes/third-party.php
 *
 */


if (function_exists('wpfai_social')) {
  function fluidity_wpfai_social() {
    $attributes = array('icons' => 'twitter,facebook,google-plus,pinterest,linkedin', // FIXME: can we assign list from options data?
      'shape' => 'square', 'inverse' => 'yes', 'size' => 'lg', 'loadfa' => 'no'); ?>
    <div class="fluidity-social-icons"><?php
        echo wpfai_social($attributes); ?>
    </div><?php
  }
  add_action('fluidity_social_icons','fluid_wpfai_social');
}
