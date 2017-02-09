<?php

/*
 *  author.php
 *
 */

get_header();

$mypage      = get_page_slug();
$sidebar     = (is_search()) ? 'archive' : 'author';
$has_sidebar = is_active_sidebar($sidebar);
$col_primary = ($has_sidebar) ? "col-lg-8 col-md-8" : "col-lg-12 col-md-12";
$col_primary.= " col-sm-12 col-xs-12"; ?>

<div id="fluid-content" class="fluid-author <?php echo container_type($mypage); ?>" role="main" <?php microdata()->Person(); ?>><?php
  who_am_i(); ?>
  <div class="row">

    <div id="content" class="<?php echo $col_primary; ?>" role="main" tabindex="-1">

      <div class=""><?php
        $current = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
        $role = $current->roles[0];
        get_template_part('template-parts/profile',$role);
        if (have_posts()) {

          // FIXME
          $col = min(12,$wp_query->post_count*4);
          $title_class = "col-lg-$col col-md-$col col-sm-12 col-xs-12";
          $title_posts = apply_filters( 'tcc_author_posts_header', __('Most Recent Posts','tcc-fluid')); ?>

          <div class='<?php echo esc_attr( $title_class ); ?>' itemprop='headline'>
            <h3 class='text-center'>
              <?php echo esc_html($title_posts); ?>
            </h3>
          </div>

          <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12' <?php microdata()->Blog(); ?>><?php
            $cnt = 0;
            while (have_posts()) {
              the_post();
              $css = 'lg=4&md=4&sm=6&xs=12&cnt='; ?>
              <div class="<?php echo tcc_bootstrap_css($css); ?>" <?php microdata()->BlogPosting(); ?>><?php
                get_template_part('template-parts/excerpt',get_post_type()); ?>
              </div><?php
              tcc_apply_clearfix($css.++$cnt);
            } ?>
          </div><?php

        } else {
          // FIXME: this needs to point to the correct template
          //get_template_part('content','none');
echo "<p>no posts by this person</p>";
        } ?>
      </div>

    </div><!-- .col-md-(8 or 12) --><?php

    if ($has_sidebar) { ?>
      <div class="col-lg-4 col-md-4 hidden-sm hidden-xs" <?php microdata()->SideBar(); ?>><?php
        get_sidebar($sidebar); ?>
      </div><?php
    } ?>

  </div><!-- .row -->

</div><!-- .container --><?php

get_footer();
