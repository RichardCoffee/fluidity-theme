<?php

/*
 *  File Name:  template-parts/header.php
 *
 */

$page  = get_page_slug();

#do_action('tcc_before_header');
#do_action('tcc_before_header_'.$page); ?>

<header id="fluid-header" <?php microdata()->WPHeader(); ?> role="banner">
	<div class="<?php echo container_type('fluid-header'); ?>"><?php

		#do_action('tcc_pre_header');
		#do_action("tcc_pre_header_$page");
		do_action('tcc_header_body_content');
		do_action('tcc_header_body_content_'.$page);
		#do_action('tcc_post_header');
		#do_action("tcc_post_header_$page"); ?>

	</div>
<header><?php

#do_action('tcc_after_header');
#do_action('tcc_after_header_'.$page);
