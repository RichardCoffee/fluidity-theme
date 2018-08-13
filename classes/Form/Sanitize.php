<?php
/**
 * classes/Form/Sanitize.php
 *
 */
/**
 * provides generic sanitization methods for customizer
 *
 * @link https://divpusher.com/blog/wordpress-customizer-sanitization-examples
 */
class TCC_Form_Sanitize {

	public function checkbox( $input ) {
		return sanitize_key( $input );
#		return ( isset( $input ) ? true : false ); wtf?
	}

	public function checkbox_multiple( $input ) {
		return sanitize_key( $input );
	}

	public function colorpicker( $input ) {
		return sanitize_hex_color( $input );
#		return ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $input ) ) ? $input : '';
	}

	/**
	 *  sanitize function for render 'title'
	 *
	 * @since 20180812
	 * @param string $input
	 * @return string
	 */
public function content( $input ) {
return $this->text( $input );
}

	public function email( $input ) {
		return sanitize_email( $input );
	}

	public function font( $input ) {
		return $this->text( $input );
	}

	public function image( $input ) {
		return apply_filters( 'pre_link_image', $input );
	}

	public function number( $input ) {
		return absint( $input );
	}

	public function post_content($input) {
		return wp_kses_post($input);
	}

	# See also: classes/Form/Field/Radio.php
	public function radio( $input, $setting = null ) {
		$input   = sanitize_key( $input );
		$choices = $setting->manager->get_control( $setting->id )->choices;
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}

	public function radio_multiple( $input ) {
		return sanitize_key( $input );
	}

	public function select( $input, $setting ) {
		$input = sanitize_key($input);
		$choices = $setting->manager->get_control( $setting->id )->choices;
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}

	public function select_multiple( $input, $setting ) {
		$output  = array();
		$choices = $setting->manager->get_control( $setting->id )->choices;
		foreach( $input as $key ) {
			$check = sanitize_key( $key );
			if ( array_key_exists( $check, $choices ) ) {
				$output[] = $check;
			}
		}
		return $output;
	}

	public function spinner( $input ) {
		return $this->text( $input );
	}

	public function text( $input ) {
		return wp_filter_nohtml_kses( $input );
	}

	public function text_color( $input ) {
		return $this->text( $input );
	}

	/**
	 *  sanitize function for render 'title'
	 *
	 * @since 20180812
	 * @param string $input
	 * @return string
	 */
	public function title( $input ) {
		return $this->text( $input );
	}

	public function url( $input ) {
		return apply_filters( 'pre_link_url', $input );
	}


}
