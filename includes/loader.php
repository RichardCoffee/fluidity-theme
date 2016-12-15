<?php

function fluidity_class_loader( $class ) {
	if (substr($class,0,4)==='TCC_') {
		$load = str_replace( '_', '/', substr( $class, (strpos($class,'_')+1) ) );
log_entry("file: $load");
		$file = FLUIDITY_HOME."classes/{$load}.php";
log_entry(0,"path: $file");
		if ( is_readable( $file ) ) {
			include $file;
log_entry(0,'file included');
		}
else { log_entry('file not readable'); }
	}
}
spl_autoload_register( 'fluidity_class_loader' );
