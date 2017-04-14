<?php

require_once( FLUIDITY_HOME . 'vendor/class-tgm-plugin-activation.php' );

add_action( 'tgmpa_register', 'fluidity_plugins' );

if ( ! function_exists( 'fluidity_plugins' ) ) {
	function fluidity_plugins() {
		$plugins = array(
			array(
				'name'        => 'mobble by Scott Evans',
				'slug'        => 'mobble',
				'require'     =>  false,
				'is_callable' => 'is_mobile',
			),
			array(
				'name'        => 'Nav Menu Roles by Kathy Darling',
				'slug'        => 'nav-menu-roles',
				'require'     =>  false,
				'is_callable' => 'Nav_Menu_Roles',
			),
			array(
				'name'        => 'WordPress SEO by Yoast',
				'slug'        => 'wordpress-seo',
				'required'    =>  false,
				'is_callable' => 'wpseo_init',
			),
		);
		$plugins = apply_filters( 'fluidity_tgmpa_plugins', $plugins );
		$config  = array(
			'id'           => 'tcc-fluid-' . uniqid(), // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => FLUIDITY_HOME . 'vendor/plugins', // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'parent_slug'  => 'themes.php',            // Parent menu slug.
			'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.
		);
		$config = apply_filters( 'fluidity_tgmpa_config', $config );
		if ( $plugins && $config ) {
			tgmpa( $plugins, $config );
		}
	}
}
