<?php

/*
 *  File:  includes/in_the_loop.php
 *
 *  All functions in this file expect to be run inside the WordPress loop
 *
 */

if (!function_exists('fluid_edit_post_link')) {
  function fluid_edit_post_link($separator=' ') {
    $title  = the_title( '<span class="screen-reader-text">"', '"</span>', false );
    $string = sprintf( esc_attr_x( 'Edit %s', 'Name of current post', 'tcc-fluid' ), $title );
    #edit_post_link( '{'.$string.'}', $separator.'<span class="edit-link">', '</span>' );
    ##  This code replaces the edit_post_link call so that I could add the target attribute
    $link = get_edit_post_link(get_the_ID());
    if ($link) { ?>
      <span class="edit-link">
        <a class="post-edit-link" href="<?php echo $link; ?>" target="_blank"> { <?php
          echo $string; ?>}
        </a>
      </span><?php
    }
  }
}

if (!function_exists('fluid_navigation')) {
  function fluid_navigation($suffix='above') {
    global $wp_query;
    if ($wp_query->max_num_pages>1) {
      $older = esc_html__('Older posts','tcc-fluid');
      $newer = esc_html__('Newer posts','tcc-fluid'); ?>
      <div id="nav-<?php echo $suffix; ?>" class="navigation">
        <h2 class="screen-reader-text"><?php
          esc_html_e( 'Post Navigation', 'tcc-fluid' ); ?>
        </h2>
        <div class="nav-previous pull-left"><?php
          next_posts_link('<span class="meta-nav">&larr;</span> '.$older); ?>
        </div>
        <div class="nav-next pull-right"><?php
          previous_posts_link($newer.' <span class="meta-nav">&rarr;</span>'); ?>
        </div>
      </div><?php
    }
  }
}

if (!function_exists('fluid_next_post_exists')) {
  function fluid_next_post_exists() {
    global $wp_query;
    if ( $wp_query->current_post + 1 < $wp_query->post_count ) {
      return true;
    }
    return false;
  }
}

if (!function_exists('fluid_post_date')) {
  function fluid_post_date() {
    $string = _x('Posted on %1$s by %2$s','first: formatted date string, second: user name','tcc-fluid');
    $date   = get_the_date();
    if ((get_the_modified_date('U')-(60*60*24))>get_the_date('U')) {
      $string = _x('Last modified on %1$s by %2$s','first: formatted date string, second: user name','tcc-fluid');
      $date   = get_the_modified_date();
    } ?>
    <h3 class="text-center"><?php
      echo sprintf($string,$date,microdata()->get_the_author()); ?>
    </h3><?php
  }
}

if (!function_exists('fluid_thumbnail')) {
  function fluid_thumbnail() {
    $css = (tcc_layout('sidebar')=='none') ? 'col-lg-12 col-md-12 col-sm-12 col-xs-12' : 'col-lg-8 col-md-8 col-sm-12 col-xs-12'; ?>
    <div class='<?php echo $css; ?> logo'><?php
       the_post_thumbnail(); ?>
    </div><?php
  }
}
