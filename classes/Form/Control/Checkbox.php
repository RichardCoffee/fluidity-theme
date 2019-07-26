<?php
/**
 * Add controls for arbitrary heading, description, line
 *
 * @package    Customizer_Library
 * @author     Devin Price
 * @link       https://github.com/devinsays/customizer-library
 * @since      20180717
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return NULL;
}

#class Customizer_Library_Content extends WP_Customize_Control {

class TCC_Form_Control_Checkbox extends TCC_Form_Control_Control {

	// Whitelist content parameter
	public $content = '';
	// Displayed title
	public $title   = '';

	/**
	 * Render the control's content.
	 *
	 * @since 20180717
	 * @return void
	 */
	public function render_content() {
		$input_id       = '_customize-input-' . $this->id;
		$description_id = '_customize-description-' . $this->id;
		if ( ! empty( $this->title ) ) {
			$this->element( 'span', [ 'class' => 'customize-control-title centered' ], $this->title );
		}
		$this->element( 'div', [ 'class' => 'customize-control-notifications-container' ] ); ?>
		<span class="customize-inside-control-row"><?php
			$attrs = array(
				'id'    => $input_id,
				'type'  => 'checkbox',
				'value' => $this->value(),
			);
			if ( ! empty( $this->description ) ) {
				$attrs['aria-describedby'] = $description_id;
			}
			$attrs = $this->setting_link( $attrs );
			$this->checked( $attrs, $this->value(), true );
			$this->element( 'input', $attrs );
			$this->element( 'label', [ 'for' => $input_id ], $this->label );
			if ( ! empty( $this->description ) ) {
				$attrs = array(
					'id'    => $description_id,
					'class' => 'description customize-control-description',
				);
				$this->element( 'span', $attrs, $this->description );
			} ?>
		</span><?php
	}


}
