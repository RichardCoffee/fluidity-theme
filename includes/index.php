<?php

/*
 *  File:  includes/index.php
 *
 */

function fluid_index_page($page='index') { ?>

  <div id="fluid-content" class="fluid-<?php echo $page; ?> <?php echo container_type($page); ?>" <?php microdata()->Blog(); ?>>
    <?php who_am_i(1); ?>
    <div class="row pad05perc">
      <div class="col-lg-12 col-md-12 col-sm-12 col-sx-12">

        <div class="fluid-sidebar hidden-sm hidden-xs"><?php
          fluidity_sidebar_layout($page); ?>
        </div>

        <div id="content" role="main" tabindex="-1"><?php
          do_action("fluid_{$page}_page_title");
          if (have_posts()) {

            while (have_posts ()) {
              the_post(); ?>
              <div <?php microdata()->BlogPosting(); ?>><?php
                $main = (is_single()) ? 'content' : tcc_layout('content');
                get_template_part("template-parts/$main",fluid_content_slug($page)); ?>
              </div><?php
              if (fluid_next_post_exists()) echo "<hr class='padbott'>";
            }
            #fluid_navigation('below');
            do_action("fluid_{$page}_page_afterposts");
          } else {
            do_action("fluid_{$page}_page_noposts");
          } ?>
        </div><!-- #content -->

        <div class="fluid-sidebar visible-sm visible-xs"><?php
          fluidity_sidebar_layout($page); ?>
        </div>

      </div><!-- col-*-12 -->
    </div><!-- .row -->
  </div><!-- .container --><?php

}
