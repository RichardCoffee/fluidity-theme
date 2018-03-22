<?php

/*
 *  File:   classes/Form/Field/Field.php
 *
 */

abstract class TCC_Form_Field_Field {

#	protected $echo       = true;       # echo html
	protected $field_css  = '';         # field css
	protected $field_default;           # default value
	protected $field_help = '';         # used for tooltip text
	protected $field_id;                # field id
	protected $field_name;              # field name
	protected $field_postext = '';      # text shown below input
	protected $field_pretext = '';      # text shown above input
	protected $field_type = 'text';     # input type
	protected $field_value;             # field value
	protected $label_css  = '';         # label css
	protected $label_text = '';         # label text
	protected $onchange = null;         # onchange attribute
	protected $placeholder;             # placeholder text
#	protected $post_id;                 # word press post id number
	protected $sanitize   = 'esc_attr'; # default sanitize method

	use TCC_Trait_Attributes;
	use TCC_Trait_Magic;
	use TCC_Trait_ParseArgs;

	public function __construct( $args ) {
		$this->parse_args( $args );
		if ( ( empty( $this->placeholder ) ) && ( ! empty( $this->label_text ) ) ) {
			$this->placeholder = $this->label_text;
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
		$this->apply_attrs_tag( $attrs, 'input' );
	}

	protected function label() {
		$attrs = array(
			'class' => $this->label_css,
			'for'   => $this->field_id,
		);
		$label  = $this->get_apply_attrs_tag( $attrs, 'label' );
		$label .= esc_html( $this->label_text );
		$label .= '</label>';
		return $label;
	}

	public function sanitize( $input ) {
		if ( $this->sanitize && ( is_array( $this->sanitize ) ) && method_exists( $this->sanitize[0], $this->sanitize[1] ) ) {
			list( $obj, $method ) = $this->sanitize;
			$output = $obj->$method( $input );
		} else if ( $this->sanitize && ( is_string( $this->sanitize ) ) && function_exists( $this->sanitize ) ) {
			$sanitize = $this->sanitize;
			$output   = $sanitize( $input );
		} else {
			$output = strip_tags( stripslashes( $input ) );
		}
		return $output;
	}

}
