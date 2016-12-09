<?php

function fluidity_class_loader( $class ) {
	if (substr($class,0,4)==='TCC_') {
		$dir  = dirname( TCCREP_PLUGIN_FILE );
		$load = str_replace( '_', '/', substr( $class, (strpos($class,'_')+1) ) );
		$file = FLUIDITY_HOME."classes/{$load}.php";
		if ( is_readable( $file ) ) {
			include $file;
		}
	}
}
spl_autoload_register( 'fluidity_class_loader' );
