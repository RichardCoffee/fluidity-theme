<?php

$test = test_one() || test_two();

$test2 = ( test_one() ) ? test_one() : test_two();

echo "$test\n";
echo "$test2\n";


function test_one() { return ''; }
function test_two() { return 'item two'; }
