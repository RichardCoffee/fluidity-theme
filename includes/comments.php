<?php

/*
 *  File:  includes/comments.php
 *
 */

#  http://www.christianmontoya.com
#  http://themeshaper.com/2012/11/04/the-wordpress-theme-comments-template/
if (!function_exists('fluid_comment')) {
  function fluid_comment($comment, $args, $depth) {
    static $striping = 'odd';
    $GLOBALS['comment'] = $comment; // Do we need this?
    switch ( $comment->comment_type ) {
      case 'pingback' :
      case 'trackback' :
        $comm_css = "post $comm_type $striping" ?>
        <li id="comment-<?php echo $comment->comment_ID ?>" class="<?php comment_class( $comm_css, $comment->comment_ID, $post->ID, true); ?>"><?php
          echo sprintf( $string, $type_arr[$comm_type], get_comment_author_link($comment->comment_ID), get_comment_date('',$comment->comment_ID) ); ?>
        </li><?php
        break;
      default:
    }
    $striping = ($striping==='odd') ? 'even' : 'odd';
  }
}
