<?php
/**
 * classes/Form/Customizer.php
 *
 */
/**
 * set up customizer controls
 *
 */
class TCC_Form_Control_Customizer {

	protected $control;
	protected $customize; // WP_Customize_Manager
	protected $priority = 10;
	protected $section_id;
	protected $setting_id;

#	protected static $theme; // TCC_Theme_Customizer

	use TCC_Trait_ParseArgs;

	public function __construct( $args ) {
		$this->parse_args( $args );
		if ( isset( $this->control['showhide'] ) ) {
			add_filter( 'fluid_customize_controls_localization', array( $this, 'fluid_customize_controls_localization' ) );
		}
		$render = $this->control['render'];
		$this->$render();
	}

	/**
	 * data to be passed to javascript
	 *
	 * @since 201800424
	 * @param array $data
	 * @return array
	 */
	public function fluid_customize_controls_localization( $data = array() ) {
		$data[ $this->setting_id ] = $this->control['showhide'];
		return $data;
	}

	protected function add_control( $args ) {
		$this->customize->add_control( $this->setting_id, $args );
	}

	protected function add_object_control( $obj ) {
		$this->customize->add_control( $obj );
	}

	protected function control_settings() {
		$defaults = array(
#			'settings'    => array( $this->setting_id ),
			'capability'  => $this->control['capability'],
			'priority'    => $this->priority,
			'section'     => $this->section_id,
			'label'       => $this->control['label'],
			'type'        => $this->control['render'],
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
		$args = $this->control_settings();
		$obj  = new TCC_Form_Control_Content( $this->customize, $this->setting_id, $args );
		$this->add_object_control( $obj );
	}

	protected function font() {
		$args = $this->control_settings();
		$args['choices'] = $this->control['choices'];
		$args['type']    = 'select';
		$this->add_control( $args );
	}

	protected function htmlradio() {
		$args = $this->control_settings();
		$obj  = new TCC_Form_Control_HTMLRadio( $this->customize, $this->setting_id, $args );
		$this->add_object_control( $obj );
	}

	protected function radio() {
		$args = $this->control_settings();
		$args['choices'] = $this->control['choices'];
		$args['type'] = 'radio';
fluid()->log( $this->setting_id, $args );
		$this->add_control( $args );
	}

	protected function spinner() {
		$args = $this->control_settings();
		$args['type'] = 'number';
		$this->add_control( $args );
	}

	protected function text() {
		$args = $this->control_settings();
fluid(1)->log($args);
		$this->add_control( $args );
	}


}
