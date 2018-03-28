<?php
/**
 * classes/Trait/Attributes.php
 *
 */
/**
 * A trait that provides methods to generate html for tag attributes
 *
 */
trait TCC_Trait_Attributes {

	/**
	 * echo the generated html attributes
	 *
	 * @param array $attrs an associative array containing the attribute keys and values
	 */
	public function apply_attrs( $attrs ) {
		echo $this->get_apply_attrs( $attrs );
	}

	/**
	 * echo the generated tag html
	 *
	 * @param array $attrs an associative array containing the attribute keys and values
	 * @param string $html_tag the tag to be generated
	 */
	public function apply_attrs_tag( $attrs, $html_tag ) {
		echo $this->get_apply_attrs_tag( $attrs, $html_tag );
	}

	/**
	 * generates the html for the tag attributes
	 *
	 * @param array $attrs contains attribute/value pairs
	 * @return string
	 */
	public function get_apply_attrs( $attrs ) {
		$html = ' ';
		foreach( $attrs as $attr => $value ) {
			if ( empty( $value ) ) {
				if ( in_array( $attr, array( 'itemscope', 'value' ), true ) ) {
					$attrs .= $attr . '="" ';
				}
				continue;
			}
			switch( $attr ) {
				case 'action':
				case 'href':
				case 'itemtype': # schema.org
				case 'src':
					$value = esc_url( $value );
					break;
				case 'class':
					$value = $this->sanitize_html_class( $value );
					break;
				case 'value':
					$value = esc_html( $value );
					break;
				case 'aria-label':
				case 'placeholder':
				case 'title':
					$value = esc_attr( wp_strip_all_tags( $value ) );
				default:
					$value = esc_attr( $value );
			}
			$html .= $attr . '="' . $value . '" ';
		}
		return $html;
	}

	/**
	 * generates the html for the desired tag and attributes
	 *
	 * @param array $attrs contains attribute/value pairs
	 * @param string $html_tag tag to be generated
	 * @return string
	 */
	public function get_apply_attrs_tag( $attrs, $html_tag ) {
		$html = '<' . $html_tag . $this->get_apply_attrs( $attrs );
		$html .= ( $this->is_self_closing( $html_tag ) ) ? ' />' : '>';
		return $html;
	}

	/**
	 * checks for tags that are self closing
	 *
	 * @param string $tag tag to check for
	 * @return bool
	 */
	private function is_self_closing( $tag ) {
		$self_closing = array( 'area', 'base', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr' );
		return in_array( $tag, $self_closing, true );
	}

	/**
	 * applys the wordpress function sanitize_html_class to a string containing multiple css classes
	 *
	 * @param string $css css classes to be sanitized
	 * @return string
	 */
	private function sanitize_html_class( $css ) {
		$classes = explode( ' ', $css );
		$result  = array_map( 'sanitize_html_class', $classes );
		return implode( ' ', $result );
	}


}
