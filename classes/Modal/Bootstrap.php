<?php

abstract class TCC_Modal_Bootstrap {


	protected $base   = 'modal';
	protected $prefix = 'tcc';  #  used as filter prefix
	protected $size   = 'modal-lg';
	protected $title  = 'Modal Title';

	use TCC_Trait_Attributes;


#	abstract protected function modal_header();
	abstract protected function modal_body();
	abstract protected function modal_footer();


	/**  Modal  **/

	public function generate_modal() { ?>
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
			'id'              => $this->base,
			'class'           => 'modal fade',
			'tabindex'        => '-1',
			'role'            => 'dialog',
			'aria-labelledby' => $this->base . '-title',
			'data-backdrop'   => 'true',  #  true/static
			'data-keyboard'   => 'true',  #  true/false
		);
		return apply_filters( "{$this->prefix}_modal_attrs_{$this->base}", $attrs );
	}

	private function get_modal_dialog_attrs() {
		$dialog_attrs = array(
			'class' => 'modal-dialog ' . $this->size,
			'role'  => 'document',
		);
		return apply_filters( "{$this->prefix}_modal_dialog_attrs_{$this->base}", $attrs );
	}


	/**  Modal Header  **/

	private function generate_header() { ?>
		<div <?php $this->apply_attrs( $this->get_modal_header_attrs() ); ?>>
			<button <?php $this->apply_attrs( $this->get_modal_header_button_close_attrs() ) ?>>
				<span aria-hidden="true">&times;</span>
			</button>
			<h4 id="<?php echo $this->base; ?>-title" class="modal-title text-center">
				<?php echo $this->title; ?>
			</h4>
		</div><?php
	}

	private function get_modal_header_attrs() {
		$attrs = array(
			'id'    => $this->base . '-header',
			'class' => 'modal-header',
		);
		return apply_filters( "{$this->prefix}_modal_header_attrs_{$this->base}", $attrs );
	}

	private function get_modal_header_button_close_attrs() {
		$attrs = array(
			'type'         => 'button',
			'class'        => 'close',
			'data-dismiss' => 'modal',
			'aria-label'   => __( 'Close the dialog', 'tcc-fluid' ),
		);
		return apply_filters( "{$this->prefix}_modal_header_button_close_attrs_{$this->base}", $attrs );
	}


	/**  Modal Body  **/

	private function generate_body() { ?>
		<div <?php $this->apply_attrs( $this->get_modal_body_attrs() ); ?>>
			<?php $this->modal_body(); ?>
		</div><?php
	}

	private function get_modal_body_attrs() {
		$attrs = array(
			'id'    => $this->base . '-body',
			'class' => 'modal-body',
		);
		return apply_filters( "{$this->prefix}_modal_body_attrs_{$this->base}", $attrs );
	}


	/**  Modal Footer  **/

	private function generate_footer() { ?>
		<div <?php $this->apply_attrs( $this->get_modal_footer_attrs() ); ?>>
			<?php $this->modal_footer(); ?>
		</div><?php
	}

	private function get_modal_footer_attrs() {
		$attrs = array(
			'id'    => $this->base . '-footer',
			'class' => 'modal-footer',
		);
		return apply_filters( "{$this->prefix}_modal_footer_attrs_{$this->base}", $attrs );
	}


}
