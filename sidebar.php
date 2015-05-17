<?php
/* 
 *  tcc-fluidity/sidebar.php
 *
 */

who_am_i();

$loaded = false;
if (is_front_page()) {
  $loaded = load_sidebar(array('front','standard'));
} else {
  $loaded = load_sidebar(array(get_sidebar_parameter(),'standard'));
}
if (!$loaded) {
  global $wp_query;
  $post_type = $wp_query->get('post_type');
  if ($post_type) {
    tellme("<p>looking for sidebar '$post_type'</p>");
    $loaded = load_sidebar(array($post_type,$post_type.'_sidebar','standard'));
  }
}

?>
