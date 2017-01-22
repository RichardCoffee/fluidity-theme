<?php

class TCC_Widgets_Login extends TCC_Widgets_Basic {

	function __construct() {
		$this->title = esc_html__('Login','tcc-fluid');
		$this->desc  = esc_html__('Fluidity Login form','');
		$this->slug  = 'tcc_login';
		parent::__construct();
	}

	public function inner_widget($args,$instance) {
		tcc_login_form();
	}

}
