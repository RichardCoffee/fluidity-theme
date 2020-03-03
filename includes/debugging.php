<?php
/**
 *
 */

/**
 *  Add HTTP response header defaults.  These are defaults only, and will not override pre-existing values.
 *
 * @since 20191028
 * @link https://www.dev2qa.com/how-to-modify-http-response-header-in-wordpress/
 * @link https://paragonie.com/blog/2017/12/2018-guide-building-secure-php-software
 * @link https://scotthelme.co.uk/content-security-policy-an-introduction/
 * @link https://github.com/OWASP/CheatSheetSeries/blob/master/cheatsheets/Content_Security_Policy_Cheat_Sheet.md
 * @link https://csp.withgoogle.com/docs/strict-csp.html
 * @param $headers array
 * @return array
 */
if ( ! function_exists( 'fluid_wp_headers' ) ) {
	function fluid_wp_headers( $headers ) {
		fluid()->log_chance( $headers );
		$defaults = array(
			'X-Content-Type-Options'  => 'nosniff',
			'Referrer-Policy'  => 'no-referrer',
			'X-Frame-Options'  => 'SAMEORIGIN',
			'X-XSS-Protection' => '1; mode=block',
		);
		if ( is_ssl() ) {
			$defaults['Strict-Transport-Security'] = 'max-age=30';
		}
		$csp_key = apply_filters( 'fluid_content_security_policy', '' );  // 'Content-Security-Policy'
		if ( $csp_key ) {
			$csp_policy = apply_filters( 'fluid_csp_policy', '' );  // "default-src 'self'; font-src 'self' fonts.googleapis.com"
			if ( $csp_policy ) {
				$defaults[ $csp_key ] = $csp_policy;
				$csp_uri = apply_filters( 'fluid_csp_report_uri', '' );
				if ( $csp_uri ) {
					$defaults[ $csp_key ] .= "; report-uri $csp_uri";
				}
			}
		}
		$defaults = apply_filters( 'fluid_http_header_defaults', $defaults );
		return array_merge( $defaults, $headers );
	}
	add_filter( 'wp_headers', 'fluid_wp_headers' );
}

if ( ! function_exists( 'fluid_show_color_scheme' ) ) {
	function fluid_show_color_scheme() {
		if ( WP_DEBUG && current_user_can( 'administrator' ) ) { ?>
			<span class="pull-right"><?php
				echo fluid_color(); ?>
			</span><?php
		}
	}
}

#if ( ! function_exists( 'debug_calling_function' ) ) {
	/**
	*	Get the calling function.
	*
	*	Retrieve information from the calling function/file, while also
	*	selectively skipping parts of the stack.
	*
	*	@package    Fluidity
	*	@subpackage Debugging
	*	@requires   PHP 5.3.6
	*/
	#	http://php.net/debug_backtrace
	function debug_calling_function( $depth = 1 ) {
		$default = $file = $func = $line = 'n/a';
		$call_trace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS );
		$total_cnt  = count( $call_trace );
		$depth   = max( min( $depth, $total_cnt ), 1 );
		$file = $depth;
		$func = $total_cnt;
		#	This is not an exhaustive list
		$skip_list  = array(
			'apply_filters',
			'call_user_func',
			'call_user_func_array',
			'logging',
		);
		do {
			$file = ( isset( $call_trace[ $depth ]['file'] ) )     ? $call_trace[ $depth ]['file']     : $default;
			$line = ( isset( $call_trace[ $depth ]['line'] ) )     ? $call_trace[ $depth ]['line']     : $default;
			$depth++;
			$func = ( isset( $call_trace[ $depth ]['function'] ) ) ? $call_trace[ $depth ]['function'] : $default;
		} while( in_array( $func, $skip_list ) && ( $total_cnt > $depth ) );
		return "$file, $func, $line";
	}
#}

if ( ! function_exists( 'was_called_by' ) ) {
	function was_called_by( $func ) {
		$call_trace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS );
		foreach( $call_trace as $current ) {
			if ( ! empty( $current['function'] ) ) {
				if ( $current['function'] === $func ) {
					return true;
				}
			}
		}
		return false;
	}
}

