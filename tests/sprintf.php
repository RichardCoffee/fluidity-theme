<?php

$test1 = '%1$sLogged in as %2$s%3$s. %4$sLog out?%5$s';
$test2 = '%sLogged in as %s%s. %2$sLog out?%s';

printf( $test1, 'opt1', 'opt2', 'opt3', 'opt4', 'opt5' );
echo "\n";

printf( $test2, 'opt1', 'opt2', 'opt3', 'opt4', 'opt5' );
echo "\n";


