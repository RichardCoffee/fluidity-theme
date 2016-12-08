<?php
/**
 * The template for displaying 406 pages (Not Found)
 *
 *  Note:  this file will never be used because a 406 error is generated before Wordpress gets run, hence it never gets run.
 */


get_header();
$color = tcc_color_scheme('404');

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
    <article>
      <h1  class="page-title"><?php esc_attr_e("Ooops.....Well this is somewhat embarrassing, isn't it?",'tcc-fluid'); ?></h1>
      <div class="page-content">
        <h2><?php esc_attr_e('Generally a 406 error is caused because a request has been blocked by Mod Security.','tcc-fluid'); ?></h2>
        <h3><?php esc_attr_e('If you believe that your request has been blocked by mistake please contact the web site owner.','tcc-fluid'); ?></h3>
        <a href="<?php echo home_url() ; ?>"><?php esc_attr_e('Home Page','tcc-fluid'); ?></a>
      </div><!-- .page-content -->
    </article>
  </div><!-- .col-md-12 -->
</div><!-- .row --><?php

get_footer(); ?>
