<?php

/*
 *  File:  includes/in-the-loop.php
 *
 *  All functions in this file expect to be run inside the WordPress loop
 *
 */

if (!function_exists('fluid_content_slug')) {
  function fluid_content_slug($type='single') {
    $slug = ($format=get_post_format()) ? $format : get_post_type();
    $slug = apply_filters("tcc-content",$slug);
    $slug = apply_filters("tcc-content-{$slug}",$slug);
    $slug = apply_filters("tcc-{$type}-content",$slug);
    $slug = apply_filters("tcc-{$type}-content-{$slug}",$slug);
    return $slug;
  }
}

if (!function_exists('fluid_edit_post_link')) {
  function fluid_edit_post_link($separator=' ') {
    $title  = the_title( '<span class="sr-only">"', '"</span>', false );
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
  function fluid_navigation($taxonomy='') {
    $left  = '&laquo; %link';
    $right = '%link &raquo;';
    $text  = '%title';
    $restrict = true;
    $exclude  = ''; ?>
    <div id="nav-posts" class="row noprint">
      <h2 class="screen-reader-text"><?php
        esc_attr_e( 'Post Navigation', 'tcc-fluid' ); ?>
      </h2>
      <div class="nav-previous pull-left"><?php
        previous_post_link($left,$text,$restrict,$exclude,$taxonomy); ?>
      </div>
      <div class="nav-next pull-right"><?php
        next_post_link($right,$text,$restrict,$exclude,$taxonomy); ?>
      </div>
    </div><?php
  }
}

if (!function_exists('fluid_next_post_exists')) {
  function fluid_next_post_exists() {
    global $wp_query;
    return (bool)( $wp_query->current_post + 1 < $wp_query->post_count );
  }
}

if (!function_exists('fluid_post_date')) {
  function fluid_post_date() {
    $string = esc_html_x('Posted on %1$s by %2$s','formatted date string, user name','tcc-fluid');
    $date   = get_the_date();
    $layout = tcc_settings('postdate');
log_entry("Layout:  $layout");
log_entry(0,"Modified Date:  ".get_the_modified_date('U'));
log_entry(0,"Modified Calc:  ".(get_the_modified_date('U')-(60*60*24)));
log_entry(0,"Post Date:      ".get_the_date('U'));
    if (($layout==='modified') && ((get_the_modified_date('U')-(60*60*24))>(get_the_date('U')))) {
      $string = esc_html_x('Last modified on %1$s by %2$s','formatted date string, user name','tcc-fluid');
      $date   = get_the_modified_date();
log_entry(0,'modified');
    }
else log_entry(0,'not modified');
 ?>
    <h3 class="text-center"><?php
      echo sprintf($string,$date,microdata()->get_the_author()); ?>
    </h3><?php
  }
}

if (!function_exists('fluid_thumbnail')) {
  function fluid_thumbnail() {
    $css = (tcc_layout('sidebar')==='none') ? 'col-lg-12 col-md-12 col-sm-12 col-xs-12' : 'col-lg-8 col-md-8 col-sm-12 col-xs-12'; ?>
    <div class='<?php echo $css; ?> logo'><?php
       the_post_thumbnail(); ?>
    </div><?php
  }
}

if (!function_exists('get_the_author_posts_link')) {
  function get_the_author_posts_link($authorID=0) {
    $authorID = ($authorID) ? $authorID : get_the_author_meta('ID');
    if ($authorID) {
      $link   = get_author_posts_url($agent->ID);
      #$link   = str_replace('/author/','/agent/',$link);  // FIXME:  check for appropriate link stem
      return "<a href='$link'>".get_the_author_meta('display_name')."</a>";
    }
    return '';
  }
}
