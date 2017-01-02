<?php

#  use inside loop only
if (!function_exists('tcc_excerpt_parallax')) {
	function tcc_excerpt_parallax() {
		if (tcc_design('paral')==='yes') { ?>
			<style>
				.post-<?php the_ID(); ?> {
					background-image: url('<?php echo get_featured_url( get_the_ID() ); ?>');
					<?php do_action('tcc_excerpt_parallax'); ?>
					<?php do_action('tcc_excerpt_parallax_'.get_the_ID()); ?>
				}
			</style><?php
		}
	}
}

if (!function_exists('tcc_page_parallax')) {
	function tcc_page_parallax($page) {
		if (tcc_design('paral')==='yes') {
			$pageID = (intval($page,10)>0) ? intval($page,10) : tcc_get_page_id_by_slug($page,'ID');
			$imgURL = get_featured_url($pageID);
			if ($imgURL) { ?>
				<style>
					.parallax-image {
						background-image: url("<?php echo $imgURL; ?>");
						<?php do_action('tcc_page_parallax'); ?>
					<?php do_action("tcc_page_parallax_$page"); ?>
					}
				</style>
				<div class="parallax parallax-image parallax-scroll hidden-xs"></div><?php
			}
		}
	}
}

#  use inside loop only
if (!function_exists('tcc_post_parallax')) {
	function tcc_post_parallax($css='single-parallax') {
		if ((tcc_design('paral')==='yes') && has_post_thumbnail() ) { ?>
			<style>
				.single-parallax {
					background-image: url('<?php echo get_featured_url( get_the_ID() ); ?>');
					<?php do_action('tcc_post_parallax'); ?>
					<?php do_action('tcc_post_parallax_'.get_the_ID()); ?>
				}
			</style>
			<div id="" class="parallax <?php echo $css; ?> parallax-scroll"></div><?php
		}
	}
}
