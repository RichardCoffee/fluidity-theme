<?php
/**
 *  Modal base class
 *
 * @package Fluidity
 * @subpackage Modals
 * @since 20170421
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 */
/**
 *  check for wordpress
 */
defined( 'ABSPATH' ) || exit;
/**
 *  abstract class upon which all modals are based
 *
 * @since 20170421
 */
abstract class TCC_Modal_Bootstrap {

	/**
	 *  modal id
	 *
	 * @since 20180830
	 * @var string
	 */
	protected $id = '';
	/**
	 *  modal filter prefix
	 *
	 * @since 20170421
	 * @var strin
	 */
	protected $prefix = 'tcc';
	/**
	 *  modal size - 'modal-lg', 'modal-sm'
	 *
	 * @since 20170421
	 * @var string
	 */
	protected $size = 'modal-lg';
	/**
	 *  modal title
	 *
	 * @since 20170421
	 * @var string
	 */
	protected $title = 'Modal Title';

	/**
	 *  import attribute functions
	 *
	 * @since 20170512
	 */
	use TCC_Trait_Attributes;

	/**
	 *  abstract functions required for child classes
	 *
	 * @since 20170421
	 */
#	abstract protected function modal_header();
	abstract protected function modal_body();
	abstract protected function modal_footer();


	/**  Modal  **/

	/**
	 *  insert the modal in the DOM
	 *
	 * @since 20170421
	 * @uses TCC_Trait_Attributes::apply_attrs()
	 */
	public function modal() {
		$this->id = ( empty( $this->id ) ) ? $this->prefix . '-modal-main' : $this->id; ?>
		<div <?php $this->apply_attrs( $this->get_modal_attrs() ); ?>>
			<div <?php $this->apply_attrs( $this->get_modal_dialog_attrs() ); ?>>
				<div class="modal-content"><?php
					$this->generate_header();
					$this->generate_body();
					$this->generate_footer(); ?>
				</div>
			</div>
		</div><?php
	}

	/**
	 *  main modal div attributes
	 *
	 * @since 20170421
	 * @return array
	 */
	private function get_modal_attrs() {
		$attrs = array(
			'id'              => $this->id,
			'class'           => 'modal fade',
			'tabindex'        => '-1',
			'role'            => 'dialog',
			'aria-labelledby' => $this->id . '-title',
			'data-backdrop'   => 'true',  #  true/static
			'data-keyboard'   => 'true',  #  true/false
		);
		return $attrs; # apply_filters( "{$this->prefix}_modal_main_attrs", $attrs );
	}

	/**
	 *  secondary modal div attributes
	 *
	 * @since 20170421
	 * @return array
	 */
	private function get_modal_dialog_attrs() {
		$attrs = array(
			'class' => 'modal-dialog ' . $this->size,
			'role'  => 'document',
		);
		return $attrs; # apply_filters( "{$this->prefix}_modal_dialog_attrs", $attrs );
	}


	/**  Modal Header  **/

	/**
	 *  insert the modal header
	 *
	 * @since 20170421
	 * @uses TCC_Trait_Attributes::tag()
	 * @uses TCC_Trait_Attributes::element()
	 */
	private function generate_header() {
		$this->tag( 'div', $this->get_modal_header_attrs() );
			$this->tag( 'button', $this->get_modal_header_button_close_attrs() ); ?>
				<span aria-hidden="true">&times;</span>
			</button><?php
			$this->element( 'h4', [ 'id' => $this->id . '-title', 'class' => 'modal-title text-center' ], $this->title ); ?>
		</div><?php
	}

	/**
	 *  modal header div attributes
	 *
	 * @since 20170421
	 * @return array
	 */
	private function get_modal_header_attrs() {
		$attrs = array(
			'id'    => $this->id . '-header',
			'class' => 'modal-header',
		);
		return $attrs; # apply_filters( "{$this->prefix}_modal_header_attrs", $attrs );
	}

	/**
	 *  attributes for the close bi=utton in the header
	 *
	 * @since 20170421
	 * @return array
	 */
	private function get_modal_header_button_close_attrs() {
		$attrs = array(
			'type'         => 'button',
			'class'        => 'close',
			'data-dismiss' => 'modal',
			'aria-label'   => __( 'Close the dialog', 'tcc-fluid' ),
		);
		return $attrs; # apply_filters( "{$this->prefix}_modal_header_button_close_attrs", $attrs );
	}


	/**  Modal Body  **/

	/**
	 *  insert the modal body
	 *
	 * @since 20170421
	 * @uses TCC_Trait_Attributes::apply_attrs()
	 */
	private function generate_body() { ?>
		<div <?php $this->apply_attrs( $this->get_modal_body_attrs() ); ?>>
			<?php $this->modal_body(); ?>
		</div><?php
	}

	/**
	 *  attributes for the modal body div
	 *
	 * @since 20170421
	 * @uses WordPress::apply_filters()
	 * @return array
	 */
	private function get_modal_body_attrs() {
		$attrs = array(
			'id'    => $this->id . '-body',
			'class' => 'modal-body',
		);
		return apply_filters( "{$this->prefix}_modal_body_attrs_{$this->id}", $attrs );
	}


	/**  Modal Footer  **/

	/**
	 *  insert the modal footer
	 *
	 * @since 20170421
	 * @uses TCC_Trait_Attributes::apply_attrs()
	 */
	private function generate_footer() { ?>
		<div <?php $this->apply_attrs( $this->get_modal_footer_attrs() ); ?>>
			<?php $this->modal_footer(); ?>
		</div><?php
	}

	/**
	 *  attributes for the modal footer div
	 *
	 * @since 20170421
	 * @return array
	 */
	private function get_modal_footer_attrs() {
		$attrs = array(
			'id'    => $this->id . '-footer',
			'class' => 'modal-footer',
		);
		return $attrs; # apply_filters( "{$this->prefix}_modal_footer_attrs", $attrs );
	}

	/**
	 *  display the modal activation button
	 *
	 * @since 20180830
	 * @uses TCC_Trait_Attributes::element()
	 */
	public function button( $text = '' ) {
		$attrs = $this->get_modal_button_attributes();
		$this->element( 'button', $attrs, $this->get_button_text( $text ) );
	}

	/**
	 *  attributes for the modal activation button
	 *
	 * @since 20180830
	 * @return array
	 */
	protected function get_modal_button_attributes() {
		$attrs = array(
			'type'        => 'button',
			'class'       => 'btn btn-fluidity',
			'data-toggle' => 'modal',
			'data-target' => '#' . $this->id,
		);
		return $attrs; # apply_filters( "{$this->prefix}_modal_show_button_attrs", $attrs );
	}

	/**
	 *  text for the modal activation button
	 *
	 * @since 20180830
	 * @return string
	 */
	protected function get_button_text( $text = '' ) {
		return ( empty( $text ) ) ? __( 'Show Modal', 'tcc-fluid' ) : $text;
	}


}
