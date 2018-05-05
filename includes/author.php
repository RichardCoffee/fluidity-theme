<?php
/**
 * includes/author.php
 *
 */
/**
 * load author profile template
 *
 * @param string $mypage
 */
if ( ! function_exists( 'fluid_before_posts_author' ) ) {
	function fluid_before_posts_author( $mypage ) {
		if ( $mypage === 'author' ) {
#			$current = ( isset( $_GET['author_name'] ) ) ? get_user_by( 'slug', $author_name ) : get_userdata( intval( $author ) ); wtf?
			$current = get_userdata( get_query_var( 'author', null ) );
			$role    = ( $current ) ? $current->roles[0] : ''; // TODO: filter roles
			get_template_part( 'template-parts/profile', $role );
/* ? >
			<div class='<?php echo esc_attr( $title_class ); ? >' itemprop='headline'>
				<h3 class='text-center'>
					<?php echo esc_html( $title_posts ); ? >
				</h3>
			</div><?php //*/
		}
	}
	add_action( 'fluid_before_posts', 'fluid_before_posts_author' );
}

/**
 * control excerpt length for author archive page
 *
 * @param numeric $length
 * @return numeric
 */
if ( ! function_exists( 'fluid_excerpt_length_author' ) ) {
	function fluid_excerpt_length_author( $length ) {
		if ( get_page_slug() === 'author' ) {
			return 45;
		}
		return $length;
	}
	add_filter( 'excerpt_length', 'fluid_excerpt_length_author', 12 );
}

/**
 * hide the post author name when displaying on author archive page
 *
 * @param string $string
 * @param string $postdate
 * @param bool $showboth
 * @return string
 */
if ( ! function_exists( 'fluid_post_date_sprintf_author' ) ) {
	function fluid_post_date_sprintf_author( $string, $postdate, $showboth ) {
		if ( get_page_slug() === 'author' ) {
			$string = str_replace( ' by %2$s', '<span class="hidden"> by %2$s</span>', $string );
		}
		return $string;
	}
	add_filter( 'fluid_post_date_sprintf', 'fluid_post_date_sprintf_author' );
}

/**
 * prevents the hr element from being displayed on the author page
 *
 */
if ( ! function_exists( 'fluid_post_separator_author' ) ) {
	function fluid_post_separator_author() {
		if ( get_page_slug() === 'author' ) {
			// stop hr element from being displayed
			add_action( 'fluid_post_separator_author', function() { } );
		}
	}
	add_action( 'fluid_before_posts', 'fluid_post_separator_author' );
}

/**
 * limit the posts per page for author archives
 *
 * @param WP_Query $query
 * @return WP_Query
 */
if ( ! function_exists( 'fluid_posts_per_page_author' ) ) {
	function fluid_posts_per_page_author( $query ) {
		if ( ! is_admin() && $query->is_main_query() && $query->is_author() ) {
			$query->set('posts_per_page', 9);
		}
		return $query;
	}
	add_filter('pre_get_posts', 'fluid_posts_per_page_author');
}

/**
 * initialize bootstrap clearfix
 *
 * @param string $mypage
 */
if ( ! function_exists( 'fluid_start_author_loop' ) ) {
	function fluid_start_author_loop( $mypage ) {
		if ( $mypage === 'author' ) {
			clearfix()->initialize( array(
				'lg' => 4,
				'md' => 4,
				'sm' => 6,
				'xs' => 12,
			) );
		}
	}
	add_action( 'fluid_before_posts', 'fluid_start_author_loop', 20 );
}


/**
 * stops the sidebar from being shown on the author page
 *
 * @param string $position
 * @return string
 */
if ( ! function_exists( 'fluid_theme_sidebar_positioning_author' ) ) {
	function fluid_theme_sidebar_positioning_author( $position ) {
		if ( get_page_slug() === 'author' ) {
			$position = 'none';
		}
		return $position;
	}
	add_filter( 'fluid_theme_sidebar_positioning', 'fluid_theme_sidebar_positioning_author' );
}
