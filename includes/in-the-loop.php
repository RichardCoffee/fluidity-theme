<?php
/**
 *  File:  includes/in-the-loop.php
 *
 *  All functions in this file expect to be run inside the WordPress loop
 *
 * @since 20160830
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/includes/in-the-loop.php
 */
defined( 'ABSPATH' ) || exit;

/**
 *  Controls the second parameter used for get_template_part calls.
 *
 * @since 20160830
 * @param string $page
 * @uses get_post_format()
 * @uses get_post_type()
 * @uses apply_filters()
 * @return string
 */
if ( ! function_exists( 'fluid_content_slug' ) ) {
	function fluid_content_slug( $page = 'single' ) {
		$slug = ( $format = get_post_format() ) ? $format : get_post_type();
		$slug = ( $slug === 'page' )            ? $page   : $slug;
		$slug = apply_filters( "tcc_content_slug",       $slug, $page );
		$slug = apply_filters( "tcc_content_slug_$page", $slug, $page );
		return $slug;
	}
}

/**
 *  Control the appearence of the edit post link.
 *
 * @since 20160830
 */
if ( ! function_exists( 'fluid_edit_post_link' ) ) {
	function fluid_edit_post_link() {
		$text   = '{ ' . _x( 'Edit', 'verb', 'tcc-fluid' ) . ' }';
		$before = '&nbsp;<span class="edit-link small block">';
		$after  = '</span>';
		edit_post_link( $text, $before, $after );
	}
}

/**
 *  Exercise some control over the anchor for the edit post link.
 *
 * @since 20180328
 * @param string $link  Anchor tag for the edit link.
 * @param int    $id    Post ID.
 * @param string $text  Anchor text.
 * @return string       Anchor tag for the edit link.
 */
if ( ! function_exists( 'fluid_edit_post_link_anchor' ) ) {
	function fluid_edit_post_link_anchor( $link, $id, $text ) {
		$attrs = array(
			'class'  => 'post-edit-link',
			'href'   => get_edit_post_link( $id ),
			'target' => '_blank',
			'title'  => sprintf( _x( 'Edit %s', 'Name of current post', 'tcc-fluid' ), get_the_title() ),
		);
		return fluid()->get_element( 'a', $attrs, $text );
	}
	add_filter( 'edit_post_link', 'fluid_edit_post_link_anchor', 10, 3 );
}

/**
 *  Display text for excerpt.
 *
 * @since 20180813
 */
if ( ! function_exists( 'fluid_excerpt' ) ) {
	function fluid_excerpt() {
		if ( get_theme_mod( 'content_excerpt', 'excerpt' ) === 'content' ) {
			the_content();
		} else {
			the_excerpt();
		}
	}
}

/**
 *  Display excerpt header.
 *
 * @since 20180807
 */
if ( ! function_exists( 'fluid_excerpt_header' ) ) {
	function fluid_excerpt_header() {
		fluid()->element( 'h2', [ 'class' => 'text-center', 'itemprop' => 'headline' ], fluid_get_post_title( 40 ), true );
		if ( get_theme_mod( 'content_exdate', 'show' ) === 'show' ) { ?>
			<h3 class="text-center"><?php
				$postdate = get_post_meta( get_the_ID(), 'postdate_display', true );
				$postdate = ( $postdate ) ? $postdate : get_theme_mod( 'content_postdate', 'original' );
				$postdate = ( in_array( $postdate, [ 'both', 'modified' ] ) ) ? 'modified' : $postdate;
				fluid_post_date( $postdate, false ); ?>
			</h3><?php
		}
	}
	add_action( 'fluid_excerpt_header', 'fluid_excerpt_header' );
}

/**
 *  Control excerpt length.
 *
 * @since 20170126
 * @param int  $length  Excerpt length.
 * @return int          Modified length for excerpt.
 */
if ( ! function_exists( 'fluid_excerpt_length' ) ) {
	function fluid_excerpt_length( $length ) {
		$len = intval( get_theme_mod( 'content_exlength', 55 ) );
		return ( $len ) ? $len : $length;
	}
	add_filter( 'excerpt_length', 'fluid_excerpt_length', 11 );
}

/**
 *  Returns a string intended to be used for the title attribute for post links in excerpts
 *
 * @since 20180328
 * @return string  Post link title.
 */
if ( ! function_exists( 'fluid_excerpt_link_tooltip' ) ) {
	function fluid_excerpt_link_tooltip() {
		$tooltip = sprintf( esc_html_x( 'Read All About %s', 'the post title', 'tcc-fluid' ), fluid_title() );
		return $tooltip; # apply_filters( 'fluid_excerpt_link_tooltip', $tooltip );
	}
}

