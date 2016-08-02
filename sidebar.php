<?php
/*
 *  sidebar.php
 *
 */

who_am_i();

$loaded = false;
$called = fluidity_sidebar_parameter();
if ($called=='footer') {
  $loaded = fluidity_load_sidebar('footer',true);
} elseif (is_front_page()) { # ($wp_query->get('page_id')===get_option('page_on_front'))
  $loaded = fluidity_load_sidebar('home');
} else {
  $loaded = fluidity_load_sidebar($called);
}
if (!$loaded) {
  global $wp_query;
  $post_type = $wp_query->get('post_type');
  if ($post_type) {
    if (!is_string($post_type)) {
      log_entry("FIXME: convert array to usable string",$post_type);
    }
    tellme("<p>looking for sidebar '$post_type'</p>");
    $loaded = fluidity_load_sidebar(array($post_type,$post_type.'_sidebar'));
  }
}

/* Note: http://www.wpaustralia.org/wordpress-forums/topic/pre_get_posts-and-is_front_page/

global $wp;
if ( !is_admin() && $query->is_main_query() ) {
if ( is_home() && empty( $wp->query_string ) ) {
echo 'This displays when set to Your Latest Posts and the homepage is showing';
}
elseif ( ( $query->get( 'page_id' ) == get_option( 'page_on_front' ) && get_option( 'page_on_front' ) ) || empty( $wp->query_string ) ) {
echo 'This displays when set to A Static Page and the homepage is showing.';
echo 'It also displays for homepages with multiple pages (eg. http://sitename/page/2/)';
}
} //*/

?>
