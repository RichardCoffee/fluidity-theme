<?php
/* 
 *  fluidity/sidebar.php
 *
 */

who_am_i();

$loaded = false;
if (is_front_page()) {
  $loaded = load_sidebar(array('front','standard'));
} else {
  $loaded = load_sidebar(get_sidebar_parameter());
}
if (!$loaded) {
  global $wp_query;
  $post_type = $wp_query->get('post_type');
  if ($post_type) {
    $loaded = load_sidebar($post_type,$post_type.'_sidebar');
  }
}

function get_sidebar_parameter() {
  $trace = debug_backtrace();
  foreach($trace as $item) {
    if ($item['function']=='get_sidebar') {
      return $item['args'];
    }
  }
  return array();
}

function load_sidebar($sidebars) {
  foreach($sidebars as $sidebar) {
    if (is_active_sidebar($sidebar)) {
      if (dynamic_sidebar($sidebar)) {
        return true;
      }
    }
  }
  return false;
}

?>
