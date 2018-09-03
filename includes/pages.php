<?php

/*
 *  File:  includes/pages.php
 *		Custom Fields on Pages:		<?php echo get_post_meta(get_page_id($page), 'key string here', true); ?>
 *
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 */

if ( ! function_exists( 'get_page_id' ) ) {
	# http://snipplr.com/view/39004/
	# http://www.smipple.net/snippet/elieandraos/Get%20Page%20ID%20By%20Slug
	function get_page_id( $slug ) {
		global $wpdb;
		return $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_name = '$slug' AND post_type = 'page'" );
	}
}

if (!function_exists('fluid_category_page_noposts')) {
  function fluid_category_page_noposts() {
    $text = esc_html__( 'Apologies, but no results were found for the requested Category. Perhaps searching will help find a related post.','tcc-fluid' );
    fluid_noposts_page($text);
  }
}

/**
 *  Add page controls to customizer
 *
 * @since 20180807
 * @param array $options
 * @return array
 */
if ( ! function_exists( 'fluid_customizer_pages' ) ) {
	function fluid_customizer_pages( $options = array() ) {
		$options['pages'] = array(
			'section' => array(
				'priority'    => 30,
				'panel'       => 'fluid_mods',
				'title'       => __( 'Pages', 'tcc-fluid' ),
				'description' => __( 'All settings dealing with pages', 'tcc-fluid' )
			),
			'controls' => array(
				'the-title' => array(
					'default'    => 'page',
					'label'      => __( 'Page Title', 'tcc-fluid' ),
					'descripion' => __( 'Do you want to show the page title before the content?', 'tcc-fluid' ),
					'render'     => 'radio',
					'choices'    => array(
						'no'   => __( 'Do not show the page title.', 'tcc-fluid' ),
						'page' => __( 'Show the page title after the header and before the content/sidebar.', 'tcc-fluid' ),
						'main' => __( 'Over content area only, when showing sidebar.', 'tcc-fluid' ),
					)
				)
			)
		);
		return $options;
	}
	add_filter( 'fluid_customizer_controls', 'fluid_customizer_pages' );
}
/*
if ( ! function_exists( 'fluid_hide_content_title' ) ) {
	function fluid_hide_content_title( $page ) {
fluid()->log($page);
		$exclude = array( 'log-in' );
		if ( in_array( $page, $exclude ) ) {
			remove_action( 'fluid_content_header', 'fluid_show_content_title' );
		}
	}
	add_action( 'fluid_before_loop', 'fluid_hide_content_title' );
} //*/

if (!function_exists('fluid_noposts_page')) {
  function fluid_noposts_page($text) { ?>
    <div id="post-0" class="post error404 not-found">
      <h1 class="text-center"><?php esc_html_e('Not Found','tcc-fluid' ); ?></h1>
      <p><?php
        esc_html($text); ?>
      </p><?php
      get_search_form(); ?>
    </div><!-- #post-0 --><?php
  }
}

// FIXME: can we get rid of this function yet?
if (!function_exists('fluid_save_page_template')) {
	function fluid_save_page_template( $template ) {
		global $fluidity_theme_template;
		if ( empty( $fluidity_theme_template ) ) {
			$fluidity_theme_template = basename($template,".php");
		}
		return $template;
	}
	add_action('template_include', 'fluid_save_page_template', 1000);
}

#	 * @link http://www.wpaustralia.org/wordpress-forums/topic/pre_get_posts-and-is_front_page/
#	 * @link http://www.tcbarrett.com/2013/05/wordpress-how-to-get-the-slug-of-your-post-or-page
if ( ! function_exists( 'get_page_slug' ) ) {
	function get_page_slug( $set_slug = '' ) {
		global $wp_query;
		static $slug = null;
		if ( ! empty( $set_slug ) ) {
			$slug = $set_slug;
		} else if ( defined( 'TCC_PAGE_SLUG' ) ) {
			$slug = TCC_PAGE_SLUG;
		}
		if ( empty( $slug ) ) {
			if ( ( ! is_admin() ) && $wp_query->is_main_query() ) {
				if ( ! empty( $wp_query->query['feed'] ) && ( $wp_query->query['feed'] === 'feed' ) ) {
					$slug = 'feed';
				} else if ( is_home() && empty( $wp_query->query_string ) ) {
					$slug = apply_filters( 'fluid_home_page_slug', 'home' );
				#} else if ( ( $wp_query->get( 'page_id' ) === get_option( 'page_on_front' ) && get_option( 'page_on_front' ) ) || empty( $wp_query->query_string ) ) {
				} else if ( get_option( 'page_on_front' ) && ( $wp_query->get( 'page_id' ) === get_option( 'page_on_front' ) ) ) {
					$slug = apply_filters( 'fluid_front_page_slug', 'front' );
				} else {
					$post = get_queried_object();  #  $wp_query->queried_object
					if ( $post instanceof WP_Post ) {
						if ( ! empty( $post->post_type ) && ( $post->post_type === 'page' ) ) {
							$slug = $post->post_name;
						} else if ( ! empty( $post->post_name ) ) {
							$slug = $post->post_name;
						} else if ( ! empty( $post->name ) ) {
							$slug = $post->name;
						} else if ( ! empty( $post->post_title ) ) {
							$slug = sanitize_title( $post->post_title );
						}
					} else if ( $post instanceof WP_Term ) {
						$slug = $post->slug;
					} else if ( $post instanceof WP_User ) {
						$slug = 'author';
					}
				}
			}
			if ( empty( $slug ) ) {
				if ( is_customize_preview() ) {
					$slug = 'WARNING_in_customizer_preview_no_slug_available';
				} else {
					fluid()->log( 'missing page slug', $wp_query, 'full-stack' );
					$slug = 'ERROR_missing_page_slug';
				}
			}
		}
		return $slug;
	}
}

