<?php

/*
 *  File:  includes/pages.php
 *
 */

if (!function_exists('fluid_archive_page_title')) {
  function fluid_archive_page_title() { ?>
    <h1 class="text-center"><?php the_archive_title(); ?></h1><?php
  }
}

if (!function_exists('fluid_archive_page_noposts')) {
  function fluid_archive_page_noposts() {
    $text = esc_html__( 'Apologies, but no results were found for the requested Archive. Perhaps searching will help find a related post.','tcc-fluid' );
    fluid_noposts_page($text);
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
  function get_page_slug() {
    static $slug;
    if (!$slug) {
         global $fluidity_theme_template; // FIXME: this is not a reliable source
         $page = get_queried_object();
if (!is_object($page)) { log_entry('bad page value',$page,$fluidity_theme_template); }
         if (is_object($page) && ($page->post_type==='page')) {
            $slug = $page->post_name;
         } else {
            $slug = $fluidity_theme_template;
         }
    }
    return $slug;
  }
}

if (!function_exists('get_page_title')) {
	function get_page_title($slug) {
		return tcc_get_page_id_by_slug($slug,'post_title');
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
			}
		}
		return $curr->$prop; // FIXME: check for existing property
	}
}

if (!function_exists('tcc_page_title')) {
	function tcc_page_title($slug) { ?>
		<div id="tcc-page-title-banner" class="page-title page-title-<?php echo $slug; ?>">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h2 class="text-center">
							<?php echo get_page_title($slug); ?>
						</h2>
					</div>
				</div>
			</div>
		</div><?php
	}
}

if (!function_exists('tcc_parallax_effect')) {
	function tcc_parallax_effect($page) {
		$pageID = (intval($page,10)>0) ? intval($page,10) : tcc_get_page_id_by_slug($page,'ID');
		$imgURL = get_featured_url($pageID);
		if ($imgURL) { ?>
			<style>
				.parallax-image { background-image: url("<?php echo $imgURL; ?>"); }
			</style>
			<div class="parallax parallax-image"></div><?php
		}
	}
}
