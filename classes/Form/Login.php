<?php


class TCC_Form_Login {

	protected $show_mode   = 'modal';
	protected $in_modal    = false;
	protected $in_navbar   = false;
	protected $in_widget   = false;
	protected $pull_right  = true;
	protected $redirect_to = null;

	use TCC_Trait_Attributes;
	use TCC_Trait_Logging;
	use TCC_Trait_ParseArgs;

	public function __construct( $args = array() ) {
		$this->parse_args( $args );
		add_action( 'login_form_defaults', [ $this, 'login_form_defaults' ], 1 );	#	run early - make it easy to override values
		#	Do not show login errors to users
		if ( ! WP_DEBUG) { add_filter( 'login_errors', function( $arg ) { return null; } ); }
	}


/***   Form Defaults   ***/

	public function get_login_form_defaults() {
		$defaults = array();
		add_filter( 'login_form_defaults', function( $args ) use ( &$defaults ) {
			$defaults = $args;
			return $args;
		}, 9999 );
		# filter gets called here - should be a better way to get the form values
		$form = wp_login_form( array( 'echo' => false ) );
		return $defaults;
	}

	public function login_form_defaults( $defaults = array() ) {
		# array mainly taken from wp-includes/general-template.php
		$new = array(
			'redirect'       => apply_filters( 'tcc_login_redirect_to', home_url( add_query_arg( NULL, NULL ) ) ),
			'form_id'        => apply_filters( 'tcc_login_form_id',     uniqid( 'login_form_' ) ),
			'label_username' => apply_filters( 'tcc_login_username',    __( 'Username or Email Address', 'tcc-fluid' ) ),
			'label_password' => apply_filters( 'tcc_login_password',    __( 'Password',      'tcc-fluid' ) ),
#			'label_remember' => __( 'Remember Me', 'tcc-fluid' ),
			'label_log_in'   => apply_filters( 'tcc_log_in_text',       __( 'Sign In',       'tcc-fluid' ) ),
			'label_lostpw'   => apply_filters( 'tcc_lostpw_text',       __( 'Lost Password', 'tcc-fluid' ) ),
			'id_username'    => uniqid( 'user_login_' ),
			'id_password'    => uniqid( 'user_pass_'  ),
			'id_remember'    => uniqid( 'rememberme_' ),
			'id_submit'      => uniqid( 'wp-submit_'  ),
#			'remember'       => true,
#			'value_username' => '',
#			'value_remember' => false,
		);
		return array_merge( $defaults, $new );
	}


/***   Login/Logout Forms   ***/

	public function login_form( $args = array() ) {
		if ( is_user_logged_in() ) {
			$this->show_logout_form();
		} else {
			$this->show_login_form( $args );
		}
	}

	public function show_login_form( $args ) {
		$in_navbar  = $this->in_navbar;
		$pull_right = $this->pull_right;
		extract( $args, EXTR_IF_EXISTS );
		$defaults = $this->get_login_form_defaults();
		extract( $defaults );	#	See $this->login_form_defaults for the list of extracted variables
		$text_css = ( $in_navbar ) ? 'sr-only' : 'login-text';
		$remember = ( $in_navbar ) ?  false : $remember;
		$fattrs = array(
			'id'     => $form_id,
			'class'  => ( $in_navbar ) ? 'navbar-form navbar-login-form' . ( ( $pull_right ) ? ' navbar-right' : '' ) : 'login-form',
			'name'   => 'loginform',
			'action' => site_url( '/wp-login.php' ),
			'method' => 'post',
		);
		$unattrs = array(
			'type'  => 'text',
			'name'  => 'log',
			'id'    => $id_username,
			'class' => 'form-control',
			'placeholder' => $label_username,
			'required'    => '',
		);
		$pwattrs = array(
			'type' => 'password',
			'name' => 'pwd',
			'id'   => $id_password,
			'class' => 'form-control',
			'placeholder' => $label_password,
			'required' => '',
		); ?>

		<form <?php $this->apply_attrs( $fattrs ); ?>>

			<div class='form-group login-username'><?php
				$this->element( 'label', [ 'class' => $text_css, 'for' => $id_username ], $label_username );
				$this->element( 'input', $unattrs ); ?>
			</div>

			<div class='form-group login-password'><?php
				$this->element( 'label', [ 'class' => $text_css, 'for' => $id_password ], $label_password );
				$this->element( 'input', $pwattrs ); ?>
			</div><?php

			if ( $remember ) { ?>
				<div class="checkbox login-remember">
					<label>
						<input type="checkbox" id="<?php echo esc_attr( $id_remember ); ?>"
							name="rememberme" value="forever" <?php checked( $value_remember, true ); ?>>&nbsp;
						<?php echo esc_html( $label_remember ); ?>
					</label>
				</div><?php
			} ?>

			<div class="form-group login-submit pull-left">
				<button type="submit" id="<?php echo esc_attr( $id_submit ); ?>" class="btn btn-fluidity" name="wp-submit">
					<?php fluid()->fawe( 'fa-sign-in' ); ?>&nbsp;
					<?php echo esc_html( $label_log_in ); ?>
				</button>
				<input type="hidden" name="redirect_to" value="<?php echo esc_url( $redirect ); ?>" />
			</div><?php

			if ( ! empty( $label_lostpw ) && ( $lost_url = wp_lostpassword_url( home_url() ) ) ) {
				$lpattrs = array(
					'class' => 'pull-right',
					'href'  => $lost_url,
					'title' => __( 'You can request a new password via this link.', 'tcc-fluid' ),
					'rel'   => 'nofollow',
				);
				$this->tag( 'a', $lpattrs ); ?>
					<small>
						<?php echo esc_html( $label_lostpw ); ?>
					</small>
				</a><?php
			} ?>

		</form><?php
	}

	public function show_logout_form() {
		$signout = apply_filters( 'tcc_logout_text', __( 'Sign Out', 'tcc-fluid' ) ); ?>
		<form class="<?php #echo $formclass; ?>" action="<?php #echo wp_logout_url( home_url() ); ?>" method="post">
			<div class="text-center"><?php
				$attrs = array(
					'class' => 'btn btn-fluidity',
					'href'  =>  esc_url( wp_logout_url( home_url() ) ),
					'title' =>  $signout,
					'rel'   => 'nofollow',
				); ?>
				<a <?php $this->apply_attrs( $attrs ); ?>>&nbsp;
					<?php echo esc_html( $signout ); ?>
					&nbsp;&nbsp;<i class='fa fa-sign-out'></i>
				</a>
			</div>
		</form><?php
	}


/***   Redirects   ***/

/*
$redirect = home_url( add_query_arg( '_', false ) ); ?>
Alternately:  global $wp; home_url(add_query_arg(array(),$wp->request)); ?>
Or:           home_url( add_query_arg( NULL, NULL ) ); ?>
Or:           global $wp; $location = add_query_arg( $_SERVER['QUERY_STRING'], '', home_url( $wp->request ) ); ?>
Multi-site:   $parts = parse_url( home_url() ); $current_uri = "{$parts['scheme']}://{$parts['host']}" . add_query_arg( NULL, NULL ); ?> */

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
				wp_safe_redirect( apply_filters( 'tcc_login_redirect', $location, $request, $user ) );
				exit;
			}
		}
else { $this->log( func_get_args(), $_GET, $_POST ); }
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


}
