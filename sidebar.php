<?php
/*
 *  sidebar.php
 *
 */

who_am_i();

$loaded = false;
$called = fluidity_sidebar_parameter();
if ($called=='footer') {
  echo "<p>called '$called', loaded 'footer'</p>";
  $loaded = fluidity_load_sidebar('footer');
} elseif (is_front_page()) {
  echo "<p>called '$called', loaded 'home'</p>";
  $loaded = fluidity_load_sidebar('home');
} else {
  echo "<p>called '$called', loaded '$called'</p>";
  $loaded = fluidity_load_sidebar($called);
}
if (!$loaded) {
  global $wp_query;
  $post_type = $wp_query->get('post_type');
  if ($post_type) {
    tellme("<p>looking for sidebar '$post_type'</p>");
    $loaded = fluidity_load_sidebar(array($post_type,$post_type.'_sidebar'));
  }
}

?>
