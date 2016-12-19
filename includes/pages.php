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

if (!function_exists('fluidity_page_slug')) {
  function fluidity_page_slug() {
    static $slug;
    if (!$slug) {
			$page = get_queried_object();
			if ($page->post_type==='page') {
				$slug = $page->post_name;
			} else {
				global $fluidity_theme_template;
				$slug = $fluidity_theme_template;
			}
log_entry(0,"Page slug: $slug",$page);
    }
    return $slug;
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

if (!function_exists('fluid_search_page_title')) {
  function fluid_search_page_title() {
    global $wp_query; ?>
    <h2 class="text-center"><?php
      $format = esc_html(_n('%d Search Result found','%d Search Results found',$wp_query->found_posts,'tcc-fluid'));
      $string = sprintf($format,$wp_query->found_posts);
      $string = apply_filters('tcc_search_title',$string);
      echo "<span itemprop='headline'>$string</span>"; ?>
    </h2>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><?php
      do_action('fluid_pre_search'); ?>
    </div><?php
  }
}

if (!function_exists('fluid_search_page_afterposts')) {
  function fluid_search_page_afterposts() { ?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-sx-12"><?php
      do_action('fluid_post_search'); ?>
    </div><?php
  }
}

if (!function_exists('fluid_search_page_noposts')) {
  function fluid_search_page_noposts() {
    $text = esc_html__( 'Apologies, but no results were found for the requested search. Perhaps searching with alternate keyword(s) will help.','tcc-fluid' );
    fluid_noposts_page($text);
  }
}

if (!function_exists('tcc_get_page_id_by_slug')) {
	function tcc_get_page_id_by_slug($slug) {
		static $pageID, $curr;
		if ($curr && ($curr===$slug) && $pageID) { return $pageID; }
		$pageID = 0;
		$args   = array('post_type' => 'page', 'name' => $slug);
		$pages  = new WP_Query($args);
		if ($pages) {
log_entry($pages);
			foreach($pages->posts as $page) {
				if ($page->post_name===$slug) {
					$pageID = $page->ID;
					break;
				}
			}
		}
		$curr = $slug;
		return $pageID;
	}
}

if (!function_exists('tcc_page_title')) {
	function tcc_page_title($page) {
	}
}

if (!function_exists('tcc_parallax_effect')) {
	function tcc_parallax_effect($page) {
		if (tcc_design('paral')==='yes') {
			$pageID = (intval($page,10)>0) ? intval($page,10) : tcc_get_page_id_by_slug($page);
			if ($pageID && has_post_thumbnail($pageID)) {
				$imgID  = get_post_thumbnail_id($pageID);
				$imgURL = wp_get_attachment_url($imgID); ?>
				<style>
					.parallax-image { background-image: url("<?php echo $imgURL; ?>"); }
				</style>
				<div class="parallax parallax-image"></div><?php
			}
		}
	}
}
