<?php
/*
if (!function_exists('tcc_parallax')) {
|  function tcc_parallax() {
|  |  $paras = get_option('tcc_options_parallax');
fluid()->log('paras',$paras);
|  |  if ($paras) {
|  |  |  $string = '.para-img-%1$s { background-image: url("%2$s"); height:400px; }';
|  |  |  foreach($paras as $page=>$para) {
|  |  |  |  echo sprintf($string,$page,$para);
|  |  |  }
|  |  }
|  }
|  if (tcc_design('paral', 'no' )==='yes') {
|  |  add_action('tcc_custom_css','tcc_parallax');
|  }
} //*/

#  use inside loop only
if (!function_exists('tcc_excerpt_parallax')) {
	function tcc_excerpt_parallax() {
		if (tcc_design('paral', 'no' )==='yes') { ?>
			<style>
				.post-<?php the_ID(); ?> {
					background-image: url('<?php echo esc_url_raw( get_featured_url( ) ); ?>');
					<?php do_action( 'tcc_excerpt_parallax' ); ?>
				}
			</style><?php
		}
	}
}

if (!function_exists('tcc_page_parallax')) {
	function tcc_page_parallax( $div=true ) {
		if (tcc_design('paral', 'no' )==='yes') {
			global $post;
			$pageID = $post->ID;
			if ($pageID) {
				$imgURL = get_featured_url($pageID);
				if ($imgURL) {
					$slug = get_page_slug(); ?>
					<style>
						.parallax-image {
							background-image: url("<?php echo esc_url_raw( $imgURL ); ?>");
							<?php do_action('tcc_page_parallax'); ?>
							<?php do_action("tcc_page_parallax_$slug"); ?>
						}
					</style><?php
					$divcss = 'parallax parallax-image parallax-scroll parallax-page-'.get_page_slug();
					if ($div) { ?>
						<div class="<?php e_esc_attr( $divcss ); ?>"></div><?php
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
		if ((tcc_design('paral', 'no' )==='yes') && has_post_thumbnail() ) { ?>
			<style>
				.single-parallax {
					background-image: url('<?php echo esc_url_raw( get_featured_url() ); ?>');
					<?php do_action('tcc_post_parallax'); ?>
				}
			</style>
			<div id="" class="parallax <?php e_esc_attr( $css ); ?> parallax-scroll">
			</div><?php
		}
	}
}
