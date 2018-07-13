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
		$args   = $this->control_settings();
		if ( method_exists( $this, $render ) ) {
			$this->$render( $args );
		} else {
			$this->add_control( $args );
		}
		if ( isset( $this->control['add_partial'] ) ) {
			$this->add_partial();
		}
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

	/**
	 * add partial for a control
	 *
	 * @since 20180502
	 */
	protected function add_partial() {
		extract( $this->control['add_partial'] ); // $id, $args
		$this->customize->selective_refresh->add_partial( $id, $args );
	}

	protected function add_control( $args ) {
		$this->customize->add_control( $this->setting_id, $args );
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
		return fluid_customizer()->control_defaults( $args );
	}


	/***   Render   ***/

	protected function content( $args ) {
		$obj  = new TCC_Form_Control_Content( $this->customize, $this->setting_id, $args );
		$this->add_object_control( $obj );
	}

	protected function font( $args ) {
		$args['choices'] = $this->control['choices'];
		$args['type']    = 'select';
		$this->add_control( $args );
	}

	protected function htmlradio( $args ) {
		$args['type'] = 'radio';
		$obj = new TCC_Form_Control_HTMLRadio( $this->customize, $this->setting_id, $args );
		$this->add_object_control( $obj );
	}

	protected function spinner( $args ) {
		$args['type'] = 'number';
		$this->add_control( $args );
	}

	protected function text( $args ) {
fluid(1)->log($args);
		$this->add_control( $args );
	}


}
