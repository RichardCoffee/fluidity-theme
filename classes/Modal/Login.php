<?php

class TCC_Modal_Login extends TCC_Modal_Bootstrap {


	protected $form   =  null;
	protected $prefix = 'fluid_login';


	public function __construct( $form = null ) {
		if ( $form instanceof TCC_Form_Login_Login ) {
			$this->form = $form;
		} else {
			$this->form = new TCC_Form_Login_Login;
		}
		$this->title = __( 'Login', 'tcc-fluid' );
	}

	protected function modal_body() {
		$this->form->login_form( false );
	}

	protected function modal_footer() {
		$this->form->show_login_form_links();
	}

	protected function get_button_text( $text = '' ) {
		return $this->form->defaults['label_log_in'];
	}



}
