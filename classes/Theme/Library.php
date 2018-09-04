<?php

class TCC_Theme_Library {

	use TCC_Trait_Attributes;
	use TCC_Trait_Logging;
	use TCC_Trait_Magic;

	public function __construct() {

		// verify callable logging function
		$this->logging_check_function();

		// register method aliases
		$this->register__call( array( $this, 'logging_calling_location' ),          'debug_calling_function' );
		$this->register__call( array( $this, 'logging_get_calling_function_name' ), 'get_calling_function' );
		$this->register__call( array( $this, 'logging_was_called_by' ),             'was_called_by' );
		$this->register__call( array( $this, 'apply_attrs_element' ),               'element' );
		$this->register__call( array( $this, 'apply_attrs_tag' ),                   'tag' );

		// log the stack when one of these actions is run
		if ( WP_DEBUG && function_exists( 'add_action' ) ) {
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
		echo wp_kses( $this->get_fawe( $icon ), $this->kses() );
	}

	public function get_fawe( $icon = 'fa-question fa-border' ) {
		$css = explode( ' ', $icon );
#		if ( ( ! in_array( 'fab', $css, true ) ) && ( ! in_array( 'fas', $css, true ) ) ) {
#			array_push( $css, 'fas' ); // default for 4.7.0 icons
#		}
#		array_push( $css, 'fa' ); // 4.7.0 crossover
		$args = array (
			'class'       => 'fa ' . implode( ' ', $css ),
			'aria-hidden' => 'true',
		);
		return $this->get_element( 'i', $args );
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

$this->log($meta);

	}

	# duplicated in TCC_Plugin_Library
	public function kses() {
		return array(
			'a'    => [ 'class' => [ ], 'href' => [ ], 'itemprop' => [ ], 'rel' => [ ], 'target' => [ ], 'title' => [ ], 'aria-label' => [ ] ],
			'i'    => [ 'class' => [ ] ],
			'span' => [ 'class' => [ ], 'itemprop' => [ ] ],
		);
	}

	public function get_html_object( $html ) {
		$obj = new stdClass();
		$doc = new DOMDocument();
		$doc->loadHTML( $html );
		$element = $doc->documentElement->firstChild->firstChild;
		$obj->attrs = array();
		$obj->tag   = $element->tagName;
		$obj->text  = $element->textContent;
		if ( $element->hasAttributes() ) {
			foreach ($element->attributes as $attr) {
				$obj->attrs[ $attr->nodeName ] = $attr->nodeValue;
			}
		}
		return $obj;
	}


}
