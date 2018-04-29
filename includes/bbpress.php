<?php

defined( 'ABSPATH' ) || exit;

/*
 *  includes/bbpress.php
 *
 */


#  bugfix from http://www.rewweb.co.uk/bbpress-wp4-fix2/
add_filter( 'bbp_show_lead_topic', '__return_true' ); // FIXME:  has this bug been fixed yet?

# remove normal content header
add_action( 'tcc_before_loop', function( $page_slug ) {
	if ( is_bbpress() ) {
		remove_action( 'fluid_content_header', 'fluid_show_content_title' );
		add_action(    'fluid_content_header', 'fluid_show_forum_title' );
		remove_action( 'fluid_content_footer', 'fluid_show_content_footer' );
	}
} );

#  do not show sidebar on forum pages
# FIXME:  make this optional
fluid()->log('loading bbpress includes file');
add_filter( 'fluid_theme_sidebar_args', function( $args ) {
	if ( is_bbpress() ) {
		$args['position'] = 'none';
fluid()->log('assign no sidebar');
	}
fluid()->log($args);
	return $args;
} );

#  force use of template-parts/content.php
add_filter( 'tcc_template-parts_root', function( $rootslug, $pageslug ) {
	if ( is_bbpress() ) {
		return 'content';
	}
	return $rootslug;
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

if ( ! function_exists( 'fluid_bbp_personal_options' ) ) {
	function fluid_bbp_personal_options( $user ) {
		$label = __( 'bbPress Topics', 'tcc-fluid' );
		$value = get_user_meta( $user->ID, 'fluid_bbp_topic_subscribe', true );
		$default = fluid_bbp_topic_subscribed_default(); ?>
		<table class="form-table">
			<tr class="fluid-bbp-topic-subscribe-wrap">
				<th scope="row">
					<?php e_esc_html( $label ); ?>
				</th>
				<td>
					<fieldset>
						<legend class="screen-reader-text">
							<?php e_esc_html( $label ); ?>
						</legend>
						<label for="fluid_bbp_topic_subscribe">
							<input
								id="fluid_bbp_topic_subscribe"
								type="checkbox"
								name="fluid_bbp_topic_subscribe"
								value="1"
								<?php checked( $value ); ?>
							/>
							<?php esc_html_e( 'Automatically subscribe to a forum topic when posting to it.', 'tcc-fluid' ); ?>
						</label>
					</fieldset>
				</td>
			</tr>
		</table><?php
	}
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
			$topic_subscribed = apply_filters( 'fluid_bbp_topic_subscribed_default', true );
		}
		return checked( $topic_subscribed, true, false );
	}
	add_filter( 'bbp_get_form_topic_subscribed', 'fluid_bbp_get_form_topic_subscribed', 10, 2 );
}

if ( ! function_exists( 'fluid_bbp_topic_subscribed_default' ) ) {
	function fluid_bbp_topic_subscribed_default( $subscribe = false ) {
#fluid()->log(0,'default1: '.$subscribe);
		$subscribe = ( tcc_content( 'bbp-subscribe', 'no' ) === 'yes' ) ? true : $subscribe;
#fluid()->log(0,'default2: '.$subscribe);
/*		if ( is_user_logged_in() ) {
			$user      = wp_get_current_user();
			$subscribe = get_user_meta( $user->ID, 'fluid_bbp_topic_subscribe', $subscribe );
fluid()->log(0,'default3: '.$subscribe);
		} //*/
		return $subscribe;
	}
	add_filter( 'fluid_bbp_topic_subscribed_default', 'fluid_bbp_topic_subscribed_default' );
}


/***   Font Sizes   ***/

if ( ! function_exists( 'fluid_bbp_options_font_size' ) ) {
	#  Add font sizes to Theme Options - Design page
	function fluid_bbp_options_font_size( $controls ) {
		$controls['bbp_font_text'] = array(
			'label'       => __( 'bbPress', 'tcc-fluid' ),
			'description' => __( 'Compatibility options for bbPress', 'tcc-fluid' ),
			'render'      => 'content',
			'sanitize_callback' => '__return_true',
		); //*/
		$controls['bbp_font_size'] = array(
			'default'     => 12,
			'label'       => __('Title','tcc-fluid'),
			'description' => __('Control the font size for titles and headers on forum pages', 'tcc-fluid' ),
			'render'      => 'spinner',
			'input_attrs' => array(
				'class' => 'text_3em_wide',
			)
		); //*/
		$controls['bbp_font_text_size'] = array(
			'default'     => 11,
			'label'       => __('Text','tcc-fluid'),
			'description' => __('Control the font size for normal text on forum pages', 'tcc-fluid' ),
			'render'      => 'spinner',
			'input_attrs' => array(
				'class' => 'text_3em_wide',
			)
		); //*/
		return $controls;
	}
	add_filter( 'fluid_customizer_controls_font', 'fluid_bbp_options_font_size' );
}

if ( ! function_exists( 'fluid_bbp_font_size' ) ) {
	#  Add font sizes to custom css
	function fluid_bbp_font_size() {
		if ( is_bbpress() ) {
			$fontsize = tcc_design( 'bbpsize', 12 );
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
				echo "$css_tags { font-size:  {$fontsize}px; }";
			}
			$fontosize1 = tcc_design( 'bbposize1', 11 );
			if ( $fontosize1 && ( ! ( $fontosize1 === 11 ) ) ) { # 11 is the default
				$css1 = array(
					'div#bbpress-forums .bbp-forum-info .bbp-forum-content',
					'div#bbpress-forums p.bbp-topic-meta',
				);
				$css_tags1 = implode( ",\n", $css1 );
				echo "$css_tags1 { font-size:  {$fontosize1}px; }";
			}
		}
	}
	add_action( 'tcc_custom_css', 'fluid_bbp_font_size' );
}


/***   Forum Title   ***/

if ( ! function_exists( 'fluid_show_forum_title' ) ) {
	function fluid_show_forum_title() { ?>
		<h1 class="page-title text-center">
			<?php bbp_forum_title(); ?>
		</h1><?php
	}
}


