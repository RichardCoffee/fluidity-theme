<?php

$arr1 = array();
$arr2 = array( 'val1','val2');
$arr3 = array( 'key' => 'value' );
$arr4 = 'string';
$arr5 = new stdClass;

$loop = array( $arr1, $arr2, $arr3, $arr4, $arr5 );

foreach( $loop as $test ) {
#	if ( is_array( $test ) ) { // this test always works correctly
/*	if ( (array)$test === $test ) { // this test always works correctly
		echo "an array\n";
	} else {
		echo "not an array\n";
	} */
#	if ( (array)$test !== $test ) { // this test always works correctly
#	if ( ! (array)$test === $test ) { // this test always fails
	if ( ! ( (array)$test === $test ) ) { // this test always works correctly
		echo "not an array\n";
	} else {
		echo "an array\n";
	}
}
