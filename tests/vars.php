<?php

$test = 'testing scope';

function testing_scope() {
	$mytest = 'inside scope';
	print_r( get_defined_vars() );
}

testing_scope();
