<?php
/**
 *  Add controls for arbitrary heading, description, line
 *
 * @package    Customizer_Library
 * @author     Richard Coffee, based on code by Devin Price
 * @link       https://github.com/devinsays/customizer-library
 * @since      20180416
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return NULL;
}

class TCC_Customizer_Control_Content extends TCC_Customizer_Control_Control {

	// Whitelist content parameter
	public $content = '';

	/**
	 * Render the control's content.
	 *
	 * @since 20180416
	 * @return void
	 */
	public function render_content() {
		if ( isset( $this->label ) ) {
			$attrs = array(
				'class' => 'customize-control-title centered'
			);
			$this->element( 'span', $attrs, $this->label );
		}
		if ( isset( $this->content ) ) {
			echo esc_html( $this->content );
		}
		if ( isset( $this->description ) ) {
			$this->element( 'span', [ 'class' => 'description customize-control-description' ], $this->description );
		}
	}

}
