<?php

class TCC_Modal_Login extends TCC_Modal_Bootstrap {


	protected $form   =  null;
	protected $prefix = 'fluid_login';


	public function __construct( TCC_Form_Login_Login $form = null ) {
		if ( is_object( $form ) ) {
			$this->form = $form;
		} else {
			$this->form = new TCC_Form_Login_Login;
		}
		$this->title = __( 'Login', 'tcc-fluid' );
	}

	protected function modal_body() {
		$this->form->login_form();
	}

	protected function modal_footer() {
	}

	protected function get_button_text( $text = '' ) {
		return ( empty( $text ) ) ? $this->title : $text;
	}



}
