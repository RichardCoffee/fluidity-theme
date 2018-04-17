<?php

/*
 *  File:  includes/in-the-loop.php
 *
 *  All functions in this file expect to be run inside the WordPress loop
 *
 */

if (!function_exists('fluid_content_slug')) {
  function fluid_content_slug( $page='single' ) {
    $slug = ($format=get_post_format()) ? $format : get_post_type();
    $slug = ($slug==='page')            ? $page   : $slug;
    $slug = apply_filters("tcc_content_slug",         $slug, $page);
    $slug = apply_filters("tcc_{$page}_content_slug", $slug, $page);
    return $slug;
  }
}

if ( ! function_exists( 'fluid_edit_post_link' ) ) {
	function fluid_edit_post_link() {
		$text   = '&nbsp;{ ' . esc_html_x( 'Edit', 'verb', 'tcc-fluid' ) . ' }';
		$before = '<span class="edit-link small block">';
		$after  = '</span>';
		edit_post_link( $text, $before, $after );
	}
}

/**
 * Returns a string intended to be used for the title attribute for post links in excerpts
 *
 * @since 2.3.0
 *
 * @return string
 */
if ( ! function_exists( 'fluid_excerpt_link_tooltip' ) ) {
	function fluid_excerpt_link_tooltip() {
		$tooltip = sprintf( esc_html_x('Read All About %s','a post title','tcc-fluid'), fluid_title() );
		return $tooltip; # apply_filters( 'fluid_excerpt_link_tooltip', $tooltip );
	}
}

if ( ! function_exists( 'fluid_edit_post_link_anchor' ) ) {
	function fluid_edit_post_link_anchor( $anchor, $id, $text ) {
		$attrs = array(
			'class'  => 'post-edit-link',
			'href'   => get_edit_post_link( $id ),
			'target' => '_blank',
			'title'  => sprintf( _x( 'Edit %s', 'Name of current post', 'tcc-fluid' ), get_the_title() ),
		);
		$link   = fluid()->get_apply_attrs_tag( 'a', $attrs );
		$link  .= $text . '</a>';
		return $link;
	}
	add_filter( 'edit_post_link', 'fluid_edit_post_link_anchor', 10, 3 );
}

if (!function_exists('fluid_next_post_exists')) {
  function fluid_next_post_exists() {
    global $wp_query;
    return (bool)( $wp_query->current_post + 1 < $wp_query->post_count );
  }
}

if ( ! function_exists( 'fluid_post_date' ) ) {
	function fluid_post_date( $postdate = false ) {
		$showboth = ! empty( $postdate );
		if ( ! $postdate ) {
			$postdate = get_post_meta( get_the_ID(), 'postdate_display', true );
			$postdate = ( ! $postdate || ( $postdate === 'default' ) ) ? tcc_content( 'postdate', 'original' ) : $postdate;
		}
		if ( $postdate !== 'none' ) {
			$default = esc_html_x( 'Posted on %1$s by %2$s', '1: formatted date string, 2: author name', 'tcc-fluid' );
			$date    = get_the_date();
			$author  = microdata()->get_the_author();
			if ( in_array( $postdate, [ 'both', 'modified' ] ) && ( ( get_the_modified_date( 'U' ) - DAY_IN_SECONDS ) > ( get_the_date( 'U' ) ) ) ) {
				$default  = esc_html_x( 'Last modified on %1$s by %2$s', '1: formatted date string, 2: author name', 'tcc-fluid' );
				$date     = get_the_modified_date();
				$showboth = ( $postdate === 'both' );
			}
			$string  = apply_filters( 'fluid_post_date_sprintf', $default, $postdate, $showboth );
			echo sprintf( $string, $date, $author );
		}
		return $showboth;
	}
}

if ( ! function_exists( 'fluid_post_date_sprintf' ) ) {
	function fluid_post_date_sprintf( $format, $postdate, $showboth = false ) {
fluid()-log(func_get_args());
		if ( $showboth && ( $postdate === 'original' ) ) {
			$format = esc_html_x( 'Originally posted on %s', 'wordpress formatted date string', 'tcc-fluid' );
		}
		return $format;
	}
	add_filter( 'fluid_post_date_sprintf', 'fluid_post_date_sprintf', 10, 3 );
}

if ( ! function_exists( 'fluid_postmetadata' ) ) {
	function fluid_postmetadata() { ?>
		<div class="article margint1e noprint">
			<p class="postmetadata">
				<hr><?php
				if ( has_tag() ) {
					the_tags( esc_html__( 'Tags: ', 'tcc-fluid' ), ', ', '<br>' );
					echo '<hr>';
				}
				$cat_list = get_the_category_list();
				if ( ! empty( $cat_list ) ) {  #  wordpress's has_category() does not always return a correct value - wtf?
					esc_html_ex( 'Categories: ', 'string will be followed by one or more categories', 'tcc-fluid' );
					the_category(', ');
					echo '<hr>';
				}
#				if ( has_tag() || ( ! empty( $cat_list ) ) ) { }
				$comm_0 = esc_html__( 'No Comments', 'tcc-fluid' );
				$comm_1 = esc_html_x( '1 Comment', 'single comment', 'tcc-fluid' );
				$comm_2 = esc_html_x( '% Comments', 'number of comments', 'tcc-fluid' );
				comments_popup_link( $comm_0, $comm_1, $comm_2 ); ?>
			</p>
		</div><?php
	}
}

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

