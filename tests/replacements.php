<?php

echo "\n";

define( 'ABSPATH', 'path goes here' );

global $actions;
$actions = array();


/**  Wordpress functions  **/

function add_action( $action, $callback, $priority = 10, $arg_count = 1 ) {
	global $actions;
	$actions[] = array( $action, $callback, $priority, $arg_count );
	echo " WordPress:  add_action\n";
#	var_dump(func_get_args()); /*
	echo "add_action:  $action\n";
	if ( is_array( $callback ) ) {
		echo "    method:  {$callback[1]}\n";
	} else {
		echo "  function:  $callback\n";
	}
	echo "  priority:  $priority\n";
	echo "      args:  $arg_count\n"; //*/
}

function esc_html__( $text, $domain ) {
	echo "WordPress:  esc_html__\n";
	echo "translate:  $text\n";
}

/**  Special functions  **/

function do_actions() {
	global $actions;
	if ( $actions ) {
		foreach( $actions as $action ) {
#			var_dump( $action ); /*
			echo '  action:  ' . $action[0] . "\n";
			if ( is_array( $action[1] ) ) {
				echo 'callback:  ' . get_class( $action[1][0] ) . '::' . $action[1][1] . "\n";
			} else {
				echo 'callback:  ' . $action[1] . "\n";
			}
			echo 'priority:  ' . $action[2] . "\n";
			echo '    args:  ' . $action[3] . "\n"; //*/
		}
	}
}
