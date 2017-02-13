<?php

class TCC_Widget_Login extends TCC_Widget_Widget {

	function __construct() {
		$this->title = esc_html__( 'Login', 'tcc-fluid' );
		$this->desc  = esc_html__( 'Fluidity Login form', 'tcc-fluid' );
		$this->slug  = 'tcc_login';
		parent::__construct();
	}

	public function inner_widget( $args, $instance ) {
		tcc_login_form();
#		wp_login_form();
	}

}
