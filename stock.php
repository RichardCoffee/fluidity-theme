<?php
/*
 *  File Name: index.php
 *
 */


get_header();

$page = get_page_slug(); ?>

<div id="fluid-content" class="fluid-<?php echo $page; ?> <?php echo container_type($page); ?>" <?php microdata()->Blog(); ?>><?php

    who_am_i();

    do_action("fluid_{$page}_page_top"); ?>

    <div class="fluid-sidebar hidden-sm hidden-xs"><?php
        fluidity_sidebar_layout($page); ?>
    </div>

    <div id="content" role="main" tabindex="-1"><?php

        do_action("fluid_{$page}_page_title");

        if (have_posts()) {

            while (have_posts ()) {
                the_post(); ?>
                <div <?php microdata()->BlogPosting(); ?>><?php

                    $main = (is_single() || is_page()) ? 'content' : tcc_layout('content');
                    $slug = fluid_content_slug($page)
                    get_template_part("template-parts/$main",$slug); ?>

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
</div><!-- .container -->


get_footer();
