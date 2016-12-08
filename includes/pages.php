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
    $slug = 'stock';
    if ( is_page() ) {
      $slug = get_queried_object()->post_name; }
    else if (is_404()) {
      $slug = '404';
    else {
      $page = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );
if ($page) {
      $slug = $page->post_name;
} else { log_entry('dump',get_queried_object()); }
    }

    return apply_filters('fluidity_page_slug',$slug);
  }
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
