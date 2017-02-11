<?php

/*
 *  File:   classes/Form/Field/Field.php
 *
 *  note:  register_setting calls need to be updated to WP4.7
 */

abstract class TCC_Form_Field_Field {

#	protected $click;                   # onchange attribute
#	protected $echo      = true;        # echo html
	protected $field_css = '';          # field css
	protected $field_default;           # default value
	protected $field_id;                # field id
	protected $field_name;              # field name
	protected $field_type = 'text';     # type of field
	protected $field_value;             # field value
	protected $label_css  = '';         # label css
	protected $label_text = '';         # label text
	protected $placeholder;             # placeholder text
#	protected $post_id;                 # word press post id number
	protected $sanitize   = 'esc_attr'; # default sanitize method

	use TCC_Trait_ParseArgs;

	public function __construct( $args ) {
		$this->parse_args( $args );
		if ( ( empty( $this->placeholder ) ) && ( ! empty( $this->label_text ) ) ) {
			$this->placeholder = $this->text;
		}
		if ( empty( $this->field_id ) ) {
			$this->field_id = $this->field_name;
		}
	}

	public function input( $label = true ) {
		$attrs = array(
			'id'          => $this->field_id,
			'type'        => $this->field_type,
			'class'       => $this->field_css,
			'name'        => $this->field_name,
			'value'       => $this->field_value,
			'placeholder' => $this->placeholder,
		);
		if ($label && $this->label_text) {
			$this->label();
		} ?>
		<input <?php apply_attrs( $attrs ); ?> /><?php
	}

	protected function label() {
		$attrs = array(
			'class' => $this->label_css,
			'for'   => $this->field_id,
		); ?>
		<label <?php apply_attrs( $attrs ); ?>>
			<?php e_esc_html( $this->label_text ); ?>
		</label><?php
	}


}	#	end of TCC_Form_Field_Field class

class TCC_Form_Field_Admin extends TCC_Form_Field_Field {

	protected $action   = 'admin_init';  #  when to register variable
	protected $callback = 'input';       #  display method
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
			register_setting( $this->group, $this->name, $this->sanitize );
			$callback = ( is_array( $this->callback ) ) ? $this->callback : array( &$this, $this->callback );
			add_settings_field( $this->name, $this->label(), $callback, $this->group );
		}
	}

	public function input( $label = false ) {
		parent::input( $label );
	}


}

class TCC_Form_Field_Meta extends TCC_Form_Field_Field {

  public function __construct($args) {
    parent::__construct($args);
    $this->value = get_post_meta($this->post_id,$this->name,true);
  }


}

class TCC_Form_Field_Theme extends TCC_Form_Field_Field {


}
