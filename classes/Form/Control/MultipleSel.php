<?php
/**
 * classes/Form/Control/MultipleSel.php
 *
 * @link https://jayj.dk/multiple-select-lists-theme-customizer/
 */
#class Jayj_Customize_Control_Multiple_Select extends WP_Customize_Control {

class TCC_Form_Control_MultipleSel extends TCC_Form_Control_Control {

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

		<label>
			<span class="customize-control-title">
				<?php echo esc_html( $this->label ); ?>
			</span><?php

			$attrs = [ 'multiple' => 'multiple', 'style' => 'height: 100%;' ];
			$attrs = $this->link( $attrs );
			$this->tag( 'select', $attrs );
				foreach ( $this->choices as $value => $label ) {
					$attrs = [ 'value' => $value ];
					$attrs = $this->selected( $attrs, in_array( $value, $this->value() ) );
					$this->element( 'option', $attrs, $label );
				} ?>
			</select>
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
