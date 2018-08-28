<?php

define( 'ABSPATH', 'true' );

require_once( 'stubs.php' );
require_once( '../classes/Trait/Singleton.php' );
require_once( '../classes/microdata.php' );

$attrs = microdata()->ImageObject( true );

print_r( $attrs );

echo "\nafter test\n";
