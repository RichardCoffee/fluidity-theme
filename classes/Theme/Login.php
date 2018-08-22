<?php


class TCC_Theme_Login {


	protected $login_form  = null;
	protected $login_page  = '';
	protected $redirect_to = null;

	use TCC_Trait_Logging;


	public function __construct() {
		if ( get_theme_mod( 'behavior_login', 'external' ) === 'internal' ) {
			if ( has_page( 'Login' ) ) {
				$this->login_page = home_url( '/login/' );
				add_action( 'fluidity_sidebar_fluid_styling', [ $this, 'fluid_custom_css' ] );
				add_action( 'init',             [ $this, 'prevent_wp_login' ] );
				add_action( 'wp_login_failed',  [ $this, 'wp_login_failed' ] );
				add_filter( 'authenticate',     [ $this, 'authenticate' ], 1, 3 );
			}
			if ( has_page( 'Logout' ) ) {
				add_filter('logout_url', [ $this, 'logout_url' ], 10, 2);
			}
			add_shortcode( 'fluid_login', [ $this, 'login_form_shortcode' ] );
		}
		add_filter( 'login_redirect', [ $this, 'login_redirect' ], 10, 3 );
		add_filter( 'login_redirect', [ $this, 'login_redirect_admin' ], 10, 3 );
		add_filter( 'fluid_customizer_controls_behavior', [ $this, 'fluid_customizer_controls_behavior' ] );
		if ( $this->redirect_to ) { add_filter( 'login_redirect', function( $arg1, $arg2, $arg3 ) { return $this->redirect_to; }, 11, 3 ); }
		if ( is_admin() && ( tcc_settings( 'wplogin', 'external' ) === 'internal' ) ) {
			add_action( 'admin_head',        [ $this, 'dashboard_logo' ] );
			add_filter( 'login_headertitle', [ $this, 'login_headertitle' ] );
			add_filter( 'login_headerurl',   [ $this, 'login_headerurl' ] );
		}
	}

	public function fluid_custom_css() {
		if ( is_page( 'login' ) ) {
			echo "\n.article .login-form input.form-control,\n.article .login-form textarea.form-control {\n\tmax-width: 73%;\n}\n";
		}
	}

	public function login_form_shortcode( $args = array() ) {
		$atts = shortcode_atts( [ 'called_by' => 'shortcode' ], $args );
		$this->login_form( $atts );
	}

	public function login_form( $args = array() ) {
		$login_form = new TCC_Form_Login( $args );
		$login_form->login_form();
	}

	public function navbar_login_form() {
		$login_form = new TCC_Form_Login( [ 'in_navbar' => true ] );
		$login_form->login_form();
	}


/***   Admin Login   ***/

	# http://www.catswhocode.com/blog/10-wordpress-dashboard-hacks
	public function dashboard_logo() {
		$logo_id = get_theme_mod( 'custom_logo' );
		if ( $logo_id ) {
			add_action( 'tcc_custom_css', function() use ( $logo_id ) {
				$size = apply_filters( 'fluid_header_logo_size', 'full' );
				$logo = wp_get_attachment_image_src( $logo_id , $size );
				echo "\n#header-logo {\n\tbackground-image: url( " . esc_url_raw( $logo[0] ) . " ) !important;\n}\n";
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
				$this->log( 'user var is not an object', $user );
			} else if ( get_class( $user ) === 'WP_Error' ) {
				$this->log( 'user error', $user );
			} else {
				$location = ( isset( $_POST['login_location'] ) )
					? esc_url_raw( wp_unslash( $_POST['login_location'] ) )
					: esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) );
				$this->log(
					'   redirect_to:  ' . $redirect_to,
					'       request:  ' . $request,
					'wp_get_referer:  ' . wp_get_referer(),
					'      location:  ' . $location,
					$user  //  FIXME:  php complains when a comma is at the end of this line. wtf?
				);
				wp_safe_redirect( apply_filters( 'login_redirect', $location, $request, $user ) );
				exit;
			}
		} else if ( get_class( $user ) === 'WP_Error' ) {
		} #else { $this->log( func_get_args(), $_GET, $_POST, $_SERVER ); }
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


function prevent_wp_login() {
    // WP tracks the current page - global the variable to access it
    global $pagenow;
    // Check if a $_GET['action'] is set, and if so, load it into $action variable
    $action = ( isset( $_GET['action'] ) ) ? sanitize_title( $_GET['action'] ) : '';
    // Check if we're on the login page, and ensure the action is not 'logout'
    if( $pagenow == 'wp-login.php' && ( ! $action || ( $action && ! in_array($action, array('logout', 'lostpassword', 'rp', 'resetpass'))))) {
        // Load the home page url
        $page = get_bloginfo('url');
        // Redirect to the home page
        wp_redirect($page);
        // Stop execution to prevent the page loading for any reason
        exit();
    }
}


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


/***   Options   ***/

	public function fluid_customizer_controls_behavior( $controls = array() ) {
		$controls['login'] = array(
			'default'     => 'external',
			'label'       => __( 'Login page', 'tcc-fluid' ),
			'description' => __( 'Use a plugin to handle the login page, or whether the internal theme login shortcode should be used', 'tcc-fluid' ),
			'help'        => __( 'I recommend Theme My Login.  Fluidity plays nice with that plugin, also with WP Frontend Profile', 'tcc-fluid' ),
			'render'      => 'radio',
			'choices'     => array(
				'internal' => __( 'Use [fluid-login] shortcode.', 'tcc-fluid' ),
				'external' => __( 'Use a plugin.  (recommended)', 'tcc-fluid' ),
			),
		);
		return $controls;
	}


}
