<?php

/*
 *  File:  includes/pages.php
 *
 *		Custom Fields on Pages:		<?php echo get_post_meta(get_page_id($page), 'key string here', true); ?>
 */

if(!function_exists('get_page_id')) {
	# http://snipplr.com/view/39004/
	# http://www.smipple.net/snippet/elieandraos/Get%20Page%20ID%20By%20Slug
	function get_page_id($slug) {
		static $page_id;
		if (!$page_id) {
			global $wpdb;
			$page_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '$slug' AND post_type = 'page'");
		}
		return $page_id;
	}
}

if (!function_exists('fluid_category_page_title')) {
  function fluid_category_page_title() { ?>
    <h1 class="text-center"><?php single_cat_title(esc_html__('Category: ')); ?></h1><?php
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

if (!function_exists('fluid_save_page_template')) {
	function fluid_save_page_template( $template ) {
		global $fluidity_theme_template;
		$fluidity_theme_template = basename($template,".php");
		return $template;
	}
	add_action('template_include', 'fluid_save_page_template', 1000);
}

if (!function_exists('get_page_slug')) {
	#	http://www.wpaustralia.org/wordpress-forums/topic/pre_get_posts-and-is_front_page/
	function get_page_slug() {
		global $wp_query;
		static $slug = null;
		if (!$slug) {
			if ( !is_admin() && $wp_query->is_main_query() ) {
				global $fluidity_theme_template; // FIXME: this is not a reliable source
				if ( is_home() && empty( $wp_query->query_string ) ) {
					$slug = 'blog';
				} else if ( ( $wp_query->get( 'page_id' ) == get_option( 'page_on_front' ) && get_option( 'page_on_front' ) ) || empty( $wp_query->query_string ) ) {
					$slug = 'front';
				} else {
					$page = get_queried_object(); // wtf?
					if (is_object($page) && !isset($page->post_type)) { log_entry('bad post type value',$fluidity_theme_template,'class: '.get_class($page),$page); }
					if (is_object($page) && isset($page->post_type) && ($page->post_type==='page')) {
						$slug = $page->post_name;
					} else {
#log_entry(0,$fluidity_theme_template);
						$slug = $fluidity_theme_template;
					}
				}
			}
		}
//log_entry(0,$slug.':  '.debug_calling_function());
		return $slug;
	}
}

if (!function_exists('get_page_title')) {
	function get_page_title($slug) {
		return tcc_get_page_id_by_slug($slug,'post_title');
	}
}

if (!function_exists('get_title_class')) {
	function get_title_class( $class = '', $post_id = null ) {
		$slug = get_page_slug();
		$classes = array('page-title',"page-title-$slug");
		if ($class) {
			$classes = array_merge( $classes, (array)$class );
		}
		$classes = apply_filters('get_title_class',$classes);
		$clasess = apply_filters("get_title_class_$slug",$classes);
		return $classes;
	}
}

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

if (!function_exists('tcc_page_title')) {
	function tcc_page_title($slug) {
		if (has_action("tcc_page_title_$slug")) {
			do_action("tcc_page_title_$slug");
		} else {
			$title = get_page_title($slug);
			if ($title) { ?>
				<div id="tcc-page-title-banner" <?php title_class(); ?>>
					<div class="<?php echo container_type('title','container'); ?>">
						<div class="row">
							<h2 class="text-center">
								<?php echo $title; ?>
							</h2>
						</div>
					</div>
				</div><?php
			}
		}
	}
}

if (!function_exists('title_class')) {
	function title_class( $class = '', $post_id = null ) {
		// Separates classes with a single space, collates classes for post DIV
		echo 'class="' . join( ' ', get_title_class( $class, $post_id ) ) . '"';
	}
}
