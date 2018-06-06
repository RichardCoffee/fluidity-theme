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
          esc_html_e('Comment navigation','tcc-fluid'); ?>
        </h2>
        <div class="nav-previous"><?php
          previous_comments_link('&larr; '.esc_html__('Older Comments','tcc-fluid')); ?>
        </div>
        <div class="nav-next"><?php
          next_comments_link( esc_html__('Newer Comments','tcc-fluid').' &rarr;'); ?>
        </div>
      </nav><!-- #comment-nav-above --><?php
    }
  }
}

if ( ! function_exists( 'disable_website_field' ) ) {
	#	https://github.com/taniarascia/wp-functions
	function fluid_disable_website_field( $fields ) {
		if ( isset( $fields['url'] ) ) {
			unset( $fields['url'] );
		}
		return $fields;
	}
	add_filter('comment_form_default_fields', 'fluid_disable_website_field');
}

/**
 * list comments
 *
 * @link http://www.christianmontoya.com
 * @link http://themeshaper.com/2012/11/04/the-wordpress-theme-comments-template/
 * @param WP_Comment $comment
 * @param array $args
 * @param integer $depth
 */
if (!function_exists('fluid_list_comments')) {
	function fluid_list_comments( WP_Comment $comment, array $args, $depth ) {
fluid()->log($args);
		static $striping = 'odd';
		$GLOBALS['comment'] = $comment;
		$string    = esc_html_x( '%1$s by %2$s on %3$s', 'the comment type; author name; date of comment', 'tcc-fluid' );
		$type_arr  = array(
			'comment'   => esc_html__( 'Comment', 'tcc-fluid' ),
			'trackback' => esc_html__( 'Trackback', 'tcc-fluid' ),
			'pingback'  => esc_html__( 'Pingback', 'tcc-fluid' )
		);
		$comm_type = $comment->comment_type;
		$attrs = array(
			'id'    => 'comment-' . $comment->comment_ID,
			'class' => get_comment_class( '', $comment ),
		);
		switch ( $comm_type ) {
			case 'pingback' :
			case 'trackback' :
				array_merge( $attrs['class'], [ 'post', $comm_type, "stripe-$striping" ] );
				fluid()->tag( 'li', $attrs );
				printf( $string, $type_arr[ $comm_type ], get_comment_author_link(), get_comment_date( '', $comment->comment_ID ) );
				break;
			default: # comment
				if ( empty( $comm_type ) ) {
					$comm_type = 'comment';
				}
				$css = ( get_the_author_meta( 'user_email' ) === get_comment_author_email() ) ? 'post-author' : "striping-$striping";
				array_merge( $attrs['class'], [ $css ] );
				fluid()->tag( 'li', $attrs ); ?>
				<div class="comment-author vcard">
					<span class="pull-left"><?php
						echo get_avatar( $comment, 34 ); ?>
					</span>&nbsp;<?php
					printf( $string, $type_arr[ $comm_type ], get_comment_author_link(), get_comment_date( '', $comment->comment_ID ) ); ?>
				</div><!-- .comment-author .vcard --><?php
				if ( $comment->comment_approved === '0' ) { ?>
					<em><?php
						esc_html_e( 'Your comment is awaiting moderation.', 'tcc-fluid' ); ?>
					</em>
					<br /><?php
				} ?>
				<div class="comment-text"><?php
					comment_text(); ?>
				</div>
				<div class="reply"><?php
					comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div><!-- .reply --><?php
				$striping = ( $striping === 'odd' ) ? 'even' : 'odd';
		}
		$striping = ( $striping === 'odd' ) ? 'even' : 'odd';
	}
}