if ( ! function_exists( 'fluid_read_more_link' ) ) {
	#	http://codex.wordpress.org/Excerpt
	#	https://make.wordpress.org/themes/handbook/review/accessibility/required/
	#	https://github.com/wpaccessibility/a11ythemepatterns
	function fluid_read_more_link( $output ) {
		$attrs = array(
			'class'    => apply_filters( 'fluid_read_more_css', 'read-more-link' ),
			'href'     => get_permalink( get_the_ID() ),
			'itemprop' => 'url',
		);
		$link  = fluid()->get_apply_attrs_tag( 'a', $attrs );
		$link .= esc_html( apply_filters( 'fluid_read_more_text', __( 'Read More', 'tcc-fluid' ) ) );
		$link .= '<span class="screen-reader-text"> ';
		$link .= wp_strip_all_tags( get_the_title( get_the_ID() ) );
		$link .= '</span></a>';
		if ( apply_filters( 'fluid_read_more_brackets', true ) ) {
			$link = ' [' . $link . ']';
		}
		return $link;
	}
	add_filter( 'excerpt_more', 'fluid_read_more_link' );
}

if ( ! function_exists( 'fluid_show_content_title' ) ) {
	function fluid_show_content_title() {
		if ( ! is_page() ) {
			$show_orig = false; ?>
			<h2 class="text-center">
				<?php tcc_post_title(); ?>
				<?php fluid_edit_post_link(); ?>
			</h2>
			<h3 class="post-date text-center">
				<?php $show_orig = fluid_post_date(); ?>
			</h3><?php
			if ( $show_orig ) { ?>
				<h4 class="post-date text-center"><?php
					fluid_post_date( 'original' ); ?>
				</h4><?php
			}
		}
	}
	add_action( 'fluid_content_header', 'fluid_show_content_title' );
}

if ( ! function_exists( 'fluid_thumbnail' ) ) {
	function fluid_thumbnail( $size = null, $class = 'img-responsive' ) {
		if ( ! is_page() || ( tcc_design( 'paral', 'no' ) == 'no' ) ) {
			if ( has_post_thumbnail() ) {
				$attr = array(
					'alt' => fluid_title(),
					'class' => $class
				);
				the_post_thumbnail( $size, $attr );
			}
		}
	}
	add_action( 'fluid_content_header', 'fluid_thumbnail', 20 );
}

if ( ! function_exists( 'fluid_title' ) ) {
	function fluid_title( $length=0 ) {
		$echo = false; $after = '...'; $before = ''; // FIXME
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
		if ($echo) { echo $title; } else { return $title; }
	}
}

if (!function_exists('get_the_author_posts_link')) {
	function get_the_author_posts_link( $authorID = null ) {
		$html = '';
		$authorID = ($authorID) ? $authorID : get_the_author_meta('ID');
		if ($authorID) {
			$title = __( 'Author posts archive', 'tcc-fluid' );
			$attrs = array(
				'href'       => get_author_posts_url( $authorID ),
				'title'      => $title,
				'aria-label' => $title,
			);
			#$link = str_replace('/author/','/agent/',$link);  // FIXME:  check for appropriate link stem -
			$html = fluid()->get_apply_attrs_element( 'a', $attrs, get_the_author_meta('display_name') );
		}
		return $html;
	}
}

if (!function_exists('tcc_post_title')) {
	function tcc_post_title( $max = 0, $anchor = true ) {
		$anchor = ( is_single() || is_page() ) ? false : $anchor;
		$html   = fluid_title( $max );
		if ( $anchor ) {
			$attrs = array(
				'href'  => get_the_permalink(),
				'rel'   => 'bookmark',
				'title' => fluid_excerpt_link_tooltip()
			);
			$html = fluid()->get_apply_attrs_element( 'a', $attrs, $html );
		}
		echo $html;
	}
}

if ( ! function_exists( 'fluid_show_content_footer' ) ) {
	function fluid_show_content_footer() {
		if ( is_single() && ! is_page() ) {
			$taxonomy = 'category'; # apply_filters( 'fluid_content_taxonomy', 'category' );
			new TCC_Theme_Navigation( array( 'taxonomy' => $taxonomy ) );
			fluid_postmetadata();
		}
		tcc_show_comments();
	}
	add_action( 'fluid_content_footer', 'fluid_show_content_footer' );
}

if (!function_exists('tcc_show_comments')) {
	function tcc_show_comments() {
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	}
}
