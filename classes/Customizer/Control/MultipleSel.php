<?php
/**
 * classes/Form/Control/MultipleSel.php
 *
 * @link https://jayj.dk/multiple-select-lists-theme-customizer/
 */

class TCC_Customizer_Control_MultipleSel extends TCC_Customizer_Control_Control {

	/**
	 * The type of customize control being rendered.
	 */
	public $type = 'multiple-select';

	/**
	 * Displays the multiple select on the customize screen.
	 */
	public function render_content() {

		if ( empty( $this->choices ) ) {
			return;
		} ?>

		<label><?php
			$this->element( 'span', [ 'class' => 'customize-control-title' ], $this->label );
			$attrs = [ 'multiple' => 'multiple', 'style' => 'height: 100%;' ];
			$attrs = $this->setting_link( $attrs );
			$html  = '';
			foreach ( $this->choices as $value => $label ) {
				$opts = [ 'value' => $value ];
				$this->selected( $opts, in_array( $value, $this->value() ) );
				$html .= $this->get_element( 'option', $opts, $label );
			}
			$this->element( 'select', $attrs, $html, true ); ?>
		</label><?php
	}


}

/* Example from above @link

$wp_customize->add_setting( 'multiple_select_setting', array(
    'default' => array(), // Either empty or fill it with your default values
) );

$wp_customize->add_control(
    new Jayj_Customize_Control_Multiple_Select(
        $wp_customize,
        'multiple_select_setting',
        array(
            'settings' => 'multiple_select_setting',
            'label'    => 'Testing multiple select',
            'section'  => 'themedemo_demo_settings', // Enter the name of your own section
            'type'     => 'multiple-select', // The $type in our class
            'choices'  => array( 'google' => 'Google', 'bing' => 'Bing' ) // Your choices
        )
    )
);

*/