/**
 *  Get the post title.
 *
 * @since 20200323
 * @param int  $max     Maximum length of the title.
 * @param bool $anchor  Should the title be wrapped in an anchor tag?
 */
if ( ! function_exists( 'fluid_get_post_title' ) ) {
	function fluid_get_post_title( $max = 0, $anchor = true ) {
		$anchor = ( is_single() || is_page() ) ? false : $anchor;
		if ( $anchor ) {
			$attrs = array(
				'href'  => get_the_permalink(),
				'rel'   => 'bookmark',
				'title' => fluid_excerpt_link_tooltip()
			);
			$html = fluid()->get_element( 'a', $attrs, fluid_title( $max ), true );
		} else {
			$html = fluid_title( $max );
		}
		return $html;
	}
}


/**
 *  Check of the next post exists.
 *
 * @since 20160830
 * @return bool  Is there a next post?
 */
if (!function_exists('fluid_next_post_exists')) {
  function fluid_next_post_exists() {
    global $wp_query;
    return (bool)( $wp_query->current_post + 1 < $wp_query->post_count );
  }
}

/**
 *  Show the post publish and/or last edit date.
 *
 * @since 20160830
 * @param string $postdate  Type of date to display
 * @param bool   $showboth  Show both dates, if applicable.
 * @return bool             Is there a second date?
 */
if ( ! function_exists( 'fluid_post_date' ) ) {
	function fluid_post_date( $postdate = '', $showboth = true ) {
		if ( empty( $postdate ) ) {
			$postdate = get_post_meta( get_the_ID(), 'postdate_display', true );
			$postdate = ( empty( $postdate ) || ( in_array( $postdate, [ 'defaultpd', 'default' ] ) ) ) ? get_theme_mod( 'content_postdate', 'original' ) : $postdate;
		}
		if ( ! ( $postdate === 'none' ) ) {
			$showboth = ( $postdate === 'both' );
			$default  = esc_html_x( 'Posted on %1$s by %2$s', '1: formatted date string, 2: author name', 'tcc-fluid' );
			$date     = get_the_date();
			if ( in_array( $postdate, [ 'both', 'modified' ] ) && ( ( get_the_modified_date( 'U' ) - DAY_IN_SECONDS ) > ( get_the_date( 'U' ) ) ) ) {
				$default  = esc_html_x( 'Last modified on %1$s by %2$s', '1: formatted date string, 2: author name', 'tcc-fluid' );
				$date     = get_the_modified_date();
			}
			$string = apply_filters( 'fluid_post_date_sprintf', $default, $postdate, $showboth );
			$author = ( is_single() ) ? get_the_author_posts_link() : get_the_author();
			printf( $string, $date, $author );
		}
		return $showboth;
	}
}

/**
 *  Filters for original date.
 *
 * @since 20180417
 * @param string $format    Text for date format.
 * @param string $postdate  Date format desired.
 * @param bool   $showboth  Are both formats desired?
 * @return string           Text for date format.
 */
if ( ! function_exists( 'fluid_post_date_sprintf' ) ) {
	function fluid_post_date_sprintf( $format, $postdate, $showboth ) {
		if ( $showboth && ( $postdate === 'original' ) ) {
			$format = esc_html_x( 'Originally posted on %s', 'wordpress formatted date string', 'tcc-fluid' );
		}
		return $format;
	}
	add_filter( 'fluid_post_date_sprintf', 'fluid_post_date_sprintf', 10, 3 );
}

/**
 *  Display the post meta data.
 *
 * @since 20161231
 */
if ( ! function_exists( 'fluid_postmetadata' ) ) {
	function fluid_postmetadata() { ?>
		<div class="article margint1e noprint">
			<div class="postmetadata">
				<hr class="postmetadata-separator"><?php
				if ( has_tag() ) {
					fluid_post_tags();
					echo '<hr class="postmetadata-separator">';
				}
				$cat_list = get_the_category_list();
				if ( ! empty( $cat_list ) ) {  #  wordpress's has_category() does not always return a correct value - wtf?
					$cat_list = str_replace( '	<li>', '<li class="btn-fluidity">', $cat_list );
					printf( esc_html_x( 'Categories: %s', 'string - one or more categories', 'tcc-fluid' ), $cat_list );
					echo '<hr class="postmetadata-separator">';
				} ?>
			</div>
		</div><?php
	}
}

/**
 *  Display a separator between excerpts.
 *
 * @since 20161206
 * @param string $slug the page slug
 */
