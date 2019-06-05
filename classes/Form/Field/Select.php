<?php
/**
 *  Form select field class
 *
 * @package Fluidity
 * @subpackage Form
 * @since 20190604
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2019, Richard Coffee
 * @link https://github.com/RichardCoffee/custom-post-type/blob/master/classes/Form/Field/Select.php
 */
defined( 'ABSPATH' ) || exit;
/**
 *  Handles all tasks required for displaying and saving a select field on an admin form.
 */
class TCC_Form_Field_Select extends TCC_Form_Field_Field {

	/**
	 * @since 20190604
	 * @var array Contains options for select field.
	 */
	public $chices = array();
	/**
	 * @since 20190604
	 * @var string Contains the form field type.
	 */
	protected $type = 'select';

	/**
	 *  Constructor function
	 *
	 * @since 20190604
	 */
	public function __construct( $args ) {
		parent::__construct( $args );
		$this->sanitize = array( $this, 'sanitize' );
	}

	/**
	 *  Render a select field.
	 *
	 * @since 20190605
	 * @uses TCC_Trait_Attributes::element()
	 * @uses TCC_Trait_Attributes::tag()
	 * @uses TCC_Trait_Attributes::selected()
	 */
	public function select() {
		if ( $this->choices ) {
			$element = $this->get_select_element_attributes();
			$this->tag( 'select', $element );
				$choices = $this->choices;
				if ( is_array( $choices ) ) {
					foreach( $choices as $key => $text ) {
						$attrs = [ 'value' => $key ];
						$attrs = $this->selected( $attrs, $key, $this->field_value );
						$this->element( 'option', $attrs, ' ' . $text . ' ' );
					}
				} elseif ( method_exists( $this, $choices ) ) {
					$this->$choices( $this->field_value );
				} elseif ( function_exists( $choices ) ) {
					$choices( $this->field_value );
				} ?>
			</select><?php
		}
	}

	/**
	 *  Determines attributes for select element.
	 *
	 * @since 20190605
	 */
	protected function get_select_element_attributes() {
		$attrs = array(
			'type' => $this->type,
			'name' => $this->field_name,
			'onchange' => $this->onchange,
		);
		$attrs['multiple'] = ( strpos( '[]', $this->field_name ) === false ) ? '' : 'multiple';
		return $attrs;
	}

	/**
	 *  Default sanitize/validate method for select fields.
	 *
	 * @since 20190605
	 * @param string $input
	 * @return string
	 * @see classes/Form/Sanitize.php
	 */
	public function sanitize( $input ) {
		$input = sanitize_key( $input );
		return ( array_key_exists( $input, $this->choices ) ? $input : '' );
	}


}
