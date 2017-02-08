<?php

if ( ! function_exists( 'tcc_login_form' ) ) {
	function tcc_login_form( $args = array() ) {
		TCC_Form_Login::instance()->login_form( $args );
	}
}

class TCC_Form_Login {

	protected $in_navbar   = false;
	protected $pull_right  = true;
	protected $redirect_to = null;

	use TCC_Trait_ParseArgs;
	use TCC_Trait_Singleton;

	protected function __construct( $args = array() ) {
		$this->parse_args( $args );

		add_action( 'admin_head',          array( $this, 'dashboard_logo' ) );
		add_action( 'login_form_defaults', array( $this, 'login_form_defaults' ) );

		add_filter( 'login_headertitle',   function( $args ) { return ''; } );
		add_filter( 'login_redirect',      array( $this, 'login_redirect'), 10, 3);
		add_filter( 'tcc_login_redirect',  array( $this, 'login_redirect_admin' ), 10, 3 );
		if ( $this->redirect_to ) { add_filter( 'tcc_login_redirect', function( $arg ) { return $this->redirect_to; }, 11, 3 ); }

		#	Do not show login errors to users
		if ( ! WP_DEBUG) { add_filter( 'login_errors', function( $arg ) { return null; } ); }

	}

	#	http://www.catswhocode.com/blog/10-wordpress-dashboard-hacks
	public function dashboard_logo() {
		$logo = tcc_design('logo');
		if ($logo) { ?>
			<style type='text/css'>
				#header-logo { background-image: url(<?php echo $logo; ?>) !important; }
			</style><?php
		}
	}

	public function get_login_form_defaults() {
		$defaults = array();
		add_filter('login_form_defaults', function($args) use (&$defaults) {
			$defaults = $args;
			return $args;
		}, 9999 );
		$form = wp_login_form( array( 'echo' => false ) );
		return $defaults;
	}

	public function login_form() {
		if ( is_user_logged_in() ) {
			$this->show_logout_form();
		} else {
			$this->show_login_form();
		}
	}

	public function login_form_defaults( $defaults = array() ) {
#	array mainly taken from wp-includes/general-template.php
		$new = array( 'redirect'       => apply_filters( 'tcc_login_redirect_to', home_url( add_query_arg( NULL, NULL ) ) ),
		              'form_id'        => apply_filters( 'tcc_login_form_id',     uniqid( 'login_form_' ) ),
		              'label_username' => apply_filters( 'tcc_login_username',    __( 'Username or Email Address', 'tcc-fluid' ) ),
		              'label_password' => apply_filters( 'tcc_login_password',    __( 'Password',      'tcc-fluid' ) ),
#		              'label_remember' => __( 'Remember Me', 'tcc-fluid' ),
		              'label_log_in'   => apply_filters( 'tcc_log_in_text',       __( 'Sign In',       'tcc-fluid' ) ),
		              'label_lostpw'   => apply_filters( 'tcc_lostpw_text',       __( 'Lost Password', 'tcc-fluid' ) ),
		              'id_username'    => uniqid( 'user_login_' ),
		              'id_password'    => uniqid( 'user_pass_'  ),
		              'id_remember'    => uniqid( 'rememberme_' ),
		              'id_submit'      => uniqid( 'wp-submit_'  ),
#		              'remember'       => true,
#		              'value_username' => '',
#		              'value_remember' => false,
		            );
		return array_merge( $defaults, $new );
	}

