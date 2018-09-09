<?php


class TCC_Form_Login_Login {

	public    $defaults    = array();
	protected $show_mode   = 'modal';
	protected $redirect_to = null;

	use TCC_Trait_Attributes;
	use TCC_Trait_Logging;
	use TCC_Trait_ParseArgs;

	public function __construct( $args = array() ) {
		$this->parse_args( $args );
		$this->redirect_to = ( empty( $this->redirect_to ) ) ? home_url( add_query_arg( NULL, NULL ) ) : $this->redirect_to;
		add_action( 'login_form_defaults', [ $this, 'login_form_defaults' ], 1 );	#	run early - make it easy to override values
		#	Do not show login errors to users
		if ( ! WP_DEBUG ) { add_filter( 'login_errors', function( $arg ) { return null; } ); }
		$this->get_login_form_defaults();
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
		$this->defaults = $defaults;
		return;
	}

	public function login_form_defaults( $defaults = array() ) {
		# array mainly taken from wp-includes/general-template.php
		$new = array(
			'redirect'       => apply_filters( 'login_redirect',       $this->redirect_to, null, null ),
			'form_id'        => apply_filters( 'fluid_login_form_id',  uniqid( 'login_form_' ) ),
			'label_username' => apply_filters( 'fluid_login_username', __( 'Username or Email Address', 'tcc-fluid' ) ),
			'label_password' => apply_filters( 'fluid_login_password', __( 'Password',      'tcc-fluid' ) ),
#			'label_remember' => __( 'Remember Me', 'tcc-fluid' ),
			'label_log_in'   => apply_filters( 'fluid_login_text',     __( 'Sign In',       'tcc-fluid' ) ),
			'label_lostpw'   => apply_filters( 'fluid_lostpw_text',    __( 'Lost Password', 'tcc-fluid' ) ),
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

	public function login_form() {
		if ( is_user_logged_in() ) {
			$this->show_logout_form();
		} else {
			$this->show_login_form();
		}
	}

	public function show_login_form() {
		$attrs = array(
			'id'     => $this->defaults['form_id'],
			'class'  => 'login-form',
			'name'   => 'loginform',
			'action' => site_url( '/wp-login.php' ),
			'method' => 'post',
		); ?>
		<form <?php $this->apply_attrs( $attrs ); ?>>
			<div class='form-group login-username'><?php
				$this->element( 'label', [ 'class' => 'login-text', 'for' => $this->defaults['id_username'] ], $this->defaults['label_username'] );
				$this->element( 'input', $this->get_username_attrs() ); ?>
			</div>
			<div class='form-group login-password'><?php
				$this->element( 'label', [ 'class' => 'login-text', 'for' => $this->defaults['id_password'] ], $this->defaults['label_password'] );
				$this->element( 'input', $this->get_password_attrs() ); ?>
			</div>
			<div class="checkbox login-remember"><?php
				$this->remember_checkbox(); ?>
			</div>
			<div class="form-group login-submit pull-left"><?php
				$this->submit_button(); ?>
			</div><?php
			$this->lost_password(); ?>
		</form><?php
	}

	protected function get_username_attrs() {
		$attrs = array(
			'type'  => 'text',
			'name'  => 'log',
			'id'    => $this->defaults['id_username'],
			'class' => 'form-control',
			'placeholder' => $this->defaults['label_username'],
			'required'    => '',
		);
		return $attrs;
	}

	protected function get_password_attrs() {
		$attrs = array(
			'type'  => 'password',
			'name'  => 'pwd',
			'id'    => $this->defaults['id_password'],
			'class' => 'form-control',
			'placeholder' => $this->defaults['label_password'],
			'required'    => '',
		);
		return $attrs;
	}

	protected function remember_checkbox() {
		$attrs = array(
			'type'  => 'checkbox',
			'id'    => $this->defaults['id_remember'],
			'name'  => 'rememberme',
			'value' => 'forever',
		);
		$attr = $this->checked( $attrs, $this->defaults['value_remember'], true ); ?>
		<label>&nbsp;<?php
			$this->element( 'input', $attr, $this->defaults['label_remember'] ); ?>
		</label><?php
	}

	protected function submit_button() {
		$attrs = $this->get_submit_button_attrs();
		$this->tag( 'button', $attrs );
			fluid()->fawe( 'fa-sign-in' ); ?>&nbsp;<?php
			echo esc_html( $this->defaults['label_log_in'] ); ?>
		</button><?php
		if ( $attrs['type'] === 'submit' ) {
			$input = array(
				'type'  => 'hidden',
				'name'  => 'redirect_to',
				'value' => esc_url( $this->defaults['redirect'] ), // esc_html() also gets applied to this field
			);
			$this->element( 'input', $input );
		}
	}

	protected function get_submit_button_attrs() {
		$attrs = array(
			'type'  => 'submit',
			'id'    => $this->defaults['id_submit'],
			'class' => 'btn btn-fluidity',
			'name'  => 'wp-submit',
		);
		return $attrs; //apply_filters( 'fluid_login_submit_button_attrs', $attrs, $this );
	}

	protected function lost_password() {
		if ( ! empty( $this->defaults['label_lostpw'] ) ) {
			$lost_url = wp_lostpassword_url( home_url() );
			if ( ! empty( $lost_url ) ) {
				$attrs = array(
					'class' => 'pull-right',
					'href'  => $lost_url,
					'title' => __( 'You can request a new password via this link.', 'tcc-fluid' ),
					'rel'   => 'nofollow',
				);
				$attrs = apply_filters( 'fluid_login_lost_password', $attrs );
				$this->tag( 'a', $attrs );
					$this->element( 'small', [ ], $this->defaults['label_lostpw'] ); ?>
				</a><?php
			}
		}
	}

	public function show_logout_form() {
		$signout = apply_filters( 'fluid_logout_text', __( 'Sign Out', 'tcc-fluid' ) );
/* ?>
		<form class="<?php #echo $formclass; ?>" action="<?php #echo wp_logout_url( home_url() ); ?>" method="post">
*/ ?>
			<div class="text-center"><?php
				$attrs = array(
					'type'  => 'button',
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
<?php /*
		</form><?php */
	}


}
