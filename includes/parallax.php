<?php
/*
if (!function_exists('tcc_parallax')) {
|  function tcc_parallax() {
|  |  $paras = get_option('tcc_options_parallax');
log_entry('paras',$paras);
|  |  if ($paras) {
|  |  |  $string = '.para-img-%1$s { background-image: url("%2$s"); height:400px; }';
|  |  |  foreach($paras as $page=>$para) {
|  |  |  |  echo sprintf($string,$page,$para);
|  |  |  }
|  |  }
|  }
|  if (tcc_design('paral')==='yes') {
|  |  add_action('tcc_custom_css','tcc_parallax');
|  }
} //*/

#  use inside loop only
if (!function_exists('tcc_excerpt_parallax')) {
	function tcc_excerpt_parallax() {
		if (tcc_design('paral')==='yes') { ?>
			<style>
				.post-<?php the_ID(); ?> {
					background-image: url('<?php echo get_featured_url( get_the_ID() ); ?>');
					<?php do_action('tcc_excerpt_parallax'); ?>
					<?php //do_action('tcc_excerpt_parallax_'.get_the_ID()); ?>
				}
			</style><?php
		}
	}
}

if (!function_exists('tcc_page_parallax')) {
	function tcc_page_parallax( $div=true ) {
		if (tcc_design('paral')==='yes') {
			global $post;
			$pageID = $post->ID;
			if ($pageID) {
				$imgURL = get_featured_url($pageID);
				if ($imgURL) { ?>
					<style>
						.parallax-image {
							background-image: url("<?php echo $imgURL; ?>");
							<?php do_action('tcc_page_parallax'); ?>
							<?php do_action('tcc_page_parallax_'.get_page_slug()); ?>
						}
					</style><?php
					$divcss = 'parallax parallax-image parallax-scroll parallax-page-'.get_page_slug();
					if ($div) { ?>
						<div class="<?php echo $divcss; ?>"></div><?php
					}
					return $divcss;
				}
				return 'no-featured-image-for-page-parallax';
			}
			return 'no-page-found-in-tcc_page_parallax';
		}
		return 'parallax-is-off-in-theme-options';
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
