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
#log_entry(0,"fluid_content_slug: $slug    format:  $format    page:  $page");
    return $slug;
  }
}

if (!function_exists('fluid_edit_post_link')) {
  function fluid_edit_post_link() {
    $title  = the_title( '<span class="sr-only">"', '"</span>', false );
    $string = sprintf( esc_attr_x( 'Edit %s', 'Name of current post', 'tcc-fluid' ), $title );
    ##  This code replaces the edit_post_link call so that I could add the target attribute
    $link = get_edit_post_link(get_the_ID());
    if ($link) { ?>
      <span class="edit-link small block">
        <a class="post-edit-link" href="<?php echo $link; ?>" target="_blank">&nbsp;{ <?php
          echo $string; ?>}
        </a>
      </span><?php
    }
  }
}

if ( ! function_exists( 'fluid_navigation' ) ) {
	function fluid_navigation( $taxonomy = '', $all_links = false ) {
		$left  = '<span aria-hidden="true">&laquo;</span> %title';
		$right = '%title <span aria-hidden="true">&raquo;</span>';
		$exclude  = apply_filters( 'fluid_navigation_exclude',  array() );
		$taxonomy = apply_filters( 'fluid_navigation_taxonomy', $taxonomy );
		if ( $taxonomy && $all_links ) {
			$prev_tax = get_permalink( get_adjacent_post( true,  $exclude, true,  $taxonomy ) );
			$next_tax = get_permalink( get_adjacent_post( true,  $exclude, false, $taxonomy ) );
			$prev_all = get_permalink( get_adjacent_post( false, $exclude, true ) );
			$next_all = get_permalink( get_adjacent_post( false, $exclude, false ) );
			if ( ( $prev_tax === $prev_all ) && ( $next_tax === $next_all ) ) {
				$taxonomy = '';
			}
/*log_entry(
	"    taxonomy: $taxonomy",
	"previous tax: $prev_tax",
	"previous all: $prev_all",
	"    next tax: $next_tax",
	"    next all: $next_all"
); //*/
		} ?>
		<div id="post-link-separator-top" class="post-link-separator post-link-separator-top"></div><?php
		if ( $taxonomy ) {
			$tax_obj = get_taxonomy( $taxonomy );
			$older_tooltip = sprintf( _x( 'Older Posts for %s', 'the taxonomy name (plural)', 'tcc-fluid' ), $tax_obj->labels->name );
			$newer_tooltip = sprintf( _x( 'Newer Posts for %s', 'the taxonomy name (plural)', 'tcc-fluid' ), $tax_obj->labels->name ); ?>
			<nav class="noprint" aria-label="...">
				<h2 class="screen-reader-text">
					<?php esc_attr_e( 'Category Navigation', 'tcc-fluid' ); ?>
				</h2>
				<div class="row">
					<ul class="pager pager-category"><?php
						if ( $prev_tax !== $prev_all ) { ?>
							<li class="previous btn-fluidity" title="<?php e_esc_attr( $older_tooltip ); ?>">
								<?php previous_post_link( '%link', $left, true, $exclude, $taxonomy ); ?>
							</li><?php
						}
						if ( $next_tax !== $next_all ) { ?>
							<li class="next btn-fluidity" title="<?php e_esc_attr( $newer_tooltip ); ?>">
								<?php next_post_link( '%link', $right, true, $exclude, $taxonomy ); ?>
							</li><?php
						} ?>
					</ul>
				</div>
			</nav><?php
		}
		if ( $taxonomy && $all_links ) { ?>
			<div class="post-link-separator post-link-separator-middle"></div><?php
		}
		if ( ! $taxonomy || $all_links ) { ?>
			<nav class="noprint" aria-label="...">
				<h2 class="screen-reader-text">
					<?php esc_attr_e( 'Post Navigation', 'tcc-fluid' ); ?>
				</h2>
				<div class="row">
					<ul class="pager pager-all">
						<li class="previous btn-fluidity" title="<?php esc_html_e( 'Older Posts', 'tcc-fluid' ); ?>">
							<?php previous_post_link( '%link', $left, false, $exclude ); ?>
						</li>
						<li class="next btn-fluidity" title="<?php esc_html_e( 'Newer Posts', 'tcc-fluid' ); ?>">
							<?php next_post_link( '%link', $right, false, $exclude ); ?>
						</li>
					</ul>
				</div>
			</nav><?php
		} ?>
		<div class="post-link-separator post-link-separator-bottom"></div>
		<p> </p><?php
	}
}

