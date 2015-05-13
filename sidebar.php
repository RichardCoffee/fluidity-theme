<?php

/* Name: Sidebar Default */

who_am_i(__FILE__);

$loaded = false;
if (is_front_page()) {
  echo "<h5>front page</h5>";
  $loaded = load_sidebar(array('front','standard'));
} else {
  echo "<h5>not front page</h5>";
  $loaded = load_sidebar(get_sidebar_parameter());
}
if (!$loaded) {
  echo "<h5>wp_query sidebar</h5>";
  global $wp_query;
  $post_type = $wp_query->get('post_type');
  if ($post_type) {
    echo "<h5>post_type: $post_type</h5>";
    $loaded = load_sidebar($post_type,$post_type.'_sidebar');
  }
}

function get_sidebar_parameter() {
  $trace = debug_backtrace();
  foreach($trace as $item) {
    if ($item['function']=='get_sidebar')
      return $item['args'];
  }
  return array();
}

function load_sidebar($sidebars) {
  foreach($sidebars as $sidebar) {
    if (is_active_sidebar($sidebar)) {
      if (dynamic_sidebar($sidebar)) return true;
    }
  }
  return false;
}



?>
