<?php

function fluidity_class_loader( $class ) {
	if (substr($class,0,4)==='TCC_') {
		$load = str_replace( '_', '/', substr( $class, (strpos($class,'_')+1) ) );
		$file = FLUIDITY_HOME."classes/{$load}.php";
		if ( is_readable( $file ) ) {
			include $file;
		}
	}
}
spl_autoload_register( 'fluidity_class_loader' );

class_alias('TCC_Roles_Agent','Agent');
class_alias('TCC_Plugin_Paths','Paths');
class_alias('TCC_Query_TermCount','TermCount');
class_alias('TCC_Options_Typography','Typography');
