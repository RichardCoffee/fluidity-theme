<?php
/*
 *  @package Fluidity
 */

if ( ! function_exists( 'debug_calling_function' ) ) {
	/*
	*	Get the calling function.
	*
	*	Retrieve information from the calling function/file, while also
	*	selectively skipping parts of the stack.
	*
	*	@package  Fluidity\Debugging
	*	@requires PHP 5.3.6
	*/
	#	http://php.net/debug_backtrace
	function debug_calling_function( $depth = 1 ) {
		$file = $func = $line = 'n/a';
		$call_trace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS );
		$total_cnt  = count( $call_trace );
		$skip_list  = array( 'call_user_func_array', 'debug_calling_function', 'logging' );
		do {
			$file = ( isset( $call_trace[ $depth ]['file']))       ? $call_trace[ $depth ]['file']     : 'n/a';
			$line = ( isset( $call_trace[ $depth ]['line'] ) )     ? $call_trace[ $depth ]['line']     : 'n/a';
			$depth++;
			$func = ( isset( $call_trace[ $depth ]['function'] ) ) ? $call_trace[ $depth ]['function'] : 'n/a';
		} while( in_array( $func, $skip_list ) && ( $total_cnt > $depth ) );
		return "$file, $func, $line";
	}
}

if ( ! function_exists( 'get_calling_function' ) ) {
	function get_calling_function( $depth = 1 ) {
		$result = debug_calling_function( $depth );
		$trace  = array_map( 'trim', explode( ',', $result ) );
		return $trace[1];
	}
}

if ( ! function_exists( 'was_called_by' ) ) {
	function was_called_by( $func ) {
		$call_trace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS );
		foreach( $call_trace as $current ) {
			if ( ! empty( $current['function'] ) ) {
				if ( $current['function'] === $func )
					return true;
			}
		}
		return false;
	}
}

#  generate log entry, with comment
if ( ! function_exists( 'log_entry' ) ) {
	function log_entry() {
		if ( WP_DEBUG ) {
			$args  = func_get_args();
			$depth = 1;
			if ( $args && is_int( $args[0] ) ) {
				$depth = $args[0];
				unset( $args[0] );
			}
			if ( $depth ) { error_log( debug_calling_function( $depth ) ); }
			foreach( $args as $message ) {
				if ( is_array( $message ) || is_object( $message ) ) {
					error_log( print_r( $message, true ) );
				} else if ( $message === 'dump' ) {
					error_log( print_r( debug_backtrace(), true ) );
				} else {
					error_log( $message );
				}
			}
		}
	}
	if ( defined( 'TCC_LOG_DEFINED' ) ) {
		tcc_log_entry( 'tcc_log_entry defined in '.__FILE__ );
	}
} else {
	if ( defined( 'TCC_LOG_DEFINED' ) ) {
		tcc_log_entry( 'tcc_log_entry NOT defined in '.__FILE__ );
	}
}

if ( ! function_exists( 'is_a_debugger' ) ) {
	function is_a_debugger() {
		$user = wp_get_current_user();
		$list = apply_filters( 'tcc_debugger_list', array(1) );
		if ( $list && in_array( $user->ID, (array)$list ) ) {
log_entry($list,$user);
			return true;
		} else if ( in_array( "administrator", $user->roles ) ) {
log_entry($user);
			return true;
		}
		return false;
	}
}

if ( WP_DEBUG && ! function_exists( 'tcc_log_deprecated' ) ) {
	function tcc_log_deprecated() {
		$args = func_get_args();
		log_entry( $args, 'dump' );
	}
	add_action( 'deprecated_function_run',    'tcc_log_deprecated', 10, 3 );
	add_action( 'deprecated_constructor_run', 'tcc_log_deprecated', 10, 3 );
	add_action( 'deprecated_file_included',   'tcc_log_deprecated', 10, 4 );
	add_action( 'deprecated_argument_run',    'tcc_log_deprecated', 10, 3 );
	add_action( 'deprecated_hook_run',        'tcc_log_deprecated', 10, 4 );
	add_action( 'doing_it_wrong_run',         'tcc_log_deprecated', 10, 3 );
}
