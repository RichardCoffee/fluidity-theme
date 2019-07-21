<?php
/*
 * File Name: comments.php
 *
 * @package Fluidity
 * @subpackage Comments
 * @since 20150516
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/comments.php
 * @link http://www.christianmontoya.com
 * @link http://themeshaper.com/2012/11/04/the-wordpress-theme-comments-template/
 */
defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}

who_am_i(); ?>

<div id="comments" class="article comments-area"><?php
	if ( pings_open() ) { ?>
		<p id="respond">
			<span id="trackback-link">
				<a href="<?php trackback_url() ?>" rel="trackback">
					<?php esc_html_e( 'Get a Trackback link', 'tcc-fluid' ); ?>
				</a>
			</span>
		</p><?php
	}
	if ( have_comments() ) { ?>
		<h2 class="comments-title"><?php
			$number = get_comments_number();
			$format = _n( 'One thought on %2$s', '%1$s thoughts on %2$s', $number, 'tcc-fluid' );
			$number = '<span itemprop="commentCount">' . $number . '</span>';
			$title  = '&ldquo;' . get_the_title() . '&rdquo;';
			echo wp_kses( sprintf( $format, $number, $title ), fluid()->kses() ); ?>
		</h2>
		<ul class="comment-list">
			<hr class="comment-separator"><?php
			$list = array(
				'short_ping'  => true,
				'avatar_size' => 34,
				'callback'    => 'fluid_list_comments',
			);
			wp_list_comments( $list ); ?>
		</ul><!-- .comment-list --><?php
		new TCC_Theme_CommentNav;
#		fluid_comment_navigation();
		if ( ! comments_open() ) { ?>
			<p class="no-comments">
				<?php esc_html_e('Comments are closed.', 'tcc-fluid' ); ?>
			</p><?php
		}
	}
	fluid_comment()->comment_form(); ?>
</div><!-- #comments -->
