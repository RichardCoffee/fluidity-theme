<?php
/**
 * classes/Form/Customizer.php
 *
 */
/**
 * helper class to set up customizer controls
 *
 */
class TCC_Form_Customizer {

	protected $control;
	protected $customize; // WP_Customize_Manager
	protected $priority = 10;
	protected $section_id;
	protected $setting_id;

	use TCC_Trait_ParseArgs;

	public function __construct( $args ) {
		$this->parse_args( $args );
		$render = $this->control['render'];
		$this->$render();
	}

	protected function add_control( $args ) {
		$this->customize->add_control( $this->setting_id, $args );
	}

	protected function add_custom_control( $obj ) {
		$this->customize->add_control( $obj );
	}

	protected function defaults() {
		$defaults = array(
			'settings'    => array( $this->setting_id ),
#			'setting' FIXME
			'capability'  => $this->control['capability'],
			'priority'    => $this->priority,
			'section'     => $this->section_id,
			'label'       => $this->control['label'],
#			'description' => $this->control['description'],
#			'choices' - radio or select only
			'type'        => $this->control['render'],
/*			'input_attrs' => array(
				'class' => 'my-custom-class-for-js',
				'style' => 'border: 1px solid #900',
				'placeholder' => __( 'mm/dd/yyyy' ),
			),
#			'allow_addition' =>
 */
#			'active_callback' => 'is_front_page',
		);
		if ( isset( $this->control['input_attrs'] ) ) {
			$defaults['input_attrs'] = $this->control['input_attrs'];
		}
		if ( isset( $this->control['description'] ) ) {
			$defaults['description'] = $this->control['description'];
		} elseif ( isset( $this->control['text'] ) ) {
			$defaults['description'] = $this->control['text'];
		}
		return $defaults;
	}

	protected function content() {
		$args = $this->defaults();
		$obj  = new TCC_Form_Control_Content( $this->customize, $this->setting_id, $args );
		$this->add_custom_control( $obj );
	}

	protected function font() {
		$args = $this->defaults();
		$args['choices'] = $this->control['choices'];
		$args['type']    = 'select';
		$this->add_control( $args );
	}

	protected function spinner() {
		$args = $this->defaults();
		$args['type'] = 'number';
		$this->add_control( $args );
	}

	protected function text() {
		$args = $this->defaults();
fluid()->log($args);
		$this->add_control( $args );
	}


}
