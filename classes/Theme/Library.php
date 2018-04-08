<?php

class TCC_Theme_Library {


	use TCC_Trait_Attributes;
	use TCC_Trait_Logging;


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
			'default' => array(
				'plus'  => 'fa-plus',
				'minus' => 'fa-minus',
			),
			'square'  => array(
				'plus'  => 'fa-plus-square',
				'minus' => 'fa-minus-square',
			),
			'circle'  => array(
				'plus'  => 'fa-plus-circle',
				'minus' => 'fa-minus-circle',
			),
			'sort'    => array(
				'plus'  => 'fa-sort-down',
				'minus' => 'fa-sort-up',
			),
			'window'  => array(
				'plus'  => 'fa-window-maximize',
				'minus' => 'fa-window-minimize',
			),
			'toggle'  => array(
				'plus'  => 'fa-toggle-down',
				'minus' => 'fa-toggle-up',
			),
			'level'   => array(
				'plus'  => 'fa-level-down',
				'minus' => 'fa-level-up',
			),
		);
	}



	/**  image functions  **/

	public function get_image_attrs( $id ) {
		$meta = wp_get_attachment_metadata( $id );
log_entry($meta);
	}


}
