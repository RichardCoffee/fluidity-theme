<?php
/* 
 *  fluidity/sidebar.php
 *
 */

who_am_i();

$loaded = false;
if (is_front_page()) {
#  echo "<p>front page</p>";
  $loaded = load_sidebar(array('front','standard'));
} else {
#  echo "<p>another page</p>";
  $loaded = load_sidebar(array(get_sidebar_parameter(),'standard'));
}
if (!$loaded) {
#  echo "<p>looking for post type</p>";
  global $wp_query;
  $post_type = $wp_query->get('post_type');
  if ($post_type) {
#    echo "<p>looking for post type $post_type</p>";
    $loaded = load_sidebar(array($post_type,$post_type.'_sidebar','standard'));
  }
}

function get_sidebar_parameter() {
  $trace = debug_backtrace();
  foreach($trace as $item) {
    if ($item['function']=='get_sidebar') {
      return $item['args'][0];
    }
  }
  return array();
}

function load_sidebar($sidebars) {
#  echo "<pre>"; print_r($sidebars); echo "</pre>";
  foreach($sidebars as $sidebar) {
    if (is_active_sidebar($sidebar)) {
      if (dynamic_sidebar($sidebar)) {
        return true;
      } else { /*echo "<p>$sidebar non-dynamic</p>";*/ }
    } else { /*echo "<p>$sidebar not active</p>";*/ }
  }
  return false;
}

?>
