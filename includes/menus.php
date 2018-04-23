<?php

/*
 * includes/menus.php
 *
 * Sources: http://codex.wordpress.org/Navigation_Menus
 *          http://catapultdesign.co.uk/programmatically-create-menu-in-wordpress/
 *
 */

if ( ! function_exists( 'tcc_register_nav_menu' ) ) {
	function tcc_register_nav_menu() {
		$menus = array(
			'primary' => esc_html__( 'Site Menu', 'tcc-fluid' ),
#			'header'  => esc_html__( 'Header Menu', 'tcc-fluid' ),
#			'footer'  => esc_html__( 'Footer Menu', 'tcc-fluid' )
		);
		$items = array(
			'primary' => array(
				array(
					'menu-item-title'  => esc_html__( 'Home', 'tcc-fluid' ),
					'menu-item-url'    => home_url( '/' ),
					'menu-item-status' => 'publish'
				)
			),
#					'header'  => array(),
#					'footer'  => array()
		);
		register_nav_menus( $menus );
		$locations  = get_theme_mod( 'nav_menu_locations' );
		$registered = get_registered_nav_menus();
		foreach( $menus as $key => $title) {
			if ( count( $items[ $key ] ) == 0 ) { continue; }
			if ( has_nav_menu( $key ) ) {
				$menu = wp_get_nav_menu_object( $key );
			} else {
				$menu_id = wp_create_nav_menu( $title );
				if ( is_wp_error( $menu_id ) ) {
					fluid()->log(
						"error creating menu $key", $menu_id,
						"registered menus",get_registered_nav_menus(),
						"menu locations",get_nav_menu_locations()
					);
				} else {
					foreach( $items[ $key ] as $item ) {
						$result = wp_update_nav_menu_item( $menu_id, 0, $item );
						if ( is_wp_error( $result ) ) {
							fluid()->log( "Error creating $title item {$items[ $key ]['menu-item-title']}", $result );
						}
					}
					$locations[ $key ] = $menu_id;
				}
			}
		}
		set_theme_mod( 'nav_menu_locations', $locations );
	}
	add_action( 'init', 'tcc_register_nav_menu' );
}
/*
if (!function_exists('tcc_taxonomy_nav_menu')) {
	function tcc_taxonomy_nav_menu($items,$menu,$args) {
fluid()->log(func_get_args());
		return $items;
	}
	add_filter( 'wp_get_nav_menu_items', 'tcc_taxonomy_nav_menu',10,3 );
} //*/
