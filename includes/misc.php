<?php
/**
 *  various miscellaneous functions
 *
 */
defined( 'ABSPATH' ) || exit;

// Only show the front page section when on the front page - source?
function contextual_static_front_page_section( $wp_customize ) {
	$wp_customize->get_section( 'static_front_page' )->active_callback = 'is_front_page';
}
add_action( 'customize_register', 'contextual_static_front_page_section', 11 );

/**
 *  format a phone number, allows for other locale formats with corresponding functions
 *
 * @since 20180901
 * @link https://thebarton.org/php-format-phone-number-function/
 * @param string $phone
 * @param string|array $country
 * @return string
 */
if ( ! function_exists( 'fluid_format_phone_number' ) ) {
	function fluid_format_phone_number( $phone, $country = 'us' ) {
		$function = 'fluid_format_phone_number_' . sanitize_key( $country ); // handles everything but dashes, which will error out
		if ( function_exists( $function ) ) {
			$phone = $function( $phone );
		} else if ( is_callable( $country ) ) {
			if ( $country === (array)$country ) {
				$phone = call_user_func_array( $country, $phone);
			} else {
				$phone = call_user_func( $country, $phone );
			}
		}
		return $phone;
	}
}

/**
 *  format a phone using us standard formats
 *
 * @since 20180901
 * @link https://thebarton.org/php-format-phone-number-function/
 * @param string $phone
 * @return string
 */
if ( ! function_exists( 'fluid_format_phone_number_us' ) ) {
	function fluid_format_phone_number_us( $phone ) {
		// note: making sure we have something
		if ( ! isset( $phone{3} ) ) { return ''; }
		// note: strip out everything but numbers
		$phone  = preg_replace( "/[^0-9]/", "", $phone );
		$length = strlen( $phone );
		switch( $length ) {
			case 7:
				$phone = preg_replace( "/([0-9]{3})([0-9]{4})/", "$1-$2", $phone );
				break;
			case 10:
				$phone = preg_replace( "/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $phone );
				break;
			case 11:
				$phone = preg_replace( "/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "$1($2) $3-$4", $phone );
				break;
			default:
		}
		return $phone;
	}
}

if ( ! function_exists( 'fluidity_post_class' ) ) {
	function fluidity_post_class( $classes, $added, $postid ) {
		$current = get_page_slug();
		$classes = apply_filters( "post_class_$current", $classes, $added, $postid );
		return $classes;
	}
	add_filter( 'post_class', 'fluidity_post_class', 11, 3 );
}

/**
 *  simple query template
 *
 * @param array $args list of arguements for the query
 * @param string $template passed as first get_template_part() parameter
 * @param string $slug passed as second get_template_part() parameter
 */
if ( ! function_exists( 'fluidity_show_query' ) ) {
	function fluidity_show_query( array $args, $template, $slug = '' ) {
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part( $template, $slug );
			}
		}
		wp_reset_postdata();
	}
}

/**
 *  show default theme social icons
 *
 */
if ( ! function_exists( 'fluidity_social_icons' ) ) {
	function fluidity_social_icons( $args = array() ) {
		$icons = get_option( 'tcc_options_social', [ 'active' => 'no' ] );
		if ( $icons['active'] === 'yes' ) {
			if ( has_action( 'fluidity_social_icons' ) ) {
				do_action( 'fluidity_social_icons', $args );
			} else {
				$size   = ( isset( $icons['size'] ) )   ? $icons['size']   : '';
				$target = ( isset( $icons['target'] ) ) ? $icons['target'] : 'target';
				$single = ( isset( $icons['single'] ) ) ? $icons['single'] : 'individual';
				$color  = ( isset( $icons['color']  ) ) ? $icons['color']  : 'blue';
#				unset( $icons['active'], $icons['target'], $icons['size'] );
				$social = array(); // FIXME: find another way to do this
				foreach( $icons as $field => $value ) {
					$pos = strpos( $field, '_color' );
					if ( $pos ) {
						$split = explode( '_', $field );
						$social[ $split[0] ]['color'] = $value;
					} else {
						$social[ $field ]['link'] = $value;
					}
				}
				$insta  = new TCC_Options_Social;
				$layout = $insta->options_layout(); ?>
				<span class='fluidity-social-icons'><?php
					foreach( $social as $key => $set ) {
						if ( empty( $layout[ $key ]['color'] ) ) { continue; }
						if ( empty( $set['link'] ) ) { continue; }
						$tool = sprintf( _x( 'See us on %s', '(social) website name', 'tcc-fluid' ), $layout[ $key ] ['label'] );
						$fawe = 'fa'; // ( $key === 'rss' ) ? 'fas' : 'fab';
						$attr = array(
							'class' => "$fawe fa-fw fa-$key-square $size",
							'href'  => $set['link'],
							'rel'   => 'nofollow',
							'style' => 'color:' . ( ( $single === 'single' ) ? $color : $set['color'] ) . ';',
							'title' => ( $key === 'rss' ) ? esc_html__( 'Subscribe to our RSS feed', 'tcc-fluid' ) : $tool, // TODO: option to change this text
							'target'=> ( $target === 'target' ) ? "fluidity_$key" : "_blank",
						);
						fluid()->element( 'a', $attr, ' ' );
					} ?>
				</span><?php
			}
		}
	} //*/
}

