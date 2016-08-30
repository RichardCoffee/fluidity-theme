<?php

/*
 *  File:  includes/index.php
 *
 */

function fluid_index_page($page='index') { ?>

  <div id="fluid-content" class="fluid-<?php echo $page; ?> <?php echo container_type($page); ?>" <?php microdata()->Blog(); ?>>
    <div class="row pad05perc">
      <div class="col-lg-12 col-md-12 col-sm-12 col-sx-12">

        <div class="fluid-sidebar hidden-sm hidden-xs"><?php
          sidebar_layout('single'); ?>
        </div>

        <div id="content" role="main" tabindex="-1"><?php
          if (have_posts()) {

            while (have_posts()) {
              the_post();
              $main = (is_single()) ? 'content' : tcc_layout('content');
              get_template_part("template-parts/$main",fluid_content_slug($page));
            }
            fluid_navigation('below');

          } ?>
        </div><!-- #content -->

        <div class="fluid-sidebar visible-sm visible-xs"><?php
          sidebar_layout('single'); ?>
        </div>

      </div><!-- col-*-12 -->
    </div><!-- .row -->
  </div><!-- .container --><?php

}
