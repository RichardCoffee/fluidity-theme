<?php

/* Name: Sidebar Default */

who_am_i(__FILE__);

$loaded = false;
if (is_front_page()) {
  $loaded = load_sidebar(array('front'));
} else {
  $loaded = load_sidebar(get_sidebar_parameter());
}
if (!$loaded) {
  global $wp_query;
}

function get_sidebar_parameter() {
  $trace = debug_backtrace();
  foreach($trace as $item) {
    if ($item['function']=='get_sidebar')
      return $item['args'];
  }
  return array();
}

if (!function_exists('load_sidebar')) {
  function load_sidebar($sidebars) {
    foreach($sidebars as $sidebar) {
      if (is_active_sidebar($sidebar)) {
        if (dynamic_sidebar($sidebar)) return true;
      }
    }
    return false;
  }
}


?>
