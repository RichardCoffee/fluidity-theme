<?php
@ini_set('display_errors', 1);

echo "\ntesting microdata 1\n";

echo ( 1 + 1 );

echo "\n my $error\n";

require_once( '../classes/microdata.php' );

echo "\ntesting microdata 2\n";

echo microdata()->ImageObject();

echo microdata()->ImageObject( true );
