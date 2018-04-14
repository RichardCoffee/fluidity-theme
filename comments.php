<?php
/*
 * File Name: comments.php
 *
 *  http://www.christianmontoya.com
 *  http://themeshaper.com/2012/11/04/the-wordpress-theme-comments-template/
 */

define( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}

who_am_i(); ?>

<div id="comments" class="article comments-area" itemprop="comment" itemscope itemtype='http://schema.org/Comment'><?php
	if ( pings_open() ) { ?>
		<p id="respond">
			<span id="trackback-link">
				<a href="<?php trackback_url() ?>" rel="trackback">
					<?php esc_html_e( 'Get a Trackback link', 'tcc-fluid' ); ?>
				</a>
			</span>
		</p><?php
	}
	if (have_comments()) { ?>
		<h2 class="comments-title"><?php
			$number = get_comments_number();
			$format = esc_html_n( 'One thought on %2$s', '%1$s thoughts on %2$s', $number, 'tcc-fluid' );
			$number = '<span itemprop="commentCount">' . $number . '</span>';
			$title  = '&ldquo;' . get_the_title() . '&rdquo;';
			printf( $format, $number, $title ); ?>
		</h2><?php
		fluid_comment_navigation(); ?>
		<ol class="commentlist"><?php
			$list = array(
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 34,
				'callback'    => 'fluid_list_comments',
			);
			wp_list_comments( $list ); ?>
		</ol><!-- .commentlist --><?php
		fluid_comment_navigation();
		if ( ! comments_open() ) { ?>
			<p class="no-comments">
				<?php esc_html_e('Comments are closed.', 'tcc-fluid' ); ?>
			</p><?php
		}
	}
	fluid_comment()->comment_form(); ?>
</div><!-- #comments -->
