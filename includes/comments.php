<?php

/*
 *  File:  includes/comments.php
 *
 */

if (!function_exists('fluid_comment_navigation')) {
  function fluid_comment_navigation() {
    if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>
      <nav class="navigation comment-navigation" role="navigation">
        <h2 class="screen-reader-text"><?php
          _e('Comment navigation','tcc-fluid'); ?>
        </h2>
        <div class="nav-previous"><?php // FIXME:  poor translation string
          previous_comments_link('&larr; '.__('Older Comments','tcc-fluid')); ?>
        </div>
        <div class="nav-next"><?php
          next_comments_link(__('Newer Comments','tcc-fluid').' &rarr;'); ?>
        </div>
      </nav><!-- #comment-nav-above --><?php
    }
  }
}

if (!function_exists('fluid_list_comments')) {
  #  http://www.christianmontoya.com
  #  http://themeshaper.com/2012/11/04/the-wordpress-theme-comments-template/
  function fluid_list_comments($comment,$args,$depth) {
    static $striping = 'odd';
    $GLOBALS['comment'] = $comment;
    $string    = _x('%1s: %2$s on %3$s','first placeholder is the comment type, second placeholder is an author link, third placeholder is a date','tcc-fluid');
    $type_arr  = array('comment'=>__('Comment'),'trackback'=>__('Trackback'),'pingback'=>__('Pingback'));
    $comm_type = $comment->comment_type;
    switch ( $comm_type ) {
      case 'pingback' :
      case 'trackback' :
        $comm_css = "post $comm_type $striping" ?>
        <li id="comment-<?php echo $comment->comment_ID ?>" class="<?php comment_class($comm_css); ?>"><?php
          echo sprintf( $string, $type_arr[$comm_type], get_comment_author_link(), get_comment_date() );
        #</li> - wordpress add closing tag
        break;
      default: # comment
        if (empty($comm_type)) $comm_type = 'comment';
        $css = ( get_the_author_meta('user_email') === get_comment_author_email() ) ? 'post-author' : $striping; ?>
        <li id="comment-<?php echo $comment->comment_ID; ?>" class="<?php comment_class($css); ?>">
          <div class="comment-author vcard"><?php
            echo get_avatar( $comment, 34 );
            echo ' '.sprintf( $string, $type_arr[$comm_type], get_comment_author_link(), get_comment_date() ); ?>
          </div><!-- .comment-author .vcard --><?php
          if ( $comment->comment_approved == '0' ) { ?>
            <em><?php _e( 'Your comment is awaiting moderation.', 'tcc-fluid' ); ?></em><br /><?php
          } ?>
          <div class="comment-meta commentmetadata">
            <a href="<?php echo esc_url( get_comment_link() ); ?>"><?php
              comment_date(); ?>
            </a> <?php
#            edit_comment_link( __( '(Edit)', 'tcc-fluid' ), ' ' ); ?>
          </div><!-- .comment-meta .commentmetadata -->
          <div class="comment-text"><?php
            comment_text(); ?>
          </div>
          <div class="reply"><?php
            comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
          </div><!-- .reply --><?php
          $striping = ($striping==='odd') ? 'even' : 'odd';
      #</li> - wordpress puts in closing tag
    }
    $striping = ($striping==='odd') ? 'even' : 'odd';
  }
}
