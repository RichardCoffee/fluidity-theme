<?php

if (!function_exists('tcc_admin_howdy')) {
	#	http://www.wpbeginner.com/wp-tutorials/how-to-change-the-howdy-text-in-wordpress-3-3-admin-bar/
	#	https://premium.wpmudev.org/forums/topic/change-howdy-manually
	#	http://www.hongkiat.com/blog/wordpress-howdy-customized/
	function tcc_admin_howdy( WP_Admin_Bar $wp_admin_bar ) {
		$user_id = get_current_user_id();
		if ( $user_id ) {
			/* Add the "My Account" menu */
			$current = wp_get_current_user();
			$profile = get_edit_profile_url( $user_id );
			$avatar  = get_avatar( $user_id, 28 );
			$text    = tcc_holiday_greeting();
			$howdy   = sprintf( _x( '%1$s, %2$s', 'text greeting, user name', 'tcc-fluid' ), $text, $current->display_name );
			$class   = ( empty( $avatar ) ) ? '' : 'with-avatar';
			$args    = array('id'     => 'my-account',
			                 'parent' => 'top-secondary',
			                 'title'  => esc_html( $howdy ) . $avatar,
			                 'href'   => $profile,
			                 'meta'   => array( 'class' => $class,
			                                    'title' => esc_html__( 'My Account', 'tcc-fluid' ) ) );
			$wp_admin_bar->add_menu( $args );
		}
	}
	add_action( 'admin_bar_menu', 'tcc_admin_howdy', 11 );
}

if ( ! function_exists( 'tcc_custom_css_admin' ) ) {
	function tcc_custom_css_admin() { ?>
		<style id="tcc-custom-css-admin" type="text/css">
			<?php do_action('tcc_custom_css_admin'); ?>
		</style><?php
	}
	add_action( 'admin_head', 'tcc_custom_css_admin' );
}

if ( ! function_exists( 'fluid_plugin_information_footer' ) ) {
	function fluid_plugin_information_footer() {
		echo "\n#plugin-information-footer {\n\tright: 15px;\n}\n";
	}
	add_action( 'tcc_custom_css_admin', 'fluid_plugin_information_footer' );
}
