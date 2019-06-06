<?php
/**
 *  Login form for navbar
 *
 * @package Fluidity
 * @subpackage Login
 * @since 20180929
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/Form/Login/Navbar.php
 */
defined( 'ABSPATH' ) || exit;
/**
 *  Class that displays inputs for navbar login fields.
 *
 * @since 20180923
 */
class TCC_Form_Login_Navbar extends TCC_Form_Login_Login {


	protected $pull_right  = true;


	public function __construct( $args = array() ) {
		parent::__construct( $args );
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

	/**
	 *  The lost password anchor attributes.
	 *
	 * @since 20190606
	 * @param string $lost_url URL to retrieve lost password.
	 * @return array
	 */
	protected function get_lost_password_attributes( $lost_url ) {
		$attrs = parent::get_lost_password_attributes( $lost_url );
		unset( $attrs['class'] );
		return $attrs;
	}


}
