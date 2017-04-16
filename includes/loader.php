<?php

#echo 'including ' . __FILE__ . "\n";

function fluidity_class_loader( $class ) {
	if ( substr( $class, 0, 4 ) === 'TCC_' ) {
		$load = str_replace( '_', '/', substr( $class, ( strpos( $class, '_' ) + 1 ) ) );
		$file = FLUIDITY_HOME . 'classes/' .  $load . '.php';
		if ( is_readable( $file ) ) {
			include $file;
		}
	}
}
spl_autoload_register( 'fluidity_class_loader' );

if ( ! function_exists( 'fluid_comment' ) ) {
	function fluid_comment() {
		return TCC_Theme_Comment::instance();
	}
}
