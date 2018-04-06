<?php
/**
 * classes/Form/Sanitize.php
 *
 */
/**
 * provides generic sanitization methods
 *
 */
class TCC_Form_Sanitize {

	public function checkbox( $input ) {


		return sanitize_key( $input );
	}

	public function checkbox_multiple( $input ) {
		return sanitize_key( $input );
	}

	public function colorpicker( $input ) {
		return ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $input ) ) ? $input : '';
	}

	public function font( $input ) {
		return $this->text( $input );
	}

	public function image( $input ) {
		return apply_filters( 'pre_link_image', $input );
	}

	public function post_content($input) {
		return wp_kses_post($input);
	}

	public function radio($input) {
		return sanitize_key($input);
	}

	public function radio_multiple( $input ) {
		return sanitize_key( $input );
	}

	public function select($input) {
		return sanitize_file_name($input);
	}

	public function select_multiple( $input ) {
		return array_map( array( $this, 'select' ), $input ); // FIXME
	}

	public function spinner( $input ) {
		return $this->text( $input );
	}

	public function text( $input ) {
		return wp_kses_data( $input );
	}

	public function text_color($input) {
		return $this->text($input);
	}

	public function url( $input ) {
		return apply_filters( 'pre_link_url', $input );
	}


}
