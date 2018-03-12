<?php
/*
if ( ! function_exists( 'tcc_login_page_redirect' ) ) {
	#	http://www.hongkiat.com/blog/wordpress-custom-loginpage/
	function tcc_login_page_redirect() {
		if ( has_page( 'Login' ) ) {
			$login_page  = home_url( '/login/' );
			$page_viewed = basename( $_SERVER['REQUEST_URI'] );
			if( $page_viewed === "wp-login.php" && $_SERVER['REQUEST_METHOD'] === 'GET') {
				wp_safe_redirect( $login_page );
				exit;
			}
		}
	}
#	add_action('init','tcc_login_page_redirect');
} //*/

if ( ! function_exists( 'tcc_login_failed' ) ) {
	function tcc_login_failed() {
		if ( has_page( 'Login' ) ) {
			$login_page  = home_url( '/login/' );
			wp_safe_redirect( $login_page . '?login=failed' );
			exit;
		}
	}
	add_action( 'wp_login_failed', 'tcc_login_failed' );
}

if ( ! function_exists( 'tcc_authenticate_user' ) ) {
	function tcc_authenticate_user( $user, $username, $password ) {
		if ( has_page( 'Login' ) ) {
			$login_page = home_url( '/login/' );
			if( $username === "" || $password === "" ) {
				wp_safe_redirect( $login_page . "?login=empty" );
				exit;
			}
		}
		return $user;
	}
	add_filter( 'authenticate', 'tcc_authenticate_user', 1, 3);
}

if (!function_exists('tcc_login_header_url')) {
  function tcc_login_header_url() {
    return home_url();
  }
#  add_filter('login_headerurl','tcc_login_header_url' );
}

if (!function_exists('tcc_logout_url')) {
  #  force redirect for logout url
  function tcc_logout_url($url, $redirect) {
    $site = get_option('siteurl');
    $pos  = strpos($url,'?');
    if ($pos===false) {
      $url  .= "?redirect_to=".urlencode($site);
    } else {
      $base  = substr($url,0,$pos);
      parse_str(htmlspecialchars_decode(substr($url,$pos+1)),$parms);
      $parms['redirect_to'] = $site;
      $opts  = http_build_query($parms,'tcc_');
      $url   = $base.'?'.htmlspecialchars($opts);
    }
    return $url;
  }
#  add_filter('logout_url', 'tcc_logout_url', 10, 2);
}

if (!function_exists('tcc_admin_howdy')) {
	#	http://www.wpbeginner.com/wp-tutorials/how-to-change-the-howdy-text-in-wordpress-3-3-admin-bar/
	#	https://premium.wpmudev.org/forums/topic/change-howdy-manually
	#	http://www.hongkiat.com/blog/wordpress-howdy-customized/
	#	Note:  I saw some posts that recommend changing this via gettext.  I would regard that as bad practice.
	function tcc_admin_howdy( WP_Admin_Bar $wp_admin_bar ) {
		$user_id = get_current_user_id();
		if ( $user_id ) {
			/* Add the "My Account" menu */
			$current = wp_get_current_user();
			$profile = get_edit_profile_url( $user_id );
			$avatar  = get_avatar( $user_id, 28 );
			$text    = tcc_holiday_greeting();
			$howdy   = sprintf( _x('%1$s, %2$s','text greeting, user name','tcc-fluid'), $text, $current->display_name );
			$class   = (empty($avatar)) ? '' : 'with-avatar';
			$args    = array('id'     => 'my-account',
			                 'parent' => 'top-secondary',
			                 'title'  => esc_html($howdy) . $avatar,
			                 'href'   => $profile,
			                 'meta'   => array( 'class' => $class,
			                                    'title' => esc_html__('My Account','tcc-fluid') ) );
			$wp_admin_bar->add_menu( $args );
		}
	}
	add_action('admin_bar_menu', 'tcc_admin_howdy', 11 );
}
