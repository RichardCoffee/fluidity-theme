<?php

class TCC_Widget_Login extends TCC_Widget_Widget {

	function __construct() {

		$this->title = esc_html__( 'Login', 'tcc-fluid' );
		
		if ( is_user_logged_in() && ( ! is_admin() || ( is_admin() && is_ajax() ) ) ) {
			$this->title = esc_html__( 'Logout', 'tcc-fluid' );
		}

		$this->desc  = esc_html__( 'Fluidity Login form', 'tcc-fluid' );
		$this->slug  = 'tcc_login';

		parent::__construct();

	}

	public function inner_widget( $args, $instance ) {

		fluid_login()->login_form();
#		wp_login_form();

	}

}
