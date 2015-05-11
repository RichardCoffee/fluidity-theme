<?php

/*
 *  fluidity/includes/options.php
 *
 */

if (!function_exists('tcc_design')) {
  function tcc_design($option) {
    static $data;
    if (empty($data)) { $data = get_option('tcc_options_design'); }
    if (isset($data[$option])) {
      return $data[$option];
    } else if (function_exists('tcc_option')) {
      return tcc_option($option);
    }
    return '';
  }
}

if (!function_exists('tcc_layout')) {
  function tcc_layout() {
    static $data;
    if (empty($data)) { $data = get_option('tcc_options_layout'); }
    if (isset($data[$option])) {
      return $data[$option];
    } else if (function_exists('tcc_option')) {
      return tcc_option($option);
    }
    return '';
  }
}
