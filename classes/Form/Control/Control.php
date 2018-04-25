<?php

class TCC_Form_Control_Control extends WP_Customize_Control {

	use TCC_Trait_Attributes;

	protected function tcc_link( $attrs, $setting_key = 'default' ) {
		if ( isset( $this->settings[ $setting_key ] ) && $this->settings[ $setting_key ] instanceof WP_Customize_Setting ) {
			$attrs['data-customize-setting-link'] = $this->settings[ $setting_key ]->id;
		} else {
			$attrs['data-customize-setting-key-link'] = $setting_key;
		}
		return $attrs;
	}

	/***   helper functions   ***/

	protected function element( $tag, $attrs, $text = '' ) {
		$this->apply_attrs_element( $tag, $attrs, $text );
	}

	protected function tag( $tag, $attrs ) {
		$this->apply_attrs_tag( $tag, $attrs );
	}


}