if (!function_exists('fluid_next_post_exists')) {
  function fluid_next_post_exists() {
    global $wp_query;
    return (bool)( $wp_query->current_post + 1 < $wp_query->post_count );
  }
}

if ( ! function_exists( 'fluid_post_date' ) ) {
	function fluid_post_date( $deprecated = false ) {
		$postdate = get_post_meta( get_the_ID(), 'postdate_display', true );
		$postdate = ( ! $postdate || ( $postdate === 'default' ) ) ? tcc_option( 'postdate' ) : $postdate;
		if ( $postdate !== 'none' ) {
			$default = esc_html_x( 'Posted on %1$s by %2$s', '1: formatted date string, 2: user name', 'tcc-fluid' );
			$date    = get_the_date();
			$author  = microdata()->get_the_author();
			if ( ( $postdate === 'modified' ) && ( ( get_the_modified_date( 'U' ) - DAY_IN_SECONDS ) > ( get_the_date( 'U' ) ) ) ) {
				$default = esc_html_x( 'Last modified on %1$s by %2$s', '1: formatted date string, 2: author name', 'tcc-fluid' );
				$date    = get_the_modified_date();
			}
			$string  = apply_filters( 'fluid_post_date_sprintf', $default );
			echo sprintf( $string, $date, $author );
		}
	}
}

if (!function_exists('fluid_postmetadata')) {
	function fluid_postmetadata() { ?>
		<p class="postmetadata noprint"><?php
			if (has_tag()) {
				the_tags(esc_html__('Tags','tcc-fluid').': ', ', ', '<br>');
			}
			$cat_list = get_the_category_list();
			if (!empty($cat_list)) {  #  wordpress's has_category() does not always return a correct value - wtf?
				esc_html_ex('Posted in ','string will be followed by a category or list of categories','tcc-fluid');
				the_category(', ');
			}
			if (has_tag() || (!empty($cat_list))) {
				echo ' | ';
			}
			$comm_0 = esc_html__('No Comments','tcc-fluid');
			$comm_1 = esc_html__('1 Comment','tcc-fluid');
			$comm_2 = esc_html_x('% Comments','number of comments','tcc-fluid');
			comments_popup_link( $comm_0, $comm_1, $comm_2 ); ?>
		</p><?php
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

if (!function_exists('fluid_thumbnail')) {
	function fluid_thumbnail( $size=null, $class='img-responsive' ) {
		if (!is_page() || tcc_design('paral')=='no') {
			if ( has_post_thumbnail() ) {
				$attr = array( 'alt' => fluid_title(), 'class' => $class );
				the_post_thumbnail( $size, $attr );
			}
		}
	}
}

if (!function_exists('fluid_title')) {
	function fluid_title( $length=0 ) {
		$echo=false; $after='...'; $before=''; // FIXME
		#$postID = get_post()->ID;
		$title  = get_the_title( get_the_ID() );
		if (strlen($title)===0) {
			$title = "{No Title}";
		} else {
			if ($length && is_numeric($length)) {
				$title = strip_tags($title);
				if (strlen($title)>$length) {
					$title = substr($title,0,$length);
					$title = substr($title,0,strripos($title,' '));
					$title = $before.$title.$after;
				}
			}
			$title = esc_html(apply_filters('the_title',$title,get_the_ID()));
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
				'href'  => get_author_posts_url( $authorID ),
				'title' => $title,
				'aria-label' => $title,
			);
			#$link = str_replace('/author/','/agent/',$link);  // FIXME:  check for appropriate link stem - 
			$html = '<a ' . library()->get_apply_attrs( $attrs ) . '>' . get_the_author_meta('display_name') . '</a>';
		}
		return $html;
	}
}

if (!function_exists('tcc_post_title')) {
	function tcc_post_title( $max = 0, $anchor = true ) {
		$anchor = ( is_single() || is_page() ) ? false : $anchor;
		$title  = fluid_title( $max );
		if ( $anchor ) {
			$tooltip = sprintf( esc_html_x('Read All About %s','a post title','tcc-fluid'), fluid_title() );
			$string  = '<a href="%s" rel="bookmark" title="%s">%s</a>';
			$title   = sprintf( $string, get_the_permalink(), esc_attr($tooltip), esc_html($title) );
		}
		echo $title;
	}
}

if (!function_exists('tcc_show_comments')) {
	function tcc_show_comments() {
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	}
}
