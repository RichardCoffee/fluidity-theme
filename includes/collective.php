<?php

/*
 *  header for the creative collective
 *
 */
/*
#  force use of collective color scheme
function collective_color_scheme($color) {
    return 'collective';
}
add_filter('tcc_color_scheme','collective_color_scheme'); //*/

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
add_action('tcc_enqueue','collective_enqueue');

function collective_container_type($css) {
#	if (!(strpos($css,'container-fluid nopad')===false)) { // FIXME:  why does this not work?
	$pos = strpos($css,'container-fluid nopad');
	if ($pos===false) { } else {
		$css = str_replace('container-fluid nopad','container',$css);
	}
	return $css;
}
add_filter('fluid_container_type','collective_container_type'); //*/

function collective_sidebars($sidebars) {
	unset($sidebars['three'],$sidebars['four']);
	$sidebars['two']['before_widget'] = "<div class='col-lg-6 col-md-6 col-sm-6 col-xs-12'>";
	$sidebars['two']['after_widget']  = "</div>";
	return $sidebars;
}
add_filter('tcc_register_sidebars','collective_sidebars');
