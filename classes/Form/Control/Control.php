<?php

class TCC_Form_Control_Control extends WP_Customize_Control {

	use TCC_Trait_Attributes;

	# functionally replaces method link() of class WP_Customize_Control
	public function setting_link( $attrs, $setting_key = 'default' ) {
		if ( isset( $this->settings[ $setting_key ] ) && $this->settings[ $setting_key ] instanceof WP_Customize_Setting ) {
			$attrs['data-customize-setting-link'] = $this->settings[ $setting_key ]->id;
		} else {
			$attrs['data-customize-setting-key-link'] = $setting_key;
		}
		return $attrs;
	}


/* testing */

public function link ( $setting_key = 'default', $attrs = array() ) {
	return $this->setting_link( $attrs, $setting_key );
}


}
