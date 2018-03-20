<?php

/*
 *  includes/bbpress.php
 *
 */


	#	https://bbpress.org/forums/topic/default-bbp_topic_subscription/
	#  change default (for topic subscription when posting) to true
if ( ! function_exists( 'fluid_bbp_get_form_topic_subscribed' ) ) {
	function fluid_bbp_get_form_topic_subscribed( $checked, $topic_subscribed ) {
		#  Most of this is copied from plugins/bbpress/includes/topics/template.php,
		#    but it allows 'true' as a =default= value, rather than just over-writing
		#    the actual value, as suggested in the forum article.
		// Get _POST data
		if ( bbp_is_post_request() && isset( $_POST['bbp_topic_subscription'] ) ) {
			$topic_subscribed = (bool) $_POST['bbp_topic_subscription'];
		// Get edit data
		} elseif ( bbp_is_topic_edit() || bbp_is_reply_edit() ) {
			// Get current posts author
			$post_author = bbp_get_global_post_field( 'post_author', 'raw' );
			// Post author is not the current user
			if ( bbp_get_current_user_id() !== $post_author ) {
				$topic_subscribed = bbp_is_user_subscribed_to_topic( $post_author );
			// Post author is the current user
			} else {
				$topic_subscribed = bbp_is_user_subscribed_to_topic( bbp_get_current_user_id() );
			}
		// Get current status
		} elseif ( bbp_is_single_topic() ) {
			$topic_subscribed = bbp_is_user_subscribed_to_topic( bbp_get_current_user_id() );
		// No data
		} else {
			$topic_subscribed = apply_filters( 'fluid_bbp_topic_subscribed_default', true );
		}
		return checked( $topic_subscribed, true, false );
	}
	add_filter( 'bbp_get_form_topic_subscribed', 'fluid_bbp_get_form_topic_subscribed', 10, 2 );
}

#  bugfix from http://www.rewweb.co.uk/bbpress-wp4-fix2/
add_filter('bbp_show_lead_topic', '__return_true'); // FIXME:  has this bug been fixed yet?

# remove normal content header
add_action( 'tcc_before_loop', function( $page_slug ) {
	if ( is_bbpress() ) {
		remove_filter( 'fluid_content_header', 'fluid_show_content_title' );
		add_filter(    'fluid_content_header', 'fluid_show_forum_title' );
	}
});

#  do not show sidebar on forum pages
add_filter('tcc_theme_sidebar_args', function( $args ) {
	if ( is_bbpress() ) {
		$args['position'] = 'none';
	}
	return $args;
});

#  force use of template-parts/content.php
add_filter('tcc_template-parts_root', function( $rootslug, $pageslug ) {
	if ( is_bbpress() ) {
		return 'content';
	}
}, 10, 2);

#  Change font sizes
if ( ! function_exists( 'fluidity_bbp_font_size' ) ) {
	function fluidity_bbp_font_size() {
		if ( is_bbpress() ) {
			$fontsize = tcc_design( 'bbpsize' );
			if ( $fontsize && ( ! ( $fontsize === 12 ) ) ) { # 12 is the default
				$css = array(
					'div#bbpress-forums',
					'div#bbpress-forums div.bbp-breadcrumb',
					'div#bbpress-forums div.bbp-template-notice p',
					'div#bbpress-forums ul.bbp-lead-topic',
					'div#bbpress-forums ul.bbp-topics',
					'div#bbpress-forums ul.bbp-forums',
					'div#bbpress-forums ul.bbp-replies',
					'div#bbpress-forums ul.bbp-search-results',
				);
				$css_tags = implode( ",\n", $css );
				echo "\n$css_tags {\n";
				echo "	font-size:  {$fontsize}px;\n";
				echo "}\n";
			}
			$fontosize1 = tcc_design( 'bbposize1' );
			if ( $fontosize1 && ( ! ( $fontosize1 === 11 ) ) ) { # 11 is the default
				$css1 = array(
					'div#bbpress-forums .bbp-forum-info .bbp-forum-content',
					'div#bbpress-forums p.bbp-topic-meta',
				);
				$css_tags1 = implode( ",\n", $css1 );
				echo "\n$css_tags1 {\n";
				echo "	font-size:  {$fontosize1}px;\n";
				echo "}\n";
			}
			echo '#subscription-toggle { padding-left: 2em; }' . "\n";
		}
	}
	add_action( 'tcc_custom_css', 'fluidity_bbp_font_size' );
}

# FIXME: look for an existing bbpress function
if ( ! function_exists( 'fluid_get_forum_title' ) ) {
	function fluid_get_forum_title() {
		$title = __( 'Forum Title', 'tcc-fluid' );
		if ( get_page_slug() === 'forum' ) {
			$title = __( 'Welcome to the Forums!', 'tcc-fluid' );
		} else {
			$title = sprintf( _x( '%s Forum', 'string is the forum/post title', 'tcc-fluid' ), get_the_title( get_the_ID() ) );
		}
		return $title; # apply_filters( 'fluid_get_forum_title', $title );
	}
}

if ( ! function_exists( 'fluid_show_forum_title' ) ) {
	function fluid_show_forum_title() { ?>
		<h1 class="page-title text-center">
			<?php echo fluid_get_forum_title(); ?>
		</h1><?php
	}
}


