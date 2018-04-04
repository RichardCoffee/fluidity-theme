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


/*		$logo = get_theme_mod('custom-logo');
log_entry($logo);
		if ( ! $logo ) { $logo = tcc_design('logo'); }

		#$customize->add_setting( 'setting_id', $this->setting_defaults() );

//*/


	private function setting_defaults() {
		return array(
#			'default'              => '',
			'type'                 => 'theme_mod', // or 'option'
#			'capability'           => 'edit_theme_options',
#			'theme_supports'       => '', // Rarely needed.
#			'transport'            => 'refresh', // or postMessage
			'sanitize_callback'    => 'sanitize_text_field',
#			'sanitize_js_callback' => '', // Basically to_json.
		) );

	}


}
