<?php

if ( ! function_exists( 'fluidity_register_bootstrap' ) ) {
	function fluidity_register_bootstrap() {
		wp_register_style( 'bootstrap.css',                  get_theme_file_uri( 'css/bootstrap.min.css' ),                                null, '3.3.7' );
		wp_register_style( 'bootstrap-alerts',               get_theme_file_uri( 'css/bootstrap/bootstrap-alerts.min.css' ),               null, '3.3.7' );
		wp_register_style( 'bootstrap-badges',               get_theme_file_uri( 'css/bootstrap/bootstrap-badges.min.css' ),               null, '3.3.7' );
		wp_register_style( 'bootstrap-breadcrumbs',          get_theme_file_uri( 'css/bootstrap/bootstrap-breadcrumbs.min.css' ),          null, '3.3.7' );
		wp_register_style( 'bootstrap-button-groups',        get_theme_file_uri( 'css/bootstrap/bootstrap-button-groups.min.css' ),        null, '3.3.7' );
		wp_register_style( 'bootstrap-buttons',              get_theme_file_uri( 'css/bootstrap/bootstrap-buttons.min.css' ),              null, '3.3.7' );
		wp_register_style( 'bootstrap-carousel',             get_theme_file_uri( 'css/bootstrap/bootstrap-carousel.min.css' ),             null, '3.3.7' );
		wp_register_style( 'bootstrap-close',                get_theme_file_uri( 'css/bootstrap/bootstrap-close.min.css' ),                null, '3.3.7' );
		wp_register_style( 'bootstrap-code',                 get_theme_file_uri( 'css/bootstrap/bootstrap-code.min.css' ),                 null, '3.3.7' );
		wp_register_style( 'bootstrap-component-animations', get_theme_file_uri( 'css/bootstrap/bootstrap-component-animations.min.css' ), null, '3.3.7' );
		wp_register_style( 'bootstrap-components',           get_theme_file_uri( 'css/bootstrap/bootstrap-components.min.css' ),           null, '3.3.7' );
		wp_register_style( 'bootstrap-core',                 get_theme_file_uri( 'css/bootstrap/bootstrap-core.min.css' ),                 null, '3.3.7' );
		wp_register_style( 'bootstrap-dropdowns',            get_theme_file_uri( 'css/bootstrap/bootstrap-dropdowns.min.css' ),            null, '3.3.7' );
		wp_register_style( 'bootstrap-forms',                get_theme_file_uri( 'css/bootstrap/bootstrap-forms.min.css' ),                null, '3.3.7' );
		wp_register_style( 'bootstrap-glyphicons',           get_theme_file_uri( 'css/bootstrap/bootstrap-glyphicons.min.css' ),           null, '3.3.7' );
		wp_register_style( 'bootstrap-grid',                 get_theme_file_uri( 'css/bootstrap/bootstrap-grid.min.css' ),                 null, '3.3.7' );
		wp_register_style( 'bootstrap-input-groups',         get_theme_file_uri( 'css/bootstrap/bootstrap-input-groups.min.css' ),         null, '3.3.7' );
		wp_register_style( 'bootstrap-javascript',           get_theme_file_uri( 'css/bootstrap/bootstrap-javascript.min.css' ),           null, '3.3.7' );
		wp_register_style( 'bootstrap-jumbotron',            get_theme_file_uri( 'css/bootstrap/bootstrap-jumbotron.min.css' ),            null, '3.3.7' );
		wp_register_style( 'bootstrap-labels',               get_theme_file_uri( 'css/bootstrap/bootstrap-labels.min.css' ),               null, '3.3.7' );
		wp_register_style( 'bootstrap-list-group',           get_theme_file_uri( 'css/bootstrap/bootstrap-list-group.min.css' ),           null, '3.3.7' );
		wp_register_style( 'bootstrap-media',                get_theme_file_uri( 'css/bootstrap/bootstrap-media.min.css' ),                null, '3.3.7' );
		wp_register_style( 'bootstrap-modals',               get_theme_file_uri( 'css/bootstrap/bootstrap-modals.min.css' ),               null, '3.3.7' );
		wp_register_style( 'bootstrap-navbar',               get_theme_file_uri( 'css/bootstrap/bootstrap-navbar.min.css' ),               null, '3.3.7' );
		wp_register_style( 'bootstrap-navs',                 get_theme_file_uri( 'css/bootstrap/bootstrap-navs.min.css' ),                 null, '3.3.7' );
		wp_register_style( 'bootstrap-normalize',            get_theme_file_uri( 'css/bootstrap/bootstrap-normalize.min.css' ),            null, '3.3.7' );
		wp_register_style( 'bootstrap-pager',                get_theme_file_uri( 'css/bootstrap/bootstrap-pager.min.css' ),                null, '3.3.7' );
		wp_register_style( 'bootstrap-pagination',           get_theme_file_uri( 'css/bootstrap/bootstrap-pagination.min.css' ),           null, '3.3.7' );
		wp_register_style( 'bootstrap-panels',               get_theme_file_uri( 'css/bootstrap/bootstrap-panels.min.css' ),               null, '3.3.7' );
		wp_register_style( 'bootstrap-popovers',             get_theme_file_uri( 'css/bootstrap/bootstrap-popovers.min.css' ),             null, '3.3.7' );
		wp_register_style( 'bootstrap-print',                get_theme_file_uri( 'css/bootstrap/bootstrap-print.min.css' ),                null, '3.3.7' );
		wp_register_style( 'bootstrap-progress-bars',        get_theme_file_uri( 'css/bootstrap/bootstrap-progress-bars.min.css' ),        null, '3.3.7' );
		wp_register_style( 'bootstrap-resets',               get_theme_file_uri( 'css/bootstrap/bootstrap-resets.min.css' ),               null, '3.3.7' );
		wp_register_style( 'bootstrap-responsive-embed',     get_theme_file_uri( 'css/bootstrap/bootstrap-responsive-embed.min.css' ),     null, '3.3.7' );
		wp_register_style( 'bootstrap-responsive-utilities', get_theme_file_uri( 'css/bootstrap/bootstrap-responsive-utilities.min.css' ), null, '3.3.7' );
		wp_register_style( 'bootstrap-scaffolding',          get_theme_file_uri( 'css/bootstrap/bootstrap-scaffolding.min.css' ),          null, '3.3.7' );
		wp_register_style( 'bootstrap-tables',               get_theme_file_uri( 'css/bootstrap/bootstrap-tables.min.css' ),               null, '3.3.7' );
		wp_register_style( 'bootstrap-thumbnails',           get_theme_file_uri( 'css/bootstrap/bootstrap-thumbnails.min.css' ),           null, '3.3.7' );
		wp_register_style( 'bootstrap-tooltip',              get_theme_file_uri( 'css/bootstrap/bootstrap-tooltip.min.css' ),              null, '3.3.7' );
		wp_register_style( 'bootstrap-type',                 get_theme_file_uri( 'css/bootstrap/bootstrap-type.min.css' ),                 null, '3.3.7' );
		wp_register_style( 'bootstrap-utilities',            get_theme_file_uri( 'css/bootstrap/bootstrap-utilities.min.css' ),            null, '3.3.7' );
		wp_register_style( 'bootstrap-utility',              get_theme_file_uri( 'css/bootstrap/bootstrap-utility.min.css' ),              null, '3.3.7' );
		wp_register_style( 'bootstrap-wells',                get_theme_file_uri( 'css/bootstrap/bootstrap-wells.min.css' ),                null, '3.3.7' );
		wp_register_script('bootstrap.js', get_theme_file_uri('js/bootstrap.min.js'),   array('jquery'),'3.3.7',true);
log_entry('bootstrap registered');
	}
}

