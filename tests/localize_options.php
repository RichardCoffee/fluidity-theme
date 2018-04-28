<?php

$test = array(
	'sidebar_position' => array(
		'control' => array(
			'sidebar_fluidity',
			'sidebar_mobile'
		),
		'hide' => 'none',
	),
	'widgyt_collapse' => array(
		'control' => array(
			'widgyt_icons'
		),
		'hide' => 'perm'
	),
);

function normalize_options( $options ) {
	$options = array_map(
		function( $control ) {
			return array_merge( [ 'hide' => null, 'show' => null ], $control );
		},
		$options
	);
	$target = array();
	echo "\n";
	foreach( $options as $origin => $showhide ) {
		echo " origin: $origin\n";
		foreach( $showhide['control'] as $control ) {
		echo "control: $control\n";
			$target[ $control ] = $origin;
		}
	}
	if ( count( $target ) > 0 ) {
		$options['target'] = $target;
	}
	return $options;
}

print_r( $test );

$after = normalize_options( $test );

print_r( $after );

