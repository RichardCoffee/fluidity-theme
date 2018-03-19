<?php


class TCC_Theme_Login {


	protected $login_form  = null;
	protected $login_page  = '';
	protected $redirect_to = null;

	use TCC_Trait_Logging;


	public function __construct() {
		if ( tcc_settings( 'login' ) === 'internal' ) {
			if ( has_page( 'Login' ) ) {
				$this->login_page = home_url( '/login/' );
				add_action( 'wp_login_failed',  array( $this, 'wp_login_failed' ) );
				add_filter( 'authenticate',     array( $this, 'authenticate' ), 1, 3 );
			}
			if ( has_page( 'Logout' ) ) {
				add_filter('logout_url', array( $this, 'logout_url' ), 10, 2);
			}
		}
		add_filter( 'login_redirect',     array( $this, 'login_redirect' ), 10, 3 );
		add_filter( 'tcc_login_redirect', array( $this, 'login_redirect_admin' ), 10, 3 );
		if ( $this->redirect_to ) { add_filter( 'tcc_login_redirect', function( $arg ) { return $this->redirect_to; }, 11, 3 ); }
		if ( is_admin() && ( tcc_settings( 'wplogin' ) === 'internal' ) ) {
			add_action( 'admin_head',        array( $this, 'dashboard_logo' ) );
			add_filter( 'login_headertitle', array( $this, 'login_headertitle' ) );
			add_filter( 'login_headerurl',   array( $this, 'login_headerurl' ) );
		}
	}

	public function login_form( $args = array() ) {
		$login_form = new TCC_Form_Login( $args );
		$login_form->login_form();
	}

/***   Admin Login   ***/

	# http://www.catswhocode.com/blog/10-wordpress-dashboard-hacks
	public function dashboard_logo() {
		$logo = tcc_design( 'logo' );
		if ( $logo ) {
			add_action( 'tcc_custom_css', function() use ($logo) {
				echo "\n#header-logo {\n\tbackground-image: url( $logo ) !important;\n}\n";
			});
		}
	}

	public function login_headertitle( $args ) {
		return '';
	}

	public function login_headerurl() {
		return home_url();
	}


/***   Redirects   ***/

/*
 *
$redirect = home_url( add_query_arg( '_', false ) ); ?>
Alternately:  global $wp; home_url(add_query_arg(array(),$wp->request)); ?>
Or:           home_url( add_query_arg( NULL, NULL ) ); ?>
Or:           global $wp; $location = add_query_arg( $_SERVER['QUERY_STRING'], '', home_url( $wp->request ) ); ?>
Multi-site:   $parts = parse_url( home_url() ); $current_uri = "{$parts['scheme']}://{$parts['host']}" . add_query_arg( NULL, NULL ); ?>
 *
 */

	# https://www.longren.io/wordpress-tip-redirect-to-previous-page-after-login/
	public function login_redirect( $redirect_to, $request, $user ) {
		if ( ( isset( $_GET['action'] ) && $_GET['action'] !== 'logout') || ( isset( $_POST['login_location'] ) && ! empty( $_POST['login_location'] ) ) ) {
			if ( ! $user ) {
				$redirect_to = home_url();
			} else if ( ! is_object( $user ) ) {
				log_entry( 'user var is not an object', $user );
			} else if ( get_class( $user ) === 'WP_Error' ) {
				log_entry( 'user error', $user );
			} else {
				$location = ( isset( $_POST['login_location'] ) )
					? esc_url_raw( $_POST['login_location'] )
					: esc_url_raw( $_SERVER['HTTP_REFERER'] );
				log_entry(
					'   redirect_to:  ' . $redirect_to,
					'       request:  ' . $request,
					'wp_get_referer:  ' . wp_get_referer(),
					'      location:  ' . $location,
					$user  //  FIXME:  php complains when a comma is at the end of this line. wtf?
				);
				wp_safe_redirect( apply_filters( 'tcc_login_redirect', $location, $request, $user ) );
				exit;
			}
		} else { $this->log( func_get_args(), $_GET, $_POST ); }
		return $redirect_to;
	}

	public function login_redirect_admin( $redirect_to, $request, $user ) {
#		$from = wp_get_referer();
##		if ( strpos( $from, 'wp-admin' ) !== false )       { return $from; }
##		if ( ! in_array( 'administrator', $user->roles ) ) { return $redirect_to }
#		$user_id = get_current_user_id();
#		if ( $user_id === 1 ) { return $from; }
#		return home_url();
		return $redirect_to;
	}


/***   Login Issues   ***/

	public function authenticate( $user, $username, $password ) {
		if( $username === "" || $password === "" ) {
			wp_safe_redirect( $this->login_page . "?login=empty" );
			exit;
		}
		return $user;
	}

	public function wp_login_failed() {
		wp_safe_redirect( $this->login_page . '?login=failed' );
		exit;
	}


/***   Logout   ***/

	public function logout_url( $url, $redirect ) {
		$site = get_option( 'siteurl' );
		$pos  = strpos( $url, '?' );
		if ( $pos === false ) {
			$url.= "?redirect_to=" . urlencode( $site );
		} else {
			$base  = substr( $url, 0, $pos );
			parse_str( htmlspecialchars_decode( substr( $url, $pos + 1 ) ), $parms );
			$parms['redirect_to'] = $site;
			$opts  = http_build_query( $parms, 'tcc_' );
			$url   = $base . '?' . htmlspecialchars( $opts );
		}
		return $url;
	}


}