	#	https://www.longren.io/wordpress-tip-redirect-to-previous-page-after-login/
	function login_redirect( $redirect_to, $request, $user ) {
		if ( (isset($_GET['action']) && $_GET['action'] != 'logout') || (isset($_POST['login_location']) && !empty($_POST['login_location'])) ) {
			if (!$user)                       { return home_url(); }
			if (!is_object($user))            { log_entry( 'user var is not an object', $user );  return $redirect_to; }
			if (get_class($user)=='WP_Error') { return $redirect_to; }
			$location = (isset($_POST['login_location'])) ? esc_url_raw($_POST['login_location']) : esc_url_raw($_SERVER['HTTP_REFERER']);
			log_entry(
				'   redirect_to:  ' . $redirect_to,
				'       request:  ' . $request,
				'wp_get_referer:  ' . wp_get_referer(),
				'      location:  ' . $location,
				$user
			);
			wp_safe_redirect( apply_filters( 'tcc_login_redirect', $location, $request, $user ) );
			exit;
		}
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

	protected function show_login_form() {
		$defaults = $this->get_login_form_defaults();
		extract( $defaults );	#	See $this->login_form_defaults for the list of extracted variables
		$remember = ( $this->in_navbar ) ?  false : $remember;
		$form_css = ( $this->in_navbar ) ? 'navbar-form navbar-login-form' . ( ( $this->pull_right ) ? ' navbar-right' : '' ) : 'login-form';
		$text_css = ( $this->in_navbar ) ? 'sr-only' : 'login-text';
		$attrs = array(
			'id'     => $form_id,
			'class'  => $form_css,
			'name'   => 'loginform',
			'action' => site_url( '/wp-login.php' ),
			'method' => 'post',
		); ?>
		<form <?php apply_attrs( $attrs ); ?>>

			<div class='form-group login-username'>
				<label class="<?php echo $text_css; ?>" for="<?php echo esc_attr($id_username); ?>">
					<?php echo esc_html($label_username); ?>
				</label>
				<input type="text" name="log" id="<?php echo esc_attr($id_username); ?>" class="form-control"
					placeholder="<?php echo esc_html($label_username); ?>" required>
			</div>

			<div class='form-group login-password'>
				<label class="<?php echo $text_css; ?>" for="<?php echo esc_attr($id_password); ?>">
					<?php echo esc_html($label_password); ?>
				</label>
				<input type="password" name="pwd" id="<?php echo esc_attr($id_password); ?>"
					class="form-control" placeholder="<?php echo esc_attr($label_password); ?>" required>
			</div>

			<?php if ($remember) { ?>
				<div class="checkbox login-remember">
					<label>
						<input type="checkbox" id="<?php echo esc_attr($id_remember); ?>"
							name="rememberme" value="forever" <?php checked($value_remember,true); ?>>&nbsp;
						<?php echo esc_html($label_remember); ?>
					</label>
				</div>
			<?php  } ?>

			<div class="form-group login-submit">
				<button type="submit" id="<?php echo esc_attr($id_submit); ?>" class="btn btn-fluidity" name="wp-submit"><i class="fa fa-sign-in"></i>&nbsp;
					<?php echo esc_html($label_log_in); ?>
				</button>
				<input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect); ?>" />
			</div>

			<?php if ( !empty( $label_lost ) && ( $lost_url = wp_lostpassword_url( home_url() ) ) ) {
				$tooltip = __( 'You can request a new password via this link.', 'tcc-fluid' ); ?>
				<a class="lost-password pull-right" href="<?php echo esc_url( $lost_url ); ?>" title="<?php echo esc_attr( $tooltip ); ?>" rel="nofollow">
					<small>
						<?php echo esc_html($label_lost); ?>
					</small>
				</a>
			<?php } ?>

		</form><?php
	}

	protected function show_logout_form() {
		$signout = apply_filters( 'tcc_signout_text', __( 'Sign Out', 'tcc-fluid' ) ); ?>
		<form class="<?php #echo $formclass; ?>" action="<?php #echo wp_logout_url( home_url() ); ?>" method="post">
			<div class="text-center"><?php
				$attrs = array(
					'class' => 'btn btn-fluidity',
					'href'  =>  esc_url( wp_logout_url( home_url() ) ),
					'title' =>  $signout,
					'rel'   => 'nofollow',
				); ?>
				<a <?php apply_attrs( $attrs ); ?>>&nbsp;
					<?php echo esc_html( $signout ); ?>
					&nbsp;&nbsp;<i class='fa fa-sign-out'></i>
				</a>
			</div>
		</form><?php
	}


}
