<?php

/*
 *  header for rtcenterprises
 *
 */

add_action('tcc_header_body_content','rtc_header_content');

function rtc_header_content() { ?>
	<div class="col-lg-3 col-md-2 col-sm-12 hidden-xs">
		<?php fluidity_header_logo(); ?>
	</div>
	<div class="col-lg-9 col-md-10 col-sm-12 col-xs-12">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="pull-right margint1e">
				<?php fluidity_header_bar_login(true); ?>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 margint1e">
			<?php get_template_part('template-parts/menu'); ?>
		</div>
	</div><?php
}

include('header.php');
