<?php
/**
 * The template for displaying 404 pages (Not Found)
 */

get_header();

$color = tcc_color_scheme('404'); ?>

if (WP_DEBUG) { ?>
 <div class="panel panel-<?php echo $color; ?> collapse-auto">
   <div class="panel-heading">
      <h3 class="panel-title">Word Press Information</h3>
    </div>
    <div class="panel-body">
      <pre><?php
        print_r($_GET);
        print_r($_POST);
        print_r($wp_query); ?>
      </pre><?php
      debug_rewrite_rules(); ?>
    </div>
  </div><?php
} //*/ ?>

<div class="row text-center">
  <div class="col-md-12">
    <div class="article">
      <a  href="<?php echo home_url() ; ?>/"><img src='<?php echo tcc_design('logo'); ?>'></a>
      <h1  class="page-title"><?php _e( "Ooops.....Well this is somewhat embarrassing, isn't it?", 'fluid-theme' ); ?></h1>
      <div class="page-content">
        <h2><?php _e( 'It seems as if the page you are looking for is not here', 'fluid-theme' ); ?></h2>
        <p><?php _e( 'It looks like nothing was found at this location', 'fluid-theme' ); ?></p>
        <a href="<?php echo home_url() ; ?>">Click here to go back home! </a>
      </div><!-- .page-content -->
    </div><!-- .article -->
  </div><!-- .col-md-12 .inner-padding -->
</div><!-- .row --><?php

get_footer(); ?>
