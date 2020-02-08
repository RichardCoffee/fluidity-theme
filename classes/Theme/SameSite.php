<?php
/**
 * @since 20200208
 */
class TCC_Theme_SameSite {


	private $auth_cookie;
	private $expire;
	private $logged_in_cookie;
	private $secure;
	private $secure_logged_in_cookie;
	private $samesite = 'Strict'; // 'Lax' or 'Strict'


	public function __construct() {
		if ( version_compare( PHP_VERSION, '7.3.0' ) >= 0 ) {
			add_filter( 'secure_logged_in_cookie', [ $this, 'secure_logged_in_cookie' ], 10, 3 );
			add_action( 'set_auth_cookie',         [ $this, 'set_auth_cookie' ],         10, 6 );
			add_action( 'set_logged_in_cookie',    [ $this, 'set_logged_in_cookie' ],    10, 6 );
			add_filter( 'send_auth_cookies',       [ $this, 'send_auth_cookies' ] );
		}
	}

	public function secure_logged_in_cookie( $secure_logged_in_cookie, $user_id, $secure ) {
		$this->secure_logged_in_cookie = $secure_logged_in_cookie;
		$this->secure = $secure;
		return $secure_logged_in_cookie;
	}

	public function set_auth_cookie( $auth_cookie, $expire, $expiration, $user_id, $scheme, $token ) {
		$this->auth_cookie = $auth_cookie;
		$this->expire = $expire;
		return $auth_cookie;
	}

	public function set_logged_in_cookie( $logged_in_cookie, $expire, $expiration, $user_id, $status, $token ) {
		$this->logged_in_cookie = $logged_in_cookie;
		return $logged_in_cookie;
	}

	public function send_auth_cookies( $send ) {
		$this->send_cookies();
		return false;
	}

	protected function send_cookies() {
		$auth_cookie_name = ( $this->secure ) ? SECURE_AUTH_COOKIE : AUTH_COOKIE;
		$data = array(
			'expires'  => $this->expire,
			'path'     => PLUGINS_COOKIE_PATH,
			'domain'   => COOKIE_DOMAIN,
			'secure'   => $this->secure,
			'httponly' => true,
			'samesite' => $this->samesite,
		);
		setcookie( $auth_cookie_name, $this->auth_cookie, $data );
		$data['path'] = ADMIN_COOKIE_PATH;
		setcookie( $auth_cookie_name, $this->auth_cookie, $data );
		$data['path']   = COOKIEPATH;
		$data['secure'] = $this->secure_logged_in_cookie;
		setcookie( LOGGED_IN_COOKIE, $this->logged_in_cookie, $data );
		if ( COOKIEPATH != SITECOOKIEPATH ) {
			$data['path'] = SITECOOKIEPATH;
			setcookie( LOGGED_IN_COOKIE, $this->logged_in_cookie, $data );
		}
	}


}
