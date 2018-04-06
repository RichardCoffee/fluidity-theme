<?php

class TCC_Options_Customizer {


	public function __construct( $args = array() ) {
		add_action( 'customize_register', array( $this, 'customize_register' ) );
	}

	public function customize_register( WP_Customize_Manager $customize ) {
		$this->typography( $customize );
	}

	protected function typography( WP_Customize_Manager $customize ) {
		$design = new TCC_Options_Design;
		$design->customizer( $customize );
	}


}
