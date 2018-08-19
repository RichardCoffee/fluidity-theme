<?php

function generate_filters() {
	$templates = array(
		'fluid_author_template_dir'  => 'template-parts',
		'fluid_author_template_root' => 'profile',
		'fluid_header_template_dir'  => 'template-parts',
		'fluid_header_template_root' => 'header',
		'fluid_loop_template_dir'    => 'template-parts',
#		'fluid_loop_template_root'   => $root, // dynamically assigned
	);
	foreach( $templates as $filter => $values ) {
		add_filter( $filter, function( $assigned, $slug ) use ( $values ) {
			return $values;
		});
	}
}
