<?php

/**
 *  File:  includes/in-the-loop.php
 *
 *  All functions in this file expect to be run inside the WordPress loop
 *
 * @since 20160830
 */

# * @since 20160830
if (!function_exists('fluid_content_slug')) {
  function fluid_content_slug( $page='single' ) {
    $slug = ($format=get_post_format()) ? $format : get_post_type();
    $slug = ($slug==='page')            ? $page   : $slug;
    $slug = apply_filters("tcc_content_slug",         $slug, $page);
    $slug = apply_filters("tcc_{$page}_content_slug", $slug, $page);
    return $slug;
  }
}

# * @since 20160830
if ( ! function_exists( 'fluid_edit_post_link' ) ) {
	function fluid_edit_post_link() {
		$text   = '&nbsp;{ ' . esc_html_x( 'Edit', 'verb', 'tcc-fluid' ) . ' }';
		$before = '<span class="edit-link small block">';
		$after  = '</span>';
		edit_post_link( $text, $before, $after );
	}
}

# * @since 20180328
if ( ! function_exists( 'fluid_edit_post_link_anchor' ) ) {
	function fluid_edit_post_link_anchor( $anchor, $id, $text ) {
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
 *  Display excerpt header
 *
 * @since 20180807
 */
if ( ! function_exists( 'fluid_excerpt_header' ) ) {
	function fluid_excerpt_header() { ?>
		<h2 class="text-center" itemprop="headline"><?php
			tcc_post_title( 40 ); ?>
		</h2><?php
		if ( tcc_option( 'exdate', 'content', 'show' ) === 'show' ) { ?>
			<h3 class="text-center"><?php
				fluid_post_date(); ?>
			</h3><?php
		}
	}
	add_action( 'fluid_excerpt_header', 'fluid_excerpt_header' );
}

/**
 * Returns a string intended to be used for the title attribute for post links in excerpts
 *
 * @since 20180328
 * @return string
 */
if ( ! function_exists( 'fluid_excerpt_link_tooltip' ) ) {
	function fluid_excerpt_link_tooltip() {
		$tooltip = sprintf( esc_html_x( 'Read All About %s', 'the post title', 'tcc-fluid' ), fluid_title() );
		return $tooltip; # apply_filters( 'fluid_excerpt_link_tooltip', $tooltip );
	}
}

# * @since 20160830
if (!function_exists('fluid_next_post_exists')) {
  function fluid_next_post_exists() {
    global $wp_query;
    return (bool)( $wp_query->current_post + 1 < $wp_query->post_count );
  }
}

/**
 * show the post publish and/or last edit date
 *
 * @since 20160830
 * @param string $postdate
 * @return bool
 */
if ( ! function_exists( 'fluid_post_date' ) ) {
	function fluid_post_date( $postdate = '' ) {
		$showboth = ! empty( $postdate );
		if ( empty( $postdate ) ) {
			$postdate = get_post_meta( get_the_ID(), 'postdate_display', true );
			$postdate = ( empty( $postdate ) || ( in_array( $postdate, [ 'defaultpd', 'default' ] ) ) ) ? get_theme_mod( 'content_postdate', 'original' ) : $postdate;
		}
		if ( ! ( $postdate === 'none' ) ) {
			$default = esc_html_x( 'Posted on %1$s by %2$s', '1: formatted date string, 2: author name', 'tcc-fluid' );
			$date    = get_the_date();
			if ( in_array( $postdate, [ 'both', 'modified' ] ) && ( ( get_the_modified_date( 'U' ) - DAY_IN_SECONDS ) > ( get_the_date( 'U' ) ) ) ) {
				$default  = esc_html_x( 'Last modified on %1$s by %2$s', '1: formatted date string, 2: author name', 'tcc-fluid' );
				$date     = get_the_modified_date();
				$showboth = ( $postdate === 'both' );
			}
			$string = apply_filters( 'fluid_post_date_sprintf', $default, $postdate, $showboth );
			$author = ( is_single() ) ? get_the_author_posts_link() : get_the_author();
			printf( $string, $date, $author );
		}
		return $showboth;
	}
}

# * @since 20180417
if ( ! function_exists( 'fluid_post_date_sprintf' ) ) {
	function fluid_post_date_sprintf( $format, $postdate, $showboth = false ) {
		if ( $showboth && ( $postdate === 'original' ) ) {
			$format = esc_html_x( 'Originally posted on %s', 'wordpress formatted date string', 'tcc-fluid' );
		}
		return $format;
	}
	add_filter( 'fluid_post_date_sprintf', 'fluid_post_date_sprintf', 10, 3 );
}

# * @since 20161231
if ( ! function_exists( 'fluid_postmetadata' ) ) {
	function fluid_postmetadata() { ?>
		<div class="article margint1e noprint">
			<div class="postmetadata">
				<hr><?php
				if ( has_tag() ) {
					the_tags( esc_html__( 'Tags: ', 'tcc-fluid' ), ', ', '<br>' );
					echo '<hr>';
				}
				$cat_list = get_the_category_list( ', ' );
				if ( ! empty( $cat_list ) ) {  #  wordpress's has_category() does not always return a correct value - wtf?
					printf( esc_html_x( 'Categories: %s', 'string - one or more categories', 'tcc-fluid' ), $cat_list );
					echo '<hr>';
				} ?>
			</div>
		</div><?php
	}
}

# * @since 20161206
if ( ! function_exists( 'fluid_post_separator' ) ) {
	function fluid_post_separator( $slug ) {
		if ( fluid_next_post_exists() ) {
			if ( has_action( "fluid_post_separator_$slug" ) ) {
				do_action( "fluid_post_separator_$slug" );
			} else if ( has_action( 'fluid_post_separator' ) ) {
				do_action( 'fluid_post_separator' );
			} else {
				echo "<hr class='padbott'>";
			}
		}
	}
}

# * @since 20150520
# * @link http://codex.wordpress.org/Excerpt
# * @link https://make.wordpress.org/themes/handbook/review/accessibility/required/
# * @link https://github.com/wpaccessibility/a11ythemepatterns
# * @link https://make.wordpress.org/accessibility/handbook/best-practices/markup/post-excerpts-for-an-archive-template/
if ( ! function_exists( 'fluid_read_more_link' ) ) {
	function fluid_read_more_link( $output ) {
		$attrs = array(
			'class'       => apply_filters( 'fluid_read_more_css', 'read-more-link' ),
			'href'        => get_permalink( get_the_ID() ),
			'itemprop'    => 'url',
			'aria-hidden' => 'true',
			'tabindex'    => '-1',
		);
		$link  = fluid()->get_apply_attrs_tag( 'a', $attrs );
		$link .= apply_filters( 'fluid_read_more_text', __( 'Read More', 'tcc-fluid' ) );
		// title inserted here for SEO purposes
		$link .= '<span class="screen-reader-text"> ';
		$link .= sprintf( __( 'Read more about %s', 'tcc-fluid' ), wp_strip_all_tags( get_the_title( get_the_ID() ) ) );
		$link .= '</span></a>';
		if ( apply_filters( 'fluid_read_more_brackets', true ) ) {
			$link = ' <span style="display: inline-block">[' . $link . ']</span>';
		}
		return $link;
	}
	add_filter( 'excerpt_more', 'fluid_read_more_link' );
}

# * @since 20180313
if ( ! function_exists( 'fluid_show_content_title' ) ) {
	function fluid_show_content_title() {
		if ( ! is_page() ) {
			$show_orig = false; ?>
			<h2 class="text-center" itemprop="headline">
				<?php tcc_post_title(); ?>
				<?php fluid_edit_post_link(); ?>
			</h2>
			<div id="fluid_content_post_dates">
				<?php fluid_show_post_dates(); ?>
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
				fluid_post_date( 'original' ); ?>
			</h4><?php
		}
	}
}

#	 * @since 20160830
if ( ! function_exists( 'fluid_thumbnail' ) ) {
	function fluid_thumbnail( $size = null, $class = 'img-responsive' ) {
		if ( ! is_page() || ( tcc_design( 'paral', 'no' ) === 'no' ) ) {
			if ( has_post_thumbnail() ) { ?>
				<div class="featured-image"><?php
					$attr = array(
						'alt'   => fluid_title(),
						'class' => $class
					);
					the_post_thumbnail( $size, $attr ); ?>
				</div><?php
			}
		}
	}
	add_action( 'fluid_content_header', 'fluid_thumbnail', 20 );
}

# * @since 20161229
if ( ! function_exists( 'fluid_title' ) ) {
	function fluid_title( $length = 0, $echo = false ) {
		$after = '...'; $before = ''; // FIXME
		$title  = wp_strip_all_tags( get_the_title( get_the_ID() ) );
		if ( strlen( $title ) === 0 ) {
			$title = "{No Title}";
		} else {
			if ( $length && is_numeric( $length ) ) {
				if ( strlen( $title ) > $length ) {
					$title = substr( $title, 0, $length );
					$title = substr( $title, 0, strripos( $title, ' ' ) );
					$title = $before . $title . $after;
				}
			}
			$title = apply_filters( 'the_title', $title, get_the_ID() );
		}
		if ( $echo ) {
			e_esc_html( $title );
		} else {
			return $title;
		}
	}
}

# * @since 20161229
if (!function_exists('tcc_post_title')) {
	function tcc_post_title( $max = 0, $anchor = true ) {
		$anchor = ( is_single() || is_page() ) ? false : $anchor;
		if ( $anchor ) {
			$attrs = array(
				'href'  => get_the_permalink(),
				'rel'   => 'bookmark',
				'title' => fluid_excerpt_link_tooltip()
			);
			fluid()->element( 'a', $attrs, fluid_title( $max ) );
		} else {
			fluid_title( $max, true );
		}
	}
}

# * @since 20180324
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

# * @since 20170120
if (!function_exists('tcc_show_comments')) {
	function tcc_show_comments() {
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	}
}
