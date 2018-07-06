<?php
/**
 *  includes/admin.php
 *
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/includes/admin.php
 */

/**
 *  Change text greeting in admin bar - replaces wordpress' wp_admin_bar_my_account_item function
 *
 * @link wp-includes/admin-bar.php:function wp_admin_bar_my_account_item( $wp_admin_bar )
 * @link http://www.wpbeginner.com/wp-tutorials/how-to-change-the-howdy-text-in-wordpress-3-3-admin-bar/
 * @link https://premium.wpmudev.org/forums/topic/change-howdy-manually
 * @link http://www.hongkiat.com/blog/wordpress-howdy-customized/
 * @param WP_Admin_Bar $wp_admin_bar
 */
if ( ! function_exists( 'fluid_admin_howdy' ) ) {
	function fluid_admin_howdy( WP_Admin_Bar $wp_admin_bar ) {
		remove_action( 'admin_bar_menu', 'wp_admin_bar_my_account_item', 7 );
		if ( ! ( $user_id = get_current_user_id() ) ) {
			return;
		}
		$current = wp_get_current_user();
		$avatar  = get_avatar( $user_id, 28 );
		$greet   = apply_filters( 'wp_admin_bar_my_account_greeting', tcc_holiday_greeting(), $current );
		$name    = '<span class="display-name">' . $current->display_name . '</span>';
#		$name    = fluid()->get_element( 'span', [ 'class' => 'display-name' ], $current->display_name );
		$howdy   = sprintf( _x( '%1$s, %2$s', 'text greeting, user name', 'tcc-fluid' ), $greet, $name );
		$args    = array(
			'id'     => 'my-account',
			'parent' => 'top-secondary',
			'title'  => $howdy . $avatar,
			'href'   => apply_filters( 'wp_admin_bar_my_account_profile_url', false, $current ),
			'meta'   => array(
				'class' => ( empty( $avatar ) ) ? '' : 'with-avatar',
				'title' => esc_html__( 'My Account', 'tcc-fluid' )
			)
		);
		$wp_admin_bar->add_menu( $args );
	}
	add_action( 'admin_bar_menu', 'fluid_admin_howdy', 6 );
}

/**
 *  filter the profile url for the admin bar
 *
 * @link wp-includes/admin-bar.php:function wp_admin_bar_my_account_item( $wp_admin_bar )
 * @param string $profile_url
 * @param WP_User $user
 * @return string
 */
if ( ! function_exists( 'fluid_wp_admin_bar_my_account_profile_url' ) ) {
	function fluid_wp_admin_bar_my_account_profile_url( $profile_url, WP_User $user ) {
		if ( current_user_can( 'read' ) ) {
			$profile_url = get_edit_profile_url( $user->id );
		} elseif ( is_multisite() ) {
			$profile_url = get_dashboard_url( $user->id, 'profile.php' );
		}
		return $profile_url;
	}
	add_filter( 'wp_admin_bar_my_account_profile_url', 'fluid_wp_admin_bar_my_account_profile_url', 5, 2 );
}

/**
 *  provide action to include custom css when loading admin page
 *
 */
if ( ! function_exists( 'fluid_custom_css_admin' ) ) {
	function fluid_custom_css_admin() { ?>
		<style id="fluid-custom-css-admin" type="text/css"><?php
			do_action('fluid_custom_css_admin'); ?>
		</style><?php
	}
	add_action( 'admin_head', 'fluid_custom_css_admin' );
}

/**
 *  custom css for the plugin information screen
 *
 */
if ( ! function_exists( 'fluid_plugin_information_footer' ) ) {
	function fluid_plugin_information_footer() {
		echo "\n#plugin-information-footer {\n\tleft: auto;\n\tright: 15px;\n\twidth: 217px;\n}\n";
	}
	add_action( 'fluid_custom_css_admin', 'fluid_plugin_information_footer' );
}
