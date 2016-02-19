<?php
/*
 *  sidebar.php
 *
 */

who_am_i();

$loaded = false;
$called = fluidity_sidebar_parameter();
echo "<p>called = $called</p>";
if ($called=='footer') {
  $loaded = fluidity_load_sidebar('footer');
  echo "<p>loaded = footer</p>";
} elseif (is_front_page()) {
  $loaded = fluidity_load_sidebar('home');
  echo "<p>loaded = home</p>";
} else {
  $loaded = fluidity_load_sidebar($called);
  echo "<p>loaded = $called</p>";
}
if (!$loaded) {
  global $wp_query;
  $post_type = $wp_query->get('post_type');
  if ($post_type) {
    tellme("<p>looking for sidebar '$post_type'</p>");
    $loaded = fluidity_load_sidebar(array($post_type,$post_type.'_sidebar','standard'));
  }
}

?>
