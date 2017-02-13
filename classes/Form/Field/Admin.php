<?php

/*
 *  File:   classes/Form/Field/Admin.php
 *
 *  note:  register_setting calls need to be updated to WP4.7
 */

class TCC_Form_Field_Admin extends TCC_Form_Field_Field {

	protected $action   = 'admin_init';  #  when to register variable
	protected $callback = array( $this, 'input' );       #  display method
	protected $default  = '';
	protected $group;

	public function __construct( $args ) {
		parent::__construct( $args );
		$sanitize = $this->sanitize;
		if ( empty( $this->field_value ) ) {
			$possible = get_option( $this->field_name );
			if ( $possible ) {
				$this->field_value = $sanitize( $possible );
			}
		}
		if ( empty( $this->field_value) && ! empty( $this->field_default ) ) {
			$this->field_value = $sanitize( $this->field_default );
		}
		add_action( $this->action, array( &$this, 'register_field' ), 9 );
	}

	public function register_field() {
		if ( ! empty( $this->group ) ) {
			register_setting( $this->group, $this->field_name, $this->sanitize );
#			$callback = ( is_array( $this->callback ) ) ? $this->callback : array( &$this, $this->callback );
			add_settings_field( $this->field_name, $this->label(), $this->callback, $this->group );
		}
	}

	public function input( $label = false ) {
		parent::input( $label );
	}


}
