<?php

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

if (!function_exists('single_search_result')) {
	#	http://www.hongkiat.com/blog/wordpress-tweaks-for-post-management/
	function single_search_result() {
		if (is_search() || is_archive()) {
			global $wp_query;
			if ($wp_query->post_count==1) {
				wp_redirect(get_permalink($wp_query->posts['0']->ID));
			}
		}
	}
	add_action('template_redirect','single_search_result');
}
