<?php

class TCC_Theme_Library {


	/**  attribute functions  **/

	public function apply_attrs( $args ) {
		echo $this->get_apply_attrs( $args );
	}

	public function get_apply_attrs( $args ) {
		$attrs = ' ';
		foreach( $args as $attr => $value ) {
			if ( empty( $value ) ) {
				continue;
			}
			switch( $attr ) {
				case 'action':
				case 'href':
				case 'src':
					$value = esc_url( $value );
					break;
				case 'value':
					$value = esc_html( $value );
					break;
				case 'aria-label':
				case 'title':
					$value = wp_strip_all_tags( $value );
				default:
					$value = esc_attr( $value );
			}
			$attrs .= $attr . '="' . $value . '" ';
		}
		return $attrs;
	}


	/**  font awesome  **/

	public function fawe( $icon = 'fa-question fa-border' ) {
		echo $this->get_fawe( $icon );
	}

	public function get_fawe( $icon = 'fa-question fa-border' ) {
		return '<i class="fa ' . $icon . ' aria-hidden="true"></i>';
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
