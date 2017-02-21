<?php

$test = test_one() || test_two();

echo $test;


function test_one() { return 'item one'; }
function test_two() { return 'item two'; }
