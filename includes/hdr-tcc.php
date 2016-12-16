<?php

/*
 *  header for the creative collective
 *
 */

function collective_header() { ?>

	<div class="row">

		<div class="col-lg-2 col-md-2 col-sm-12 hidden-xs">
			fluidity_header_logo();
		</div>

		<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
			<?php get_template_part('template-parts/menu'); ?>
		</div>

	</div><?php

}
add_action('tcc_header_body_content', 'collective_header');