/**
 *  show php errors in alert box
 *
 * @since 20180824
 * @link https://code.tutsplus.com/articles/display-php-errors-as-wordpress-admin-alerts--wp-23993
 * @param integer $errno
 * @param string $errstr
 * @param string $errfile
 * @param integer $errline
 * @param array $errcontext
 */
if ( ! function_exists( 'fluid_php_error_alerts' ) ) {
	function fluid_php_error_alerts( $errno, $errstr, $errfile = '', $errline = 0, $errcontext = array() ) {
		global $php_error_messages;
		$errorType = array (
			E_ERROR             => 'ERROR',
			E_CORE_ERROR        => 'PHP CORE ERROR',
			E_COMPILE_ERROR     => 'COMPILE ERROR',
			E_USER_ERROR        => 'PROGRAMMER ERROR',
			E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR',
			E_WARNING           => 'WARNING',
			E_CORE_WARNING      => 'PHP CORE WARNING',
			E_COMPILE_WARNING   => 'COMPILE WARNING',
			E_USER_WARNING      => 'USER WARNING',
			E_NOTICE            => 'NOTICE',
			E_USER_NOTICE       => 'USER NOTICE',
			E_DEPRECATED        => 'DEPRECATED',
			E_USER_DEPRECATED   => 'USER DEPRECATED',
			E_PARSE             => 'PARSING ERROR'
		);
		if ( array_key_exists( $errno, $errorType ) ) {
			$errname = $errorType[ $errno ];
		} else {
			$errname = 'UNKNOWN ERROR';
		}
		$format = esc_html__( '%1$s Error: [%2$s] %3$s, %4$s on line %5$s', 'tcc-fluid' );
		ob_start();?>
		<div class="notice error">
			<p><?php
				printf(
					$format,
					'<strong>' . $errname . '</strong>',
					'<strong>' . $errno   . '</strong>',
					$errstr,
					'<strong>' . $errfile . '</strong>',
					'<strong>' . $errline . '</strong>'
				); ?>
			</p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php
					esc_html_e( 'Dismiss this notice.', 'tcc-fluid' ); ?>
				</span>
			</button>
		</div><?php
		$error_message = ob_get_clean();
		fluid()->log( $errstr, $error_message, 'stack' );
		if ( is_admin() ) {
			echo $error_message;
		} else {
			if ( empty( $php_error_messages ) ) {
				add_action( 'fluid_php_error_messages', function() {
					global $php_error_messages;
					echo $php_error_messages;
				} );
			}
			$php_error_messages .= $error_message;
		}
		if ( in_array( $errno, [ E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR ] ) ) {
			if ( ! is_admin() ) {
				echo $error_message;
			}
			die();
		}
	}
	if ( WP_DEBUG ) {
#		set_error_handler( 'fluid_php_error_alerts', E_ERROR ^ E_CORE_ERROR ^ E_COMPILE_ERROR ^ E_USER_ERROR ^ E_RECOVERABLE_ERROR ^ E_WARNING ^ E_CORE_WARNING ^ E_COMPILE_WARNING ^ E_USER_WARNING ^ E_NOTICE ^ E_USER_NOTICE ^ E_DEPRECATED ^ E_USER_DEPRECATED ^ E_PARSE );
	}
}

/**
 *  add monthly to wp cron schedules
 *
 * @param array $schedules
 * @return array
 */
if ( ! function_exists( 'fluid_survey_cron_schedules' ) ) {
	function fluid_survey_cron_schedules( $schedules = array() ) {
		if ( ! isset( $schedules['monthly'] ) ) {
			$schedules['monthly'] = array( 'interval' => MONTH_IN_SECONDS, 'display' => __( 'Once Monthly', 'tcc-fluid' ) );
		}
		return $schedules;
	}
	add_filter( 'cron_schedules', 'fluid_survey_cron_schedules' );
}

/* FIXME
if (!function_exists('remove_post_revisions')) {

DELETE a,b,c FROM wp_posts a
LEFT JOIN wp_term_relationships b ON (a.ID = b.object_id)
LEFT JOIN wp_postmeta c ON (a.ID = c.post_id)
WHERE a.post_type = 'revision'

} //*/

/**
 *  helper function - alias
 *
 * @param string $title
 * @return string
 */
if ( ! function_exists( 'has_page' ) ) {
	function has_page( $title ) {
		return page_exists( $title );
	}
}

/**
 *  check to see if a page exists
 *
 * @link http://www.tammyhartdesigns.com/tutorials/wordpress-how-to-determine-if-a-certain-page-exists
 * @link string $title
 * @return bool
 */
if ( ! function_exists( 'page_exists' ) ) {
	function page_exists( $title ) {
		$pages = get_pages();
		$search = sanitize_title( $title );
		foreach ( $pages as $page ) {
			if ( $page->post_name === $search ) {
				return true; // FIXME: return url? -> home_url( "/$search/" );
			}
		}
		return false;
	}
}

