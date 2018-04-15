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
fluid()->log($args);
		$this->parse_args( $args );
		$render = $this->control['render'];
		$this->$render();
	}

	protected function add_control( $args ) {
		$this->customize->add_control( $this->setting_id, $args );
	}

	protected function defaults() {
		$defaults = array(
			'settings'    => $this->setting_id,
#			'setting' FIXME
			'capability'  => $this->control['capability'],
			'priority'    => $this->priority,
			'section'     => $this->section_id,
			'label'       => $this->control['label'],
#			'description' => __( 'Control description', 'tcc-fluid' );
#			'choices' - radio or select only
			'type'        => $this->control['render'],
/*			'input_attrs' => array(
				'class' => 'my-custom-class-for-js',
				'style' => 'border: 1px solid #900',
				'placeholder' => __( 'mm/dd/yyyy' ),
			), */
#			'active_callback' => 'is_front_page',
		);
		if ( isset( $this->control['input_attrs'] ) ) {
			$defaults['input_attrs'] = $this->control['input_attrs'];
		}
		if ( isset( $this->control['text'] ) ) {
			$defaults['description'] = $this->control['text'];
		}
		return $defaults;
	}

	protected function font() {
		$args = $this->defaults();
		$args['choices'] = $this->control['source'];
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
