<?php

/*
 *  File:  includes/pages.php
 *
 *		Custom Fields on Pages:		<?php echo get_post_meta(get_page_id($page), 'key string here', true); ?>
 */

if ( ! function_exists( 'get_page_id' ) ) {
	# http://snipplr.com/view/39004/
	# http://www.smipple.net/snippet/elieandraos/Get%20Page%20ID%20By%20Slug
	function get_page_id( $slug ) {
		global $wpdb;
		return $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_name = '$slug' AND post_type = 'page'" );
	}
}

if ( ! function_exists( 'fluid_category_page_title' ) ) {
	function fluid_category_page_title() { ?>
		<h1 class="text-center">
			<?php single_cat_title( esc_html__( 'Category: ', 'tcc-fluid' ) ); ?>
		</h1><?php
	}
}

if (!function_exists('fluid_category_page_noposts')) {
  function fluid_category_page_noposts() {
    $text = esc_html__( 'Apologies, but no results were found for the requested Category. Perhaps searching will help find a related post.','tcc-fluid' );
    fluid_noposts_page($text);
  }
}

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

if ( ! function_exists( 'get_page_slug' ) ) {
	#	http://www.wpaustralia.org/wordpress-forums/topic/pre_get_posts-and-is_front_page/
	function get_page_slug( $set_slug = '' ) {
		global $wp_query;
		static $slug = null;
		if ( ! $slug ) {
			if ( $set_slug ) {
				$slug = $set_slug;
			} else if ( defined( 'TCC_PAGE_SLUG' ) ) {
				$slug = TCC_PAGE_SLUG;
			} else if ( !is_admin() && $wp_query->is_main_query() ) {
/*log_entry(
	'is home:  '.is_home(),
	'page id:  '.$wp_query->get( 'page_id' ),
	'  front:  ' . get_option( 'page_on_front' ),
); //*/
				if ( is_home() && empty( $wp_query->query_string ) ) {
					$slug = 'home';
				#} else if ( ( $wp_query->get( 'page_id' ) === get_option( 'page_on_front' ) && get_option( 'page_on_front' ) ) || empty( $wp_query->query_string ) ) {
				} else if ( get_option('page_on_front') && ( $wp_query->get('page_id') === get_option('page_on_front') ) ) {
					$slug = 'front';
				} else {
					$page = get_queried_object();  #  $wp_query->queried_object
					if ( is_object( $page ) && isset( $page->post_type ) && ( $page->post_type === 'page' ) ) {
						$slug = $page->post_name;
					} else {
						global $fluidity_theme_template; // FIXME: this is not a reliable source
						$slug = $fluidity_theme_template;
					}
				}
			}
#log_entry(0,$slug.' called by '.debug_calling_function());
		}
		return $slug;
	}
}

if ( ! function_exists( 'get_page_title' ) ) {
	function get_page_title( $slug ) {
		$title = tcc_get_page_id_by_slug( $slug, 'post_title' );
		if ( is_archive() && ! get_query_var( 'paged' ) ) {
			if ( is_tax() || is_category() || is_tag() ) {
				$descrip = term_description();
				if ( $descrip ) {
					$title =  apply_filters( 'the_content', $descrip );
				}
			}
		}
		return $title;
	}
}

if (!function_exists('get_title_class')) {
	function get_title_class( $class = '', $post_id = null ) {
		$slug = get_page_slug();
		$classes = array('page-title',"page-title-$slug");
		if ($class) {
			$classes = array_merge( $classes, (array)$class ); }
		$classes = apply_filters('get_title_class',$classes);
		$classes = apply_filters("get_title_class_$slug",$classes);
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

if (!function_exists('tcc_get_page_id_by_slug')) {
	function tcc_get_page_id_by_slug($slug,$prop='ID') {
		static $curr;
		if (!$curr || (!$slug===$curr->post_name)) {
			$args   = array('post_type' => 'page', 'name' => $slug);
			$pages  = new WP_Query($args);
			if ($pages) {
				foreach($pages->posts as $page) {
					if ($page->post_name===$slug) {
						$curr = $page;
						break;
					}
				}
				if (!$curr) { return ''; }
			}
		}
		return $curr->$prop; // FIXME: check for existing property
	}
}

/**
 *  @brief Provides filters for css applied to main content tag.
 *
 *  @param string initial css class(es)
 *  @return string css classes to be applied
 */
if ( ! function_exists( 'tcc_main_tag_css' ) ) {
	function tcc_main_tag_css( $css = '' ) {
		$page = get_page_slug();
		if ( ! $css && ( tcc_layout( 'fluid_sidebar' ) === 'no' ) ) {
			$tcc  = tcc_layout( 'main_css' );
			if ( $tcc ) {
				$css = $tcc;
			}
		}
		$css = apply_filters( 'tcc_main_tag_css', $css );
		$css = apply_filters( "tcc_main_tag_css_$page", $css );
		return $css;
	}
}

if ( ! function_exists( 'tcc_page_title' ) ) {
	function tcc_page_title( $slug ) {
		if ( has_action( "tcc_page_title_$slug" ) ) {
			do_action( "tcc_page_title_$slug" );
		} else if ( has_action( 'tcc_page_title' ) ) {
			do_action( 'tcc_page_title' );
		} else if ( tcc_design( 'title' ) === 'no' ) {
		} else {
			$title = get_page_title( $slug );
			if ( $title ) { ?>
				<div id="tcc-page-title-banner" <?php title_class(); ?>>
					<div class="row">
						<h1 class="text-center">
							<?php echo $title; ?>
						</h1>
					</div>
				</div><?php
			}
		}
	}
}

if ( ! function_exists( 'title_class' ) ) {
	function title_class( $class = '', $post_id = null ) {
		// Separates classes with a single space, collates classes for post DIV
		echo 'class="' . join( ' ', get_title_class( $class, $post_id ) ) . '"';
	}
}
