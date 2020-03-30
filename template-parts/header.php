<?php
/*
 *  File Name:  template-parts/header.php
 *
 */

defined( 'ABSPATH' ) || exit; ?>

<header id="fluid-header" <?php microdata()->WPHeader(); ?>><?php
	$class = array(
		'header-' . tcc_layout( 'header', 'static' ),
		container_type( 'header' )
	); ?>
	<div id="header-container" class="<?php echo fluid()->sanitize_html_class( $class ); ?>">
		<div class="row margint1e marginb1e">

			<div class="hidden-md col-lg-1"></div>
			<div class="col-12 col-lg-10"><?php
				get_template_part('template-parts/menu'); ?>
			</div>
			<div class="hidden-md col-lg-1"></div>

		</div>
	</div><?php
	do_action('fluid_post_header_content', get_page_slug() ); ?>
</header><?php
