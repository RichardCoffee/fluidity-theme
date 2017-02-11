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
			$this->placeholder = $this->field_text;
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
		if ( $label && $this->label_text ) {
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


}
