<?php
/*
 *  File Name: index.php
 *
 */


if (!function_exists('log_entry')) {
  function log_entry() {
    if (WP_DEBUG) {
      $args  = func_get_args();
      $depth = 1;
      if (is_int($args[0])) {
        $depth = $args[0];
        unset($args[0]);
      }
      if ($depth) error_log(debug_calling_function($depth));
      foreach ($args as $message) {
        if (is_array($message) || is_object($message)) {
          error_log(print_r($message, true));
        } else if ($message==='dump') {
          error_log(print_r(debug_backtrace(),true));
        } else {
          error_log($message);
        }
      }
    }
  }
} else { log_entry('log_entry already defined'); }
  log_entry('log_entry now defined');


log_entry('in index.php');

get_header();

fluid_index_page('index');

get_footer();