/**
 *  redirect search page to post page if one result was returned by the search
 *
 * @link http://www.hongkiat.com/blog/wordpress-tweaks-for-post-management/
 */
if ( ! function_exists( 'single_search_result' ) ) {
	function single_search_result() {
		global $wp_query;
		if ( is_search() || is_archive() ) {
			if ( $wp_query->post_count === 1 ) {
				wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );
			}
		}
	}
	add_action( 'template_redirect', 'single_search_result' );
}

/**
 *  creates an array that contains information about an image
 *
 * @link http://stackoverflow.com/questions/25974196/how-to-get-wp-gallery-image-captions
 * @parameter int $attachment_id
 * @return array
 */
if ( ! function_exists( 'wp_get_attachment' ) ) {
	function wp_get_attachment( $attachment_id ) {
		$attachment = get_post( $attachment_id );
		$metadata   = get_post_meta( $attachment_id );
		$img_data   = unserialize( $metadata['_wp_attachment_metadata'][ 0 ] );
		$data = array(
			'alt'         => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
			'caption'     => $attachment->post_excerpt,
			'description' => $attachment->post_content,
			'href'        => get_permalink( $attachment->ID ),
			'sizes'       => attachment_sizes( $img_data, url_stem( $attachment->guid ) ),
			'src'         => $attachment->guid,
			'title'       => $attachment->post_title
		);
		return $data;
	}
}

/**
 *  returns an array of image sizes available
 *
 * @param array $data
 * @param string $stem
 * @return array
 */
if ( ! function_exists( 'attachment_sizes' ) ) {
	function attachment_sizes( $data, $stem ) {
		$sizes = array();
		foreach( $data['sizes'] as $size => $data ) {
			$sizes[ $size ] = $stem . $data['file'];
		}
		return $sizes;
	}
}

/**
 *  returns the stem for a url
 *
 * @param string $url
 * @return string
 */
if ( ! function_exists( 'url_stem' ) ) {
	function url_stem( $url ) {
		$pos  = strrpos( $url, '/' );
		$stem = substr( $url, 0, $pos + 1 );
		return $stem;
	}
}

/**
 *  generates a holiday greeting
 *
 * @link http://stackoverflow.com/questions/14907561/how-to-get-date-for-holidays-using-php
 * @param string $default
 * @return string
 */
if (!function_exists('tcc_holiday_greeting')) {
	function tcc_holiday_greeting( $default = '' ) {
		$default = ( (bool) $default ) ? $default : __( 'Welcome', 'tcc-fluid' );
		$year = date('Y');
		$MLK  = date( 'm-d', strtotime( "january $year third monday" ) ); //marthin luthor king day
		$PD   = date( 'm-d', strtotime( "february $year third monday" ) ); //presidents day
		$Est  = date( 'm-d', easter_date( $year ) ); // easter
		$MD   = date( 'm-d', strtotime( "may $year last monday" ) ); // memorial day
		$LD   = date( 'm-d', strtotime( "september $year first monday" ) );  //labor day
		$CD   = date( 'm-d', strtotime( "october $year second monday" ) ); //columbus day
		$TG   = date( 'm-d', strtotime( "november $year last thursday" ) ); // thanksgiving
		$date = date( 'm-d' );
		switch( $date ) {
			case '01-01':
				$message = __( 'Happy New Year', 'tcc-fluid' );
				break;
			case $MLK:
				$message = __( 'Martin Luthor King Day', 'tcc-fluid' );
				break;
			case '02-14':
				$message = __( "Valentine's Day", 'tcc-fluid' );
				break;
			case $PD:
				$message = __( "Presidents' Day", 'tcc-fluid' );
				break;
			case $Est:
				$message = __( 'Happy Easter', 'tcc-fluid' ); // happy easter?  wtf?
				break;
			case $MD:
				$message = __( 'Memorial Day', 'tcc-fluid' );
				break;
			case '07-04':
				$message = __( 'Fourth of July', 'tcc-fluid' );
				break;
			case $LD:
				$message = __( 'Labor Day', 'tcc-fluid' );
				break;
			case $CD:
				$message = __( 'Columbus Day', 'tcc-fluid' );
				break;
			case $TG:
				$message = __( 'Happy Thanksgiving', 'tcc-fluid' );
				break;
			case '11-11':
				$message = __( "Veteran's Day", 'tcc-fluid' );
				break;
			case '12-25':
				$message = __( 'Merry Christmas', 'tcc-fluid' );
				break;
			default:
				$message = ( get_current_user_id() === 1 ) ? __( 'Your Royal Highness', 'tcc-fluid' ) : $default;
		}
		return $message;
	}
}
/*
function stupid_theme_checker () {
	wp_link_pages();
	posts_nav_link();
	paginate_links();
	#	TCC_Theme_Pagination
	the_posts_pagination();
	the_posts_navigation();
	next_posts_link();
	previous_posts_link();
	add_theme_support( "custom-header", $args );
} //*/
