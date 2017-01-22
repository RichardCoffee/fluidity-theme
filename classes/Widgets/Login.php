<?php

class TCC_Widgets_Login extends TCC_Widgets_Basic {

	private $wp_login_defaults;

	function __construct() {
		$this->title = esc_html__('Login','tcc-fluid');
		$this->desc  = esc_html__('Fluidity Login form','');
		$this->slug  = 'tcc_login';
		parent::__construct();
		add_filter('login_form_defaults',array($this,'login_form_defaults'),1000);
		$temp = wp_login_form(array('echo'=>false));
log_entry($temp);
	}

	public function inner_widget($args,$instance) {
		tcc_login_form($this->wp_login_defaults);
	}

	public function login_form_defaults($args) {
		$this->wp_login_defaults = $args;
		return $args;
	}

}
