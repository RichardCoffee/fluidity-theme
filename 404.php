<?php
/**
 * The template for displaying 404 pages (Not Found)
 */


get_header();
$micro = microdata();
$color = tcc_color_scheme('404');
/*
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
      <h1  class="page-title"><?php esc_attr_e(__("Ooops.....Well this is somewhat embarrassing, isn't it?",'tcc-fluid')); ?></h1>
      <div class="page-content">
        <h2><?php esc_attr_e(__('It seems as if the page you are looking for is not here','tcc-fluid')); ?></h2>
        <h3><?php esc_attr_e(__('It looks like nothing was found at this location','tcc-fluid')); ?></h3>
        <a href="<?php echo home_url() ; ?>"><?php esc_attr_e(__('Home Page','tcc-fluid')); ?></a>
      </div><!-- .page-content -->
    </div><!-- .article -->
  </div><!-- .col-md-12 -->
</div><!-- .row --><?php

get_footer(); ?>
