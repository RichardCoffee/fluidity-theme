<?php
/**
 *  Login form for navbar
 *
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 */
class TCC_Form_Navbar extends TCC_Form_Login {

	protected $pull_right  = true;


	public function __construct( $args = array() ) {
		parent::__construct( $args );
		add_filter( 'fluid_login_lost_password', [ $this, 'fluid_login_lost_password' ], 9 );
	}

	public function show_login_form() {
		$attrs = array(
			'id'     => $this->defaults['form_id'],
			'class'  => 'navbar-form navbar-login-form' . ( ( $this->pull_right ) ? ' navbar-right' : '' ),
			'name'   => 'loginform',
			'action' => site_url( '/wp-login.php' ),
			'method' => 'post',
		); ?>
		<form <?php $this->apply_attrs( $attrs ); ?>>
			<div class='form-group login-username'><?php
				$this->element( 'label', [ 'class' => 'sr-only', 'for' => $this->defaults['id_username'] ], $this->defaults['label_username'] );
				$this->element( 'input', $this->get_username_attrs() ); ?>
			</div>
			<div class='form-group login-password'><?php
				$this->element( 'label', [ 'class' => 'sr-only', 'for' => $this->defaults['id_password'] ], $this->defaults['label_password'] );
				$this->element( 'input', $this->get_password_attrs() ); ?>
			</div>
			<div class="form-group login-lost-password"><?php
				$this->lost_password(); ?>
			</div>
			<div class="form-group login-submit"><?php
				$this->submit_button(); ?>
			</div>
		</form><?php
	}

	public function fluid_login_lost_password( $attrs ) {
		$attrs['class'] = '';
		return $attrs;
	}


}
