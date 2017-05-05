<?php

$data = array(
	array(
		'origin' => 'origin-one',
		'target' => 'target-one',
		'match'  => 'match',
	),
	array(
		'origin' => 'origin-two',
		'match'  => 'match',
	),
	array(
		'origin' => 'origin-three',
		'target' => 'target-three',
	),
	array(
		'origin'  => 'origin-four',
		'target'  => 'target-four',
		'nomatch' => 'nomatch-four',
	),
	array(
		'target' => 'target-five',
		'match'  => 'match',
	),
	array(
		'origin' => 'origin-six',
		'targat' => 'target-six',
		'match'  => 'match',
	),
);

function test_case( $item ) {
	$default = array(
		'origin'  => null,
		'target'  => null,
		'match'   => null,
		'nomatch' => null,
	);
	return array_merge( $default, $item );
}

$test1 = array_map( 'test_case', $data );
print_r( $test1 );
