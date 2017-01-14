<?php

if (!function_exists('debug_calling_function')) {
  // http://php.net/debug_backtrace
  function debug_calling_function($depth=1) {
    $file = $func = $line = 'n/a';
    $debugTrace = debug_backtrace();
    if (isset($debugTrace[$depth])) {
      $file = ($debugTrace[$depth]['file']) ? $debugTrace[$depth]['file'] : 'n/a';
      $line = ($debugTrace[$depth]['line']) ? $debugTrace[$depth]['line'] : 'n/a';
    }
    $func = (isset($debugTrace[($depth+1)]['function'])) ? $debugTrace[($depth+1)]['function'] : 'n/a';
    return "$file, $func, $line";
  }
}

#  generate log entry, with comment
if (!function_exists('log_entry')) {
  function log_entry() {
    if (WP_DEBUG) {
      $args  = func_get_args();
      $depth = 1;
      if ($args && is_int($args[0])) {
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
}
