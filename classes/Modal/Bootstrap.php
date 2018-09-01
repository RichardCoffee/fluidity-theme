<?php

abstract class TCC_Modal_Bootstrap {


	protected $id     = '';
	protected $prefix = 'tcc';  #  used as filter prefix
	protected $size   = 'modal-lg';
	protected $title  = 'Modal Title';

	use TCC_Trait_Attributes;


#	abstract protected function modal_header();
	abstract protected function modal_body();
	abstract protected function modal_footer();


	/**  Modal  **/

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

	private function get_modal_attrs() {
		$attrs = array(
			'id'              => $this->id,
			'class'           => 'modal fade',
			'tabindex'        => '-1',
			'role'            => 'dialog',
			'aria-labelledby' => $this->prefix . '-title',
			'data-backdrop'   => 'true',  #  true/static
			'data-keyboard'   => 'true',  #  true/false
		);
		return $attrs; # apply_filters( "{$this->prefix}_modal_main_attrs", $attrs );
	}

	private function get_modal_dialog_attrs() {
		$attrs = array(
			'class' => 'modal-dialog ' . $this->size,
			'role'  => 'document',
		);
		return $attrs; # apply_filters( "{$this->prefix}_modal_dialog_attrs", $attrs );
	}


	/**  Modal Header  **/

	private function generate_header() {
		$this->element( 'div', $this->get_modal_header_attrs() );
			$this->element( 'button', $this->get_modal_header_button_close_attrs() ); ?>
				<span aria-hidden="true">&times;</span>
			</button><?php
			$this->element( 'h4', [ 'id' => $this->prefix . '-title', 'class' => 'modal-title text-center' ], $this->title ); ?>
		</div><?php
	}

	private function get_modal_header_attrs() {
		$attrs = array(
			'id'    => $this->prefix . '-header',
			'class' => 'modal-header',
		);
		return $attrs; # apply_filters( "{$this->prefix}_modal_header_attrs", $attrs );
	}

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

	private function generate_body() { ?>
		<div <?php $this->apply_attrs( $this->get_modal_body_attrs() ); ?>>
			<?php $this->modal_body(); ?>
		</div><?php
	}

	private function get_modal_body_attrs() {
		$attrs = array(
			'id'    => $this->prefix . '-body',
			'class' => 'modal-body',
		);
		return apply_filters( "{$this->prefix}_modal_body_attrs_{$this->id}", $attrs );
	}


	/**  Modal Footer  **/

	private function generate_footer() { ?>
		<div <?php $this->apply_attrs( $this->get_modal_footer_attrs() ); ?>>
			<?php $this->modal_footer(); ?>
		</div><?php
	}

	private function get_modal_footer_attrs() {
		$attrs = array(
			'id'    => $this->prefix . '-footer',
			'class' => 'modal-footer',
		);
		return $attrs; # apply_filters( "{$this->prefix}_modal_footer_attrs", $attrs );
	}

	public function button( $text = '' ) {
		$attrs = $this->get_modal_button_attributes();
		fluid()->element( 'button', $attrs, $this->get_button_text( $text ) );
	}

	protected function get_modal_button_attributes() {
		$attrs = array(
			'type'        => 'button',
			'class'       => 'btn btn-fluidity',
			'data-toggle' => 'modal',
			'data-target' => $this->id,
		);
		return $attrs; # apply_filters( "{$this->prefix}_modal_show_button_attrs", $attrs );
	}

	protected function get_button_text( $text = '' ) {
		return ( empty( $text ) ) ? __( 'Show Modal', 'tcc-fluid' ) : $text;
	}


}