if ( ! function_exists( 'fluidity_enqueue_bootstrap' ) ) {
	function fluidity_enqueue_bootstrap() {
		$resets     = fluidity_check_bootstrap( 'resets' );
		$core       = fluidity_check_bootstrap( 'core' );
		$components = fluidity_check_bootstrap( 'components' );
		$javascript = fluidity_check_bootstrap( 'javascript' );
		$utility    = fluidity_check_bootstrap( 'utility' );
		$load_resets     = ( count( $resets )     === 3  ) ? true : false;	#	load reset and dependencies
		$load_core       = ( count( $core )       === 7  ) ? true : false;	#	load all of core
		$load_components = ( count( $components ) === 21 ) ? true : false;	#	load all components not requiring javascript
		$load_javascript = ( count( $javascript ) === 4  ) ? true : false;	#	load all components requiring javascript
		$load_utility    = ( count( $utility )    === 2  ) ? true : false;	#	load utility components
		$load_bootstrap  = ( $load_resets && $load_core && $load_components && $load_javascript && $load_utility ) ? true : false;	#	load all of bootstrap
		if ( $load_bootstrap ) {
			wp_enqueue_style( 'bootstrap.css' );
		} else {
			$bootstrap = array();
			if ( $load_resets ) {
				wp_enqueue_style( 'bootstrap-resets' );
			} else {
				$bootstrap = array_merge( $bootstrap, $resets );
			}
			if ( $load_core ) {
				wp_enqueue_style( 'bootstrap-core' );
			} else {
				$bootstrap = array_merge( $bootstrap, $core );
			}
			if ( $load_components ) {
				wp_enqueue_style( 'bootstrap-components' );
			} else {
				$bootstrap = array_merge( $bootstrap, $components );
			}
			if ( $load_javascript ) {
				wp_enqueue_style( 'bootstrap-javascript' );
			} else {
				$bootstrap = array_merge( $bootstrap, $javascript );
			}
			if ( $load_utility ) {
				wp_enqueue_style( 'bootstrap-utility' );
			} else {
				$bootstrap = array_merge( $bootstrap, $utility );
			}
			if ( $bootstrap ) {
				foreach( $bootstrap as $component ) {
					wp_enqueue_style( "bootstrap-$component" );
				}
			}
			if ( $load_bootstrap || $load_javascript || $javascript ) {
				wp_enqueue_script( 'bootstrap.js' );
			}
		}
log_entry('bootstrap enqueued',$load_bootstrap,$bootstrap,$load_resets,$resets,$load_core,$core,$load_components,$components,$load_javascript,$javascript,$load_utility,$utility);
	}
}

function fluidity_check_bootstrap( $section ) {
	$return = array();
	$options = tcc_bootstrap( $section );
log_entry("section:  $section",$options);
	if ( $options ) {
		foreach( $options as $component => $status ) {
			if ( $status === 'yes' ) {
				$return[] = $component;
			}
		}
	}
	return $return;
}
