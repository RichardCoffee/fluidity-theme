<?php
/*
 *  File Name:  template-parts/header.php
 *
 */

defined( 'ABSPATH' ) || exit; ?>

<header id="fluid-header" <?php microdata()->WPHeader(); ?>>
	<div class="<?php e_esc_attr( 'header-' . tcc_layout( 'header', 'static' ) ); ?> <?php e_esc_attr( container_type( 'header' ) ); ?>">
		<div class="row margint1e marginb1e">

			<div class="col-lg-1  col-md-1  hidden-sm hidden-xs"></div>
			<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12"><?php
				get_template_part('template-parts/menu'); ?>
			</div>
			<div class="col-lg-1  col-md-1  hidden-sm hidden-xs"></div>

		</div>
	</div><?php
	do_action('fluid_post_header_content', get_page_slug() ); ?>
</header><?php
