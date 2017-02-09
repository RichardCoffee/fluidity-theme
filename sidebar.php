<?php
/*
 *  sidebar.php
 *
 */

who_am_i();

$loaded = false;
$called = fluidity_sidebar_parameter();
if (substr($called,0,6)==='footer') {
  $loaded = fluidity_load_sidebar($called,true);
} elseif (is_front_page()) {  #  Alternate:  ($wp_query->get('page_id')===get_option('page_on_front'))
  $loaded = fluidity_load_sidebar('front');
} else {
  $loaded = fluidity_load_sidebar($called);
}
if (!$loaded) {
  global $wp_query;
  $post_type = $wp_query->get('post_type');
  $slug = ( $format=get_post_format() ) ? $format : get_post_type(); // inside loop only, which this is not
log_entry("slugfor sidebar:  $slug");
  if ($post_type) {
    if (!is_string($post_type)) {
      $use_this = 'standard';
      foreach($post_type as $type) {
        if (in_array($type,array('post'))) { continue; }
        $use_this = $type;
      }
      $post_type = $use_this;
      #log_entry("FIXME: convert array to usable string",$post_type);
    }
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
