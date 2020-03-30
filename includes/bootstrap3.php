<?php
/**
 * includes/bootstrap.php
 * handles loading bootstrap components
 *
 */

/**
 * enqueue all requested bootstrap components
 *
 */
if ( ! function_exists( 'fluidity_enqueue_bootstrap' ) ) {
	function fluidity_enqueue_bootstrap() {
		# bootstrap version being loaded
		$version    = '3.3.7';
		# load component arrays
		$resets     = fluidity_check_bootstrap( 'resets' );
		$core       = fluidity_check_bootstrap( 'core' );
		$components = fluidity_check_bootstrap( 'components' );
		$javascript = fluidity_check_bootstrap( 'javascript' );
		$utility    = fluidity_check_bootstrap( 'utility' );
		# check for possibility of loading aggregate files
		$load_resets     = ( count( $resets )     === 3  ) ? true : false;	#	load reset and dependencies
		$load_core       = ( count( $core )       === 7  ) ? true : false;	#	load all of core
		$load_components = ( count( $components ) === 21 ) ? true : false;	#	load all components not requiring javascript
		$load_javascript = ( count( $javascript ) === 4  ) ? true : false;	#	load all components requiring javascript
		$load_utility    = ( count( $utility )    === 2  ) ? true : false;	#	load utility components
		$load_bootstrap  = ( $load_resets && $load_core && $load_components && $load_javascript && $load_utility ) ? true : false;	#	load all of bootstrap
		if ( $load_bootstrap ) {
			# load all of bootstrap
			wp_enqueue_style( 'bootstrap.css', get_theme_file_uri( 'css/bootstrap.min.css' ), null, $version );
		} else {
			$bootstrap = array();
			# load reset css
			if ( $load_resets ) {
				wp_enqueue_style( 'bootstrap-resets', get_theme_file_uri( 'css/bootstrap/bootstrap-resets.min.css' ), null, $version );
			} else {
				$bootstrap = array_merge( $bootstrap, $resets );
			}
			# load core css
			if ( $load_core ) {
				wp_enqueue_style( 'bootstrap-core', get_theme_file_uri( 'css/bootstrap/bootstrap-core.min.css' ), null, $version );
			} else {
				$bootstrap = array_merge( $bootstrap, $core );
			}
			# load components css
			if ( $load_components ) {
				wp_enqueue_style( 'bootstrap-components', get_theme_file_uri( 'css/bootstrap/bootstrap-components.min.css' ), null, $version );
			} else {
				$bootstrap = array_merge( $bootstrap, $components );
			}
			# load javascript css
			if ( $load_javascript ) {
				wp_enqueue_style( 'bootstrap-javascript', get_theme_file_uri( 'css/bootstrap/bootstrap-javascript.min.css' ), null, $version );
			} else {
				$bootstrap = array_merge( $bootstrap, $javascript );
			}
			# load utility css
			if ( $load_utility ) {
				wp_enqueue_style( 'bootstrap-utility', get_theme_file_uri( 'css/bootstrap/bootstrap-utility.min.css' ), null, $version );
			} else {
				$bootstrap = array_merge( $bootstrap, $utility );
			}
			# load individual components
			if ( $bootstrap ) {
				foreach( $bootstrap as $component ) {
					wp_enqueue_style( "bootstrap-$component", get_theme_file_uri( "css/bootstrap/bootstrap-{$component}.min.css" ), null, $version );
				}
			} else {
				wp_enqueue_style( 'bootstrap.css', get_theme_file_uri( 'css/bootstrap.min.css' ), null, $version );
				$load_bootstrap = true;
			}
		}
		# load javascript file
		if ( $load_bootstrap || $load_javascript || $javascript ) {
			wp_enqueue_script( 'bootstrap.js', get_theme_file_uri( 'js/bootstrap.min.js' ), array( 'jquery' ), $version, true );
		}
	}
}

/**
 * checks theme options for which bootstrap components to load
 *
 * @since 2.3.0
 * @param string $section which bootstrap section to check
 * @return array which components to load
 */
if ( ! function_exists( 'fluidity_register_bootstrap' ) ) {
	function fluidity_check_bootstrap( $section ) {
		$return  = array();
		$options = tcc_bootstrap( $section );
		if ( $options ) {
			foreach( $options as $component => $status ) {
				if ( $status === 'yes' ) {
					$return[] = $component;
				}
			}
		}
		return $return;
	}
}
