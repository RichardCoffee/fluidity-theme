<?php
/**
 * Add controls for arbitrary heading, description, line
 *
 * @package    Customizer_Library
 * @author     Devin Price
	@link       https://github.com/devinsays/customizer-library
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return NULL;
}

#class Customizer_Library_Content extends WP_Customize_Control {

class TCC_Form_Control_Content extends TCC_Form_Control_Control {

	// Whitelist content parameter
	public $content = '';

	/**
	 * Render the control's content.
	 *
	 * Allows the content to be overriden without having to rewrite the wrapper.
	 *
	 * @since   1.0.0
	 * @return  void
	 */
	public function render_content() {

#		fluid()->log(get_class_vars(__CLASS__));

		switch ( $this->type ) {

			case 'content' :

				if ( isset( $this->label ) ) {
					$attrs = array(
						'class' => 'customize-control-title centered',
						'style' => 'font-size: 17px;'
					);
					$this->apply_attrs_element( 'span', $attrs, $this->label );
				}

				if ( isset( $this->content ) ) {
					echo esc_html( $this->content );
				}

				if ( isset( $this->description ) ) {
					$this->apply_attrs_element( 'span', [ 'class' => 'description customize-control-description' ], $this->description );
				}

				break;

		}

	}

}
