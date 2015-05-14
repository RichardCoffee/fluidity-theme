<?php

/*
 *  fluidity/author.php
 *
 * The template for displaying Author archive pages
 *
 * @link http://codex.wordpress.org/Template_Hierachy
 */

get_header();

$sidebar     = (is_search()) ? 'archive' : 'author';
$has_sidebar = is_active_sidebar($sidebar);
$col_primary = ($has_sidebar) ? "col-lg-8 col-md-8" : "col-md-12";
$col_primary.= " col-sm-12 col-xs-12"; ?>

<div class="container"><?php
  who_am_i(); ?>
  <div class="row">

    <div class="<?php echo $col_primary; ?>">

      <div class=""><?php
        $current = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
        $role = $current->roles[0];
        get_template_part('template_parts/profile',$role);
        if (have_posts()) {
          $col = min(12,$wp_query->post_count*4);
          $title_class = "col-lg-$col col-md-$col col-sm-12 col-xs-12";
          $title_posts = apply_filters('tcc_author_posts_title',__('Most Recent Posts','tcc-fluid')); ?>
          <div class='<?php echo $title_class; ?>'>
            <h3 class='text-center'><?php echo $title_posts; ?></h3>
          </div>
          <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'><?php
            tcc_navigation('above');
            while (have_posts()) {
              the_post(); ?>
              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12"><?php
                get_template_part('template_parts/content',get_post_type()); ?>
              </div><?php
              apply_clearfix('lg=4&md=4&sm=6&xs=12');
            }
            tcc_navigation('below'); ?>
          </div><?php
        } else {
          // FIXME: this needs to point to the correct template
          //get_template_part('content','none');
echo "<p>no posts</p>";
        } ?>
      </div>

    </div><!-- .col-md-(8 or 12) --><?php

    if ($has_sidebar) { ?>
      <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"><?php
        get_sidebar($sidebar); ?>
      </div><?php
    } ?>

  </div><!-- .row -->

</div><!-- .container --><?php

get_footer();
