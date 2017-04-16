<?php

abstract class TCC_Options_Options {

	protected $base     = 'options'; # change this in child
	protected $priority = 1000;      # change this in child

	abstract protected function form_title();
	abstract protected function describe_options();
	abstract protected function options_layout();

	public function __construct() {
		add_filter( 'fluidity_options_form_layout', array( $this, 'form_layout' ), $this->priority );
#		add_action( 'fluid-customizer', array( $this, 'options_customize_register' ), $this->priority, 2 );
	}

	public function form_layout( $form ) {
		$form[ $this->base ] = array(
			'describe' => array( $this, 'describe_options' ),
			'title'    => $this->form_title(),
			'option'   => 'tcc_options_'.$this->base,
			'layout'   => $this->options_layout(),
		);
		return $form;
	}
/*
	public function options_customize_register($wp_customize, TCC_Options_Fluidity $form) {
		$wp_customize->add_section( 'fluid_'.$this->base, array('title' => $this->form_title(), 'priority' => $this->priority));
		$form->customizer_settings($wp_customize,$this->base);
	} //*/

}