if ( ! function_exists( 'fluid_post_separator' ) ) {
	function fluid_post_separator( $slug ) {
		if ( fluid_next_post_exists() ) {
			if ( has_action( "fluid_post_separator_$slug" ) ) {
				do_action( "fluid_post_separator_$slug" );
			} else if ( has_action( 'fluid_post_separator' ) ) {
				do_action( 'fluid_post_separator' );
			} else {
				echo '<hr class="padbott">';
			}
		}
	}
}

/**
 *  Turns tag list into tag buttons.
 *
 * @since 20190722
 */
if ( ! function_exists( 'fluid_post_tags' ) ) {
	function fluid_post_tags() {
		ob_start();
		the_tags( esc_html__( '', 'tcc-fluid' ), '+++', '<br>' );
		$html = ob_get_clean();
		$tags = explode( '+++', $html );
		esc_html_e( 'Tags: ', 'tcc-fluid' );
		fluid()->tag( 'ul', [ 'class' => 'post-tags' ] );
			foreach( $tags as $tag ) {
				fluid()->element( 'li', [ 'class' => 'btn-fluidity' ], $tag, true );
			} ?>
		</ul><?php
	}
}

/**
 *  Display the post title.
 *
 * @since 20161229
 * @param int  $max     Maximum length of the title.
 * @param bool $anchor  Should the title be displayed in an anchor tag?
 */
if ( ! function_exists( 'fluid_post_title' ) ) {
	function fluid_post_title( $max = 0, $anchor = true ) {
		echo fluid_get_post_title( $max, $anchor );
	}
}

/**
 *  Control the 'read_more' link.
 *
 * @since 20150520
 * @link http://codex.wordpress.org/Excerpt
 * @link https://make.wordpress.org/themes/handbook/review/accessibility/required/
 * @link https://github.com/wpaccessibility/a11ythemepatterns
 * @link https://make.wordpress.org/accessibility/handbook/best-practices/markup/post-excerpts-for-an-archive-template/
 * @param string $output  WP's version of the more link.
 * @return string         Fluidity's version of the more link.
 */
if ( ! function_exists( 'fluid_read_more_link' ) ) {
	function fluid_read_more_link( $output ) {
		$attrs = array(
			'class'       => apply_filters( 'fluid_read_more_css', 'read-more-link' ),
			'href'        => get_permalink( get_the_ID() ),
			'itemprop'    => 'url',
			'aria-hidden' => 'true',
#			'tabindex'    => '-1',
		);
		// title inserted here for SEO purposes
		$span = fluid()->get_element( 'span', [ 'class' => 'screen-reader-text' ], fluid_excerpt_link_tooltip() );
		$text = esc_html( strip_tags( fluid_read_more_text() ) );
		$link = fluid()->get_element( 'a', $attrs, $text . $span, true );
		if ( apply_filters( 'fluid_read_more_brackets', true ) ) {
			$link = '<span class="block">[' . $link . ']</span>';
		}
		return $link;
	}
	add_filter( 'excerpt_more', 'fluid_read_more_link' );
}

/**
 *  Returns desired 'read more' text.
 *
 * @since 20190721
 * @return string  Filtered 'read more' text.
 */
if ( ! function_exists( 'fluid_read_more_text' ) ) {
	function fluid_read_more_text() {
		return apply_filters( 'fluid_read_more_text', __( 'Read More', 'tcc-fluid' ) );
	}
}

/**
 *  Filter the content more jump link.
 *
 * @since 20190721
 * @link https://digwp.com/2010/01/wordpress-more-tag-tricks/
 * @link https://codex.wordpress.org/Customizing_the_Read_More#Link_Jumps_to_More_or_Top_of_Page
 * @param string $link  More jump link.
 * @return string       Modified more jump link.
 */
if ( ! function_exists( 'fluid_remove_more_jump_link' ) ) {
	function fluid_remove_more_jump_link( $link ) {
		$link = preg_replace( '|#more-[0-9]+|', '', $link );
		return $link;
	}
	add_filter( 'the_content_more_link', 'fluid_remove_more_jump_link' );
}

/**
 *  Shows the content title.
 *
 * @since 20180313
 */
if ( ! function_exists( 'fluid_show_content_title' ) ) {
	function fluid_show_content_title() {
		if ( ! is_page() ) {
			$show_orig = false; ?>
			<h2 class="text-center" itemprop="headline"><?php
				fluid_post_title();
				fluid_edit_post_link(); ?>
			</h2>
			<div id="fluid_content_post_dates"><?php
				fluid_show_post_dates(); ?>
			</div><?php
		}
	}
	add_action( 'fluid_content_header', 'fluid_show_content_title' );
}

/**
 * wrapper for displaying the post dates
 *
 * @since 20180502
 */
