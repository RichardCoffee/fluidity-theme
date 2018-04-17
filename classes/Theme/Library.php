<?php

class TCC_Theme_Library {


	use TCC_Trait_Attributes;
	use TCC_Trait_Logging;
	use TCC_Trait_Magic;

	public function __construct() {
		$this->logging_check_function();
		$this->register__call( array( $this, 'logging_calling_location' ),          'debug_calling_function' );
		$this->register__call( array( $this, 'logging_get_calling_function_name' ), 'get_calling_function' );
		$this->register__call( array( $this, 'logging_was_called_by' ),             'was_called_by' );
/*		if ( WP_DEBUG && function_exists( 'add_action' ) ) {
			add_action( 'deprecated_function_run',    array( $this, 'logging_log_deprecated' ), 10, 3 );
			add_action( 'deprecated_constructor_run', array( $this, 'logging_log_deprecated' ), 10, 3 );
			add_action( 'deprecated_file_included',   array( $this, 'logging_log_deprecated' ), 10, 4 );
			add_action( 'deprecated_argument_run',    array( $this, 'logging_log_deprecated' ), 10, 3 );
			add_action( 'deprecated_hook_run',        array( $this, 'logging_log_deprecated' ), 10, 4 );
			add_action( 'doing_it_wrong_run',         array( $this, 'logging_log_deprecated' ), 10, 3 );
		} //*/
	}


	/**  Font Awesome icons  **/

	public function fawe( $icon = 'fa-question fa-border' ) {
		echo $this->get_fawe( $icon );
	}

	public function get_fawe( $icon = 'fa-question fa-border' ) {
		$css = explode( ' ', $icon );
#		if ( ( ! in_array( 'fab', $css, true ) ) && ( ! in_array( 'fas', $css, true ) ) ) {
#			array_push( $css, 'fas' ); // default for 4.7.0 icons
#		}
		array_push( $css, 'fa' ); // 4.7.0 crossover
		$args = array (
			'class'       => implode( ' ', $css ),
			'aria-hidden' => 'true',
		);
		return $this->get_apply_attrs_tag( 'i', $args ) . '</i>';
	}

	public function get_widget_fawe() {
		return array(
			'default'  => array(
				'plus'  => 'fa-plus',
				'minus' => 'fa-minus',
			),
			'square'   => array(
				'plus'  => 'fa-plus-square',
				'minus' => 'fa-minus-square',
			),
			'circle'   => array(
				'plus'  => 'fa-plus-circle',
				'minus' => 'fa-minus-circle',
			),
			'sort'     => array(
				'plus'  => 'fa-sort-down',
				'minus' => 'fa-sort-up',
			),
			'sortel'   => array(
				'plus'  => 'fa-sort-down',
				'minus' => 'fa-sort-up fa-rotate-270',
			),
			'window'   => array(
				'plus'  => 'fa-window-maximize',
				'minus' => 'fa-window-minimize',
			),
			'toggle'   => array(
				'plus'  => 'fa-toggle-down',
				'minus' => 'fa-toggle-up',
			),
			'level'    => array(
				'plus'  => 'fa-level-down',
				'minus' => 'fa-level-up',
			),
		);
	}


	/***   Is_*   ***/

	public function is_mobile() {
		# Use mobble plugin if present
		if ( class_exists( 'Mobile_Detect' ) && function_exists( 'is_mobile' ) ) {
			$mobile = is_mobile();
		} else {
			$mobile = wp_is_mobile();
		}
		return $mobile;
	}

	/**  image functions  **/

	public function get_image_attrs( $id ) {
		$meta = wp_get_attachment_metadata( $id );
log_entry($meta);
	}


}
