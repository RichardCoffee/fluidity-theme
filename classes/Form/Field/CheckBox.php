<?php

class TCC_Form_Field_CheckBox extends TCC_Form_Field_Field {

	protected $type    = 'checkbox';
	protected $checked = false;

	public function checkbox() {
		echo $this->get_checkbox();
	}

	public function get_checkbox() {
		return $this->get_input() . $this->get_label();
	}

	protected function get_input_attributes() {
		return $this->checked( parent::get_input_attributes(), $this->checked, true );
	}

}