if ( ! function_exists( 'fluid_show_post_dates' ) ) {
	function fluid_show_post_dates() { ?>
		<h3 class="post-date text-center"><?php
			$show_orig = fluid_post_date(); ?>
		</h3><?php
		if ( $show_orig ) { ?>
			<h4 class="post-date text-center"><?php
				fluid_post_date( 'original', false ); ?>
			</h4><?php
		}
	}
}

/**
 *  Show the thumbnail, if present.
 *
 * @since 20160830
 * @param string|array $size   Desired size for the thumbnail.
 * @param string       $class  CSS class for the image.
 */
if ( ! function_exists( 'fluid_thumbnail' ) ) {
	function fluid_thumbnail( $size = 'post-thumbnail', $class = 'img-responsive' ) {
		if ( ! is_page() || ( tcc_design( 'paral', 'no' ) === 'no' ) ) {
			if ( has_post_thumbnail() ) { ?>
				<div class="featured-image"><?php
#					add_filter( 'wp_get_attachment_image_attributes', 'fluid_thumbnail_filter' );
					$attrs = array(
						'alt'   => fluid_title(),
						'class' => $class
					);
					the_post_thumbnail( $size, $attrs ); ?>
				</div><?php
/*
$html = get_the_post_thumbnail( get_the_ID(), $size, $attrs );
$obj = fluid()->get_html_object( $html );
foreach( [ 'srcset', 'sizes', 'width', 'height' ] as $attr ) {
if ( array_key_exists( $attr, $obj->attrs ) ) unset( $obj->attrs[ $attr ] );
}
$image = fluid()->get_tag( $obj->tag, $obj->attrs );
fluid()->element( 'div', [ 'class' => 'featured-image' ], $image, true );
*/
			}
		}
	}
	add_action( 'fluid_content_header', 'fluid_thumbnail', 20 );
}
/*
if ( ! function_exists( 'fluid_thumbnail_filter' ) ) {
	function fluid_thumbnail_filter( $attrs ) {
		remove_filter( 'wp_get_attachment_image_attributes', 'fluid_thumbnail_filter' );
		foreach( $attrs as $attr => $value ) {
			if ( in_array( $attr, [ 'sizes', 'width', 'height' ] ) ) {
				unset( $attrs[ $attr ] );
			}
		}
		return $attrs;
	}
} //*/

/**
 *  Restrict the length of the title.
 *
 * @since 20161229
 * @param int  $length  Maximum desired length of the title.
 * @param bool $echo    Should we echo the title?
 * @return string       Only returns if $echo is true.
 */
if ( ! function_exists( 'fluid_title' ) ) {
	function fluid_title( $length = 0, $echo = false ) {
		$after  = '...'; $before = ''; // FIXME
		$title  = wp_kses( get_the_title( get_the_ID() ), fluid()->kses() );
		$length = intval( $length, 10 );
		if ( strlen( $title ) === 0 ) {
			$title = "{No Title}";
		} else {
			if ( $length ) {
				$test = wp_strip_all_tags( $title );
				if ( strlen( $test ) > $length ) {
					$title = substr( $test,  0, $length );
					$title = substr( $title, 0, strripos( $title, ' ' ) );
					$title = $before . $title . $after;
				}
			}
			$title = apply_filters( 'the_title', $title, get_the_ID() );
		}
		if ( $echo ) {
			echo $title;
		} else {
			return $title;
		}
	}
}

/**
 *  Displays a view image link.
 *
 * @since 201811
 */
if ( ! function_exists( 'fluid_view_image_link' ) ) {
	function fluid_view_image_link() {
		$attrs = array(
#			'class'       => apply_filters( 'fluid_view_image_link_css', 'text-center' ),
			'href'        => get_permalink( get_the_ID() ),
			'itemprop'    => 'url',
			'aria-hidden' => 'true',
			'tabindex'    => '-1',
		);
		fluid()->element( 'a', $attrs, __( 'View image', 'tcc-fluid' ) );
	}
}

/**
 *  Display the footer section for a post.
 *
 * @since 20180324
 */
if ( ! function_exists( 'fluid_show_content_footer' ) ) {
	function fluid_show_content_footer() {
		if ( is_single() && ! is_page() ) {
			$taxonomy = 'category'; # apply_filters( 'fluid_content_navigation_taxonomy', 'category' );
			fluid_navigation( $taxonomy );
			fluid_postmetadata();
		}
		tcc_show_comments();
	}
	add_action( 'fluid_content_footer', 'fluid_show_content_footer' );
}

/**
 *  Display the comments template.
 *
 * @since 20170120
 */
if (!function_exists('tcc_show_comments')) {
	function tcc_show_comments() {
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	}
}
