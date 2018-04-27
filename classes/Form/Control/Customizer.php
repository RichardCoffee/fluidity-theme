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

	protected static $theme; // TCC_Theme_Customizer

	use TCC_Trait_ParseArgs;

	public function __construct( $args ) {
		if ( empty( self::$theme ) ) {
			self::$theme = fluid_customizer();
		}
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
		$current = $this->control['showhide'];
fluid()->logobj($this);
		if ( isset( $current['respond'] ) ) {
			$data['respond'][ $this->setting_id ] = $current['respond'];
		}
		if ( isset( $current['control'] ) ) {
			$data[ $this->setting_id ] = $current;
		}
		return $data;
	}

	protected function add_control( $args ) {
		$this->customize->add_control( $this->setting_id, $args );
#fluid()->logobj($this);
	}

	protected function add_object_control( $obj ) {
		$this->customize->add_control( $obj );
	}

	protected function control_settings() {
		$defaults = array(
			'settings'    => array( $this->setting_id ),
			'priority'    => $this->priority,
			'section'     => $this->section_id,
			'type'        => $this->control['render'],
		);
		$args = array_merge( $defaults, $this->control );
		return self::$theme->control_defaults( $args );
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
		$args['type'] = 'radio';
		$obj  = new TCC_Form_Control_HTMLRadio( $this->customize, $this->setting_id, $args );
		$this->add_object_control( $obj );
	}

	protected function radio() {
		$args = $this->control_settings();
		$args['choices'] = $this->control['choices'];
		$args['type'] = 'radio';
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
