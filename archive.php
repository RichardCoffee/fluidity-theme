<?php
/*
 * tcc-fluidity/archive.php
 *
 */

$micro = TCC_Microdata::get_instance();

get_header();

$col_primary = "col-md-8";
$col_sidebar = "col-md-4";
$col_second  = "col-md-4";
$clearfix    = "md=4";
$sidebar     = (is_search()) ? 'search' : 'archive';
$has_sidebar = is_active_sidebar($sidebar);
if (!$has_sidebar) {
  $col_primary = "col-md-12";
  $col_second  = "col-md-3";
  $clearfix    = "md=3";
} ?>

<div class="container"><?php
  who_am_i(__FILE__); ?>
  <div class="row">

    <div class="<?php echo $col_primary; ?>"><?php
      if (have_posts()) {
        if (is_search()) { ?>
          <h2 class="text-center"><?php
            $string = _n('%d Search Result found for: %s','%d Search Results found for: %s',$wp_query->found_posts,'tcc-realty');
            echo sprintf($string,$wp_query->found_posts,tcc_archive_title()); ?>
          </h2>
          <div class="col-md-12"><?php
            do_action('tcc_pre_search'); ?>
          </div><?php
        }
        if ($wp_query->max_num_pages>1) tcc_navigation('above');
        $cnt  = 0;
        while(have_posts()) {
          the_post();
          $prop = get_post_meta(get_the_ID(),'prop_action');
          $slug = sanitize_title($prop[0]); ?>
          <div class="<?php echo $col_second; ?>"><?php
            get_template_part('template_parts/content',$slug); ?>
          </div><?php
          apply_clearfix("$clearfix&cnt=".(++$cnt));
        }
        if ($wp_query->max_num_pages>1) tcc_navigation('below');
        if (is_search()) { ?>
          <div class="col-md-12"><?php
            do_action('tcc_post_search'); ?>
          </div><?php
        }
      } ?>
    </div><?php
    if ($has_sidebar) { ?>
      <div class="<?php echo $col_sidebar; ?>"><?php
        get_sidebar($sidebar); ?>
      </div><?php
    } ?>

  </div><!-- .row -->
</div><!-- .container --><?php

get_footer();

function tcc_archive_title() {
  $result = '';
  foreach($_GET as $tax=>$slug) {
    if ($tax==='s') continue;
    if (empty($slug)) continue;
    $name  = false;
    $key   = sanitize_text_field($tax);
    $value = sanitize_text_field($slug);
    $check = substr($key,0,4);
    if ($check=='prop') {
      $name = get_term_name($key,$value);
    } else if (in_array($check,array('min_','max_'))) {
#      $pref = ($check=='min_') ? 'Min' : 'Max';
#      $type = (intval($value)==floatval($value)) ? "%s %s %2d" : "%s %s %3.1f";
#      $name = sprintf($type,$pref,ucfirst(substr($key,4)),floatval($value));
      // this code assumes min only, use above code for min/max
      $type = (intval($value)==floatval($value)) ? "%s %2d" : "%s %3.1f";
      $name = sprintf($type,ucfirst(substr($key,4)),floatval($value));
    }
    if ($name) $result.= (empty($result)) ? $name : " / $name";
  }
  return $result;
}

?>
