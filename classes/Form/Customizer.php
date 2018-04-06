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

	protected $customize; // WP_Customize_Manager
	protected $item; # taken from Options_Class->options_layout()
	protected $priority = 10;
	protected $section_id;
	protected $setting_id;

	use TCC_Trait_ParseArgs;

	public function __construct( $args ) {
		$this->parse_args( $args );
		$render = $this->item['render'];
		$this->$render();
	}

	protected function add_control( $args ) {
		$this->customize->add_control( $this->setting_id, $args );
	}

	protected function defaults() {
		$defaults = array(
			'settings'    => $this->setting_id,
#			'setting' FIXME
			'priority'    => $this->priority,
			'section'     => $this->section_id,
			'label'       => $this->item['label'],
#			'choices' - radio or select only
			'type'        => $this->item['render'],
			'input_attrs' => ( ! empty( $this->item['attrs'] ) ) ? $this->item['attrs'] : array(),
/*			'input_attrs' => array(
				'class' => 'my-custom-class-for-js',
				'style' => 'border: 1px solid #900',
				'placeholder' => __( 'mm/dd/yyyy' ),
			), */
#			'active_callback' => 'is_front_page',
		);
		if ( isset( $this->item['text'] ) ) {
			$defaults['description'] = $this->item['text'];
		}
		return $defaults;
	}

	protected function font() {
		$args = $this->defaults();
		$args['choices'] = $this->item['source'];
		$args['type']    = 'select';
		$this->add_control( $args );
	}

	protected function spinner() {
		$args = $this->defaults()
		$args['type'] = 'number';
		$this->add_control( $args );
	}

	protected function text() {
		$args = $this->defaults();
fluid()->log($args);
		$this->add_control( $args );
	}


}
