<?php

/*
 *  header for the creative collective
 *
 */

function collective_header() { ?>

	<div class="row">

		<div class="col-lg-2 col-md-2 col-sm-12 hidden-xs">
			<?php fluidity_header_logo(); ?>
		</div>

		<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
			<?php get_template_part('template-parts/menu'); ?>
		</div>

	</div><?php

}
add_action('tcc_header_body_content', 'collective_header');

function collective_enqueue() {
	wp_enqueue_style('collective', get_theme_file_uri("css/collective.css"), null, FLUIDITY_VERSION);
}
add_action('fluidity_enqueue','collective_enqueue');

function tcc_container_type($css) {
	return 'container';
}
add_filter('fluid_container_type','tcc_container_type');
