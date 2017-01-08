<?php
/**
 * The template for displaying 404 pages (Not Found)
 */


get_header();
$micro = microdata();

if (WP_DEBUG && is_user_logged_in()) { ?>
 <div class="panel panel-fluidity collapse-auto">
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
    <article>
      <h1  class="page-title"><?php esc_attr_e(__("Ooops.....Well this is somewhat embarrassing, isn't it?",'tcc-fluid')); ?></h1>
      <div class="page-content">
        <h2><?php esc_attr_e(__('It seems as if the page you are looking for is not here','tcc-fluid')); ?></h2>
        <h3><?php esc_attr_e(__('It looks like nothing was found at this location','tcc-fluid')); ?></h3>
        <div class="row">
          <div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
          <div class="col-lg-4 col-md-6 col-sm-8 col-xs-12">
            <?php get_search_form(); ?>
          </div>
          <div class="col-lg-4 col-md-3 col-sm-2 hidden-xs"></div>
        </div>
        <h1>
          <a href="<?php echo home_url() ; ?>"><?php esc_attr_e(__('Home Page','tcc-fluid')); ?></a>
        </h1>
      </div><!-- .page-content -->
    </article>
  </div><!-- .col-md-12 -->
</div><!-- .row --><?php

get_footer(); ?>