if (!function_exists('get_title_class')) {
	function get_title_class( $class = '', $post_id = null ) {
		$slug = get_page_slug();
		$classes = array('page-title',"page-title-$slug");
		if ( $class ) {
			$classes = array_merge( $classes, (array)$class ); }
		$classes = apply_filters( 'get_title_class', $classes );
		$classes = apply_filters( "get_title_class_$slug", $classes );
		return $classes;
	}
}
/*
if (!function_exists('has_page')) {
	function has_page( $title ) {
		return page_exists( $title );
	}
}

#  http://www.tammyhartdesigns.com/tutorials/wordpress-how-to-determine-if-a-certain-page-exists
if (!function_exists('page_exists')) {
	function page_exists( $search ) {
		$pages = get_pages();
		foreach ($pages as $page) {
			if ($page->post_name===$search) {
				return true; }
		}
		return false;
	}
} //*/

if ( ! function_exists( 'tcc_before_posts_filter' ) ) {
	function tcc_before_posts_filter( $mypage ) {
		# ! get_theme_mod( 'pages_the-title', 'page' ) === 'main'
		fluid()->log( 'theme mod: ' . get_theme_mod( 'pages_the-title', 'page' ) );
		if ( ! in_array( get_theme_mod( 'pages_the-title', 'page' ), array( 'no', 'page' ) ) ) {
			tcc_show_page_title( $mypage );
		}
	}
	add_action( 'fluid_before_posts', 'tcc_before_posts_filter' );
}

if ( ! function_exists( 'tcc_get_page_id_by_slug' ) ) {
	function tcc_get_page_id_by_slug( $slug, $prop = 'ID' ) {
		static $curr;
		if ( ( ! $curr ) || ( ! ( $slug === $curr->post_name ) ) ) {
			$args   = array( 'post_type' => 'page', 'name' => $slug );
			$pages  = new WP_Query( $args );
			if ( $pages ) {
				foreach( $pages->posts as $page ) {
					if ( $page->post_name === $slug ) {
						$curr = $page;
						break;
					}
				}
				if ( ! $curr ) { return ''; }
			}
		}
		return $curr->$prop; // FIXME: check for existing property
	}
}

if ( ! function_exists( 'tcc_get_page_title' ) ) {
	function tcc_get_page_title( $slug ) {
		$title = tcc_get_page_id_by_slug( $slug, 'post_title' );
		if ( is_archive() && ! get_query_var( 'paged' ) ) {
			if ( is_tax() || is_category() || is_tag() ) {
				$title =  ( $descrip = term_description() ) ? $descrip : $title;
			}
		} else if ( is_search() ) {
			$title = 'Search Results';
		}
		return $title; #  apply_filters( 'tcc_get_page_title', $title, $slug );
	}
}

if ( ! function_exists( 'fluid_page_effects' ) ) {
	function fluid_page_effects( $mypage ) {
		if ( is_page() ) {
			tcc_page_parallax( $mypage );
		}
		# ! get_theme_mod( 'pages_the-title', 'page' ) === 'page'
		if ( ! in_array( get_theme_mod( 'pages_the-title', 'page' ), array( 'no', 'main' ) ) ) {
			tcc_show_page_title( $mypage );
		}
	}
	add_action( 'tcc_inside_page', 'fluid_page_effects' );
}

if ( ! function_exists( 'tcc_page_title' ) ) {
	function tcc_page_title( $slug ) {
		if ( has_action( "tcc_page_title_$slug" ) ) {
			do_action( "tcc_page_title_$slug" );
		} else if ( has_action( 'tcc_page_title' ) ) {
			do_action( 'tcc_page_title', $slug );
		} else if ( get_theme_mod( 'pages_the-title', 'page' ) === 'no' ) {
		} else {
			$title = tcc_get_page_title( $slug );
			if ( $title ) { ?>
				<div id="fluid-page-title-banner" <?php title_class(); ?>>
					<h1 class="text-center" itemprop="headline">
						<?php e_esc_html( wp_strip_all_tags( $title ) ); ?>
					</h1>
				</div><?php
			}
		}
	}
}

if ( ! function_exists( 'tcc_show_page_title' ) ) {
	function tcc_show_page_title( $mypage ) {
		if ( is_page() || is_archive() ) {
			tcc_page_title( $mypage );
		}
	}
}

if ( ! function_exists( 'title_class' ) ) {
	function title_class( $class = '', $post_id = null ) {
		// Separates classes with a single space, collates classes for post DIV
		echo 'class="' . esc_attr( join( ' ', get_title_class( $class, $post_id ) ) ) . '"';
	}
}
