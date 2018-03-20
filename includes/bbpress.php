<?php

/*
 *  includes/bbpress.php
 *
 */


#  bugfix from http://www.rewweb.co.uk/bbpress-wp4-fix2/
add_filter( 'bbp_show_lead_topic', '__return_true' ); // FIXME:  has this bug been fixed yet?

# remove normal content header
add_action( 'tcc_before_loop', function( $page_slug ) {
	if ( is_bbpress() ) {
		remove_filter( 'fluid_content_header', 'fluid_show_content_title' );
		add_filter(    'fluid_content_header', 'fluid_show_forum_title' );
	}
} );

#  do not show sidebar on forum pages
add_filter( 'tcc_theme_sidebar_args', function( $args ) {
	if ( is_bbpress() ) {
		$args['position'] = 'none';
	}
	return $args;
} );

#  force use of template-parts/content.php
add_filter( 'tcc_template-parts_root', function( $rootslug, $pageslug ) {
	if ( is_bbpress() ) {
		return 'content';
	}
}, 10, 2 );

add_action( 'tcc_custom_css', function() {
	if ( is_bbpress() ) {
		echo "\n#subscription-toggle {\n\tpadding-left: 2em; }\n";
	}
} );


/***   Topic Subscription   ***/

if ( ! function_exists( 'fluid_bbp_options_topic_subscription' ) ) {
	function fluid_bbp_options_topic_subscription( $layout ) {
		$layout['bbp'] = array(
			'label'  => __( 'bbPress', 'tcc-fluid' ),
			'text'   => __( 'Compatibility options for bbPress', 'tcc-fluid' ),
			'render' => 'title',
		);
		$layout['bbp-subscribe'] = array(
			'label'  => __( 'Default Topic Subscription', 'tcc-fluid' ),
			'text'   => __( 'Check for topic subscription default value', 'tcc-fluid' ),
			'help'   => __( 'If this box is checked, then the checkbox on the comment form, indicating that the commenter will receive email notifications, will also be checked.', 'tcc-fluid' ),
			'render' => 'checkbox',
		);
		return $layout;
	}
	add_filter( 'tcc_content_options_layout', 'fluid_bbp_options_topic_subscription' );
}

#  https://bbpress.org/forums/topic/default-bbp_topic_subscription/
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
		} elseif ( bbp_is_topic_edit() || bbp_is_reply_edit() || bbp_is_single_topic() ) {
			$topic_subscribed = bbp_is_user_subscribed_to_topic( bbp_get_current_user_id() );
		// No data
		} else {
			$default = tcc_content( 'bbp-subscribe', true );
			$topic_subscribed = apply_filters( 'fluid_bbp_topic_subscribed_default', $default );
		}
		return checked( $topic_subscribed, true, false );
	}
	add_filter( 'bbp_get_form_topic_subscribed', 'fluid_bbp_get_form_topic_subscribed', 10, 2 );
}


/***   Font Sizes   ***/

if ( ! function_exists( 'fluid_bbp_options_font_size' ) ) {
	#  Add font sizes to Theme Options - Design page
	function fluid_bbp_options_font_size( $layout ) {
		$layout['bbp'] = array(
			'label'   => __( 'bbPress', 'tcc-fluid' ),
			'text'    => __( 'Compatibility options for bbPress', 'tcc-fluid' ),
			'render'  => 'title',
		);
		$layout['bbpsize'] = array(
			'default' => 12,
			'label'   => __('Title','tcc-fluid'),
			'text'    => __('Control the font size for titles and headers on forum pages', 'tcc-fluid' ),
			'stext'   => _x( 'px', "abbreviation for 'pixel' - not sure this even needs translating...", 'tcc-fluid' ),
			'render'  => 'text',
			'divcss'  => 'tcc_text_3em',
		);
		$layout['bbposize1'] = array(
			'default' => 11,
			'label'   => __('Text','tcc-fluid'),
			'text'    => __('Control the font size for normal text on forum pages', 'tcc-fluid' ),
			'stext'   => _x( 'px', "abbreviation for 'pixel' - not sure this even needs translating...", 'tcc-fluid' ),
			'render'  => 'text',
			'divcss'  => 'tcc_text_3em',
		);
		return $layout;
	}
	add_filter( 'tcc_design_options_layout', 'fluid_bbp_options_font_size' );
}

if ( ! function_exists( 'fluid_bbp_font_size' ) ) {
	#  Add font sizes to custom css
	function fluid_bbp_font_size() {
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
				echo "\n$css_tags {\n\tfont-size:  {$fontsize}px;\n}\n";
			}
			$fontosize1 = tcc_design( 'bbposize1' );
			if ( $fontosize1 && ( ! ( $fontosize1 === 11 ) ) ) { # 11 is the default
				$css1 = array(
					'div#bbpress-forums .bbp-forum-info .bbp-forum-content',
					'div#bbpress-forums p.bbp-topic-meta',
				);
				$css_tags1 = implode( ",\n", $css1 );
				echo "\n$css_tags1 {\n\tfont-size:  {$fontosize1}px;\n}\n";
			}
		}
	}
	add_action( 'tcc_custom_css', 'fluid_bbp_font_size' );
}


/***   Forum Title   ***/

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


