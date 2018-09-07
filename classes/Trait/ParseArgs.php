<?php
/**
 *  Add support for parsing incoming arrays.
 *
 * @package Fluidity
 * @subpackage Traits
 * @since 20170128
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/Trait/ParseArgs.php
 */
defined( 'ABSPATH' ) || exit;
/**
 *  trait TCC_Trait_ParseArgs
 *
 * @since 20170128
 */
trait TCC_Trait_ParseArgs {

	/**
	 *  parse args that have a corresponding property
	 *
	 * @since 20170128
	 * @param array $args required.
	 */
	protected function parse_args( $args ) {
		if ( ! $args ) return;
		foreach( $args as $prop => $value ) {
			if ( property_exists( $this, $prop ) ) {
				$this->{$prop} = $value;
			}
		}
	}

	/**
	 *  parse all args into either existing properties or create new public properties
	 *
	 * @since 20170128
	 */
	protected function parse_all_args( $args ) {
		if ( ! $args ) return;
		foreach( $args as $prop => $value ) {
			$this->{$prop} = $value;
		}
	}

}
