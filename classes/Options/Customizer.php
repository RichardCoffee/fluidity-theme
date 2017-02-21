<?php

class TCC_Options_Customizer {

	use TCC_Trait_Singleton;

	protected function __construct( $args = array() ) {
		add_action( 'customize_register', array( $this, 'customize_register' ) );
	}

	public function customize_register( WP_Customize_Manager $customizer ) {

		
		$logo = get_theme_mod('custom-logo');
log_entry($logo);
		if ( ! $logo ) { $logo = tcc_design('logo'); }

		#$wp_customize->add_setting( 'setting_id', $this->setting_defaults() );




	}


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
