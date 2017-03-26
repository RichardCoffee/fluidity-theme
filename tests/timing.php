<?php

$string = 'PMW_Plugin_Privacy';


$start = microtime(true);
$loop = 500000;
for($i=0;$i<$loop;$i++) {
	$load = str_replace( '_', '/', substr( $string, 4 ) );
}
$end = microtime(true);
$len = $end - $start;
echo "\nLoop 1 length:  $len\n";


$start = microtime(true);
$loop = 10000;
for($j=0;$j<$loop;$j++) {
	$load = str_replace( '_', '/', substr( $string, ( strpos( $string, '_' ) + 1 ) ) );
}
$end = microtime(true);
$len = $end - $start;
echo "\nLoop 2 length:  $len\n";


$start = microtime(true);
$loop = 10000;
for($k=0;$k<$loop;$k++) {
	$arr = explode( '_', $string );
	array_shift( $arr );
	$load = implode( '/', $arr );
}
$end = microtime(true);
$len = $end - $start;
echo "\nLoop 3 length:  $len\n\n";
