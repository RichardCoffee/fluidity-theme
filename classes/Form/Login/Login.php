<?php
/**
 *  handles showing the login form
 *
 * @package Fluidity
 * @subpackage Login
 * @since 20170201
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2017, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/Form/Login/Login.php
 */
defined( 'ABSPATH' ) || exit;
/**
 *  handles login forms
 *
 * @since 20170201
 */
class TCC_Form_Login_Login {

	/**
	 *  login form defaults
	 *
	 * @since 20170201
	 * @var array
	 */
	public $defaults = array();
	/**
	 *  redirect url
	 *
	 * @since 20170211
	 * @var string
	 */
	protected $redirect_to = null;

	use TCC_Trait_Attributes; // * @since 20170507
	use TCC_Trait_Logging;
	use TCC_Trait_ParseArgs;

	/**
	 *  class constructor
	 *
	 * @since 20170201
	 * @param array $args
	 */
	public function __construct( $args = array() ) {
		$this->parse_args( $args );
		$this->redirect_to = ( empty( $this->redirect_to ) ) ? home_url( add_query_arg( NULL, NULL ) ) : $this->redirect_to;
		add_action( 'login_form_defaults', [ $this, 'login_form_defaults' ], 1 );	#	run early - make it easy to override values
		add_action( 'wp_enqueue_scripts', [ $this, 'wp_localize_script' ] );
		#	Do not show login errors to users
		if ( ! WP_DEBUG ) { add_filter( 'login_errors', function( $arg ) { return null; } ); }
		$this->get_login_form_defaults();
	}

	public function wp_localize_script() {
		$attrs = $this->get_password_attrs();
		wp_localize_script( 'fluid-login-js', 'FluidLogin', [ 'name' => $attr['name'] ] );
	}

/***   Form Defaults   ***/

	/**
	 *  arranges gettin the login form defaults
	 *
	 * @since 20170122
	 */
	public function get_login_form_defaults() {
		$defaults = array();
		add_filter( 'login_form_defaults', function( $args ) use ( &$defaults ) {
			$defaults = $args;
			return $args;
		}, 9999 );
		# filter gets called here - should be a better way to get the form values
		$form = wp_login_form( array( 'echo' => false ) );
		$this->defaults = $defaults;
	}

	/**
	 *  creates the login form defaults array
	 *
	 * @since 20170123
	 * @param array $defaults
	 * @return array
	 */
	public function login_form_defaults( $defaults = array() ) {
$this->log(list_filter_hooks('login_redirect'));
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

	/**
	 *  determines whether the login or logout methods should be used
	 *
	 * @since 20170211
	 */
	public function login_form() {
		if ( is_user_logged_in() ) {
			$this->show_logout_form();
		} else {
			$this->show_login_form();
		}
	}

	/**
	 *  show the login form
	 *
	 * @since 20150502
	 */
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
				$this->element( 'label', [ 'class' => 'login-text', 'for' => $this->defaults['id_password'] ], $this->defaults['label_password'] ); ?>
				<div class="input-group"><?php
					$this->element( 'input', $this->get_password_attrs() );
					$this->tag( 'span', $this->get_password_addon_attributes() );
						fluid()->fawe( 'eye fa-stack-1x' );
						fluid()->fawe( 'ban fa-stack-1x' ); ?>
					</span>
				</div>
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

	/**
	 *  provide the attributes for the user name input field
	 *
	 * @since 20180823
	 * @return array
	 */
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

	/**
	 *  provides the attributes for the user password field
	 *
	 * @since 20180823
	 * @return array
	 */
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

	/**
	 *  Provide attributes for the user password addon
	 *
	 * @since 20190607
	 * @return array
	 */
	protected function get_password_addon_attributes() {
		$attrs = array(
			'class' => 'input-group-addon show-hide-password fa-stack',
			'title' => __( 'View your password.', 'tcc-fluid' )
		);
		return $attrs;
	}

	/**
	 *  display the remember me checkbox
	 *
	 * @since 20180823
	 */
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

	/**
	 *  display the form submit button
	 *
	 * @since 20180823
	 */
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

	/**
	 *  provides the attributes for the form submit button
	 *
	 * @since 20180909
	 * @return array
	 */
	protected function get_submit_button_attrs() {
		$attrs = array(
			'type'  => 'submit',
			'id'    => $this->defaults['id_submit'],
			'class' => 'btn btn-fluidity',
			'name'  => 'wp-submit',
		);
		return $attrs; //apply_filters( 'fluid_login_submit_button_attrs', $attrs, $this );
	}

	/**
	 *  displays the lost password anchor link
	 *
	 * @since 20180823
	 */
	protected function lost_password() {
		if ( ! empty( $this->defaults['label_lostpw'] ) ) {
			$lost_url = wp_lostpassword_url( home_url() );
			if ( ! empty( $lost_url ) ) {
				$attrs = $this->get_lost_password_attributes( $lost_url );
				$attrs = apply_filters( 'fluid_login_lost_password', $attrs );
				$this->display_lost_password_anchor( $attrs );
			}
		}
	}

	/**
	 *  The lost password anchor attributes.
	 *
	 * @since 20190606
	 * @param string $lost_url URL to retrieve lost password.
	 * @return array
	 */
	protected function get_lost_password_attributes( $lost_url ) {
		return array(
			'class' => 'pull-right',
			'href'  => $lost_url,
			'title' => __( 'You can request a new password via this link.', 'tcc-fluid' ),
			'rel'   => 'nofollow',
		);
	}

	/**
	 *  Display lost password anchor.
	 *
	 * @since 20190606
	 * @param array $attrs Attributes for anchor tag.
	 */
	protected function display_lost_password_anchor( $attrs ) {
		$this->element( 'a', $attrs, $this->defaults['label_lostpw'] );
	}

	/**
	 *  display the logout button
	 *
	 * @since 20170208
	 */
	public function show_logout_form() {
		$signout = apply_filters( 'fluid_logout_text', __( 'Sign Out', 'tcc-fluid' ) ); ?>
		<div class="text-center"><?php
			$attrs = array(
				'type'  => 'button',
				'class' => 'btn btn-fluidity',
				'href'  =>  esc_url( wp_logout_url( home_url() ) ),
				'title' =>  $signout,
				'rel'   => 'nofollow',
			);
			$fawe = fluid()->get_fawe( 'sign-out' );
			$this->element( 'a', $attrs, esc_html( $signout ) . '&nbsp;' . $fawe , true ); ?>
		</div><?php
	}


}
