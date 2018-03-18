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
		add_action( 'login_form_defaults', array( $this, 'login_form_defaults' ), 1 );	#	run early - make it easy to override values
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
		# filter gets called here - was unable to find a better way to get the form values
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
		$remember = ( $in_navbar ) ?  false : $remember;
		$form_css = ( $in_navbar ) ? 'navbar-form navbar-login-form' . ( ( $pull_right ) ? ' navbar-right' : '' ) : 'login-form';
		$text_css = ( $in_navbar ) ? 'sr-only' : 'login-text';
		$attrs = array(
			'id'     => $form_id,
			'class'  => $form_css,
			'name'   => 'loginform',
			'action' => site_url( '/wp-login.php' ),
			'method' => 'post',
		); ?>
		<form <?php $this->apply_attrs( $attrs ); ?>>

			<div class='form-group login-username'>
				<label class="<?php echo $text_css; ?>" for="<?php echo esc_attr( $id_username ); ?>">
					<?php echo esc_html( $label_username ); ?>
				</label>
				<input type="text" name="log" id="<?php echo esc_attr( $id_username ); ?>" class="form-control"
					placeholder="<?php echo esc_html( $label_username ); ?>" required>
			</div>

			<div class='form-group login-password'>
				<label class="<?php echo $text_css; ?>" for="<?php echo esc_attr( $id_password ); ?>">
					<?php echo esc_html( $label_password ); ?>
				</label>
				<input type="password" name="pwd" id="<?php echo esc_attr( $id_password ); ?>"
					class="form-control" placeholder="<?php echo esc_attr( $label_password ); ?>" required>
			</div>

			<?php if ( $remember ) { ?>
				<div class="checkbox login-remember">
					<label>
						<input type="checkbox" id="<?php echo esc_attr( $id_remember ); ?>"
							name="rememberme" value="forever" <?php checked( $value_remember, true ); ?>>&nbsp;
						<?php echo esc_html( $label_remember ); ?>
					</label>
				</div>
			<?php  } ?>

			<div class="form-group login-submit">
				<button type="submit" id="<?php echo esc_attr( $id_submit ); ?>" class="btn btn-fluidity" name="wp-submit">
					<?php fluid_library()->fawe( 'fa-sign-in' ); ?>&nbsp;
					<?php echo esc_html( $label_log_in ); ?>
				</button>
				<input type="hidden" name="redirect_to" value="<?php echo esc_url( $redirect ); ?>" />
			</div>

			<?php if ( ! empty( $label_lostpw ) && ( $lost_url = wp_lostpassword_url( home_url() ) ) ) {
				$tooltip = __( 'You can request a new password via this link.', 'tcc-fluid' ); ?>
				<a class="lost-password pull-right" href="<?php echo esc_url( $lost_url ); ?>" title="<?php echo esc_attr( $tooltip ); ?>" rel="nofollow">
					<small>
						<?php echo esc_html( $label_lostpw ); ?>
					</small>
				</a>
			<?php } ?>

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
