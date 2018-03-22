<?php

abstract class TCC_Modal_Bootstrap {


	protected $prefix = 'tcc';  #  used as filter prefix
	protected $size   = 'modal-lg';
	protected $title  = 'Modal Title';

	use TCC_Trait_Attributes;


#	abstract protected function modal_header();
	abstract protected function modal_body();
	abstract protected function modal_footer();


	/**  Modal  **/

	public function modal() { ?>
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
			'id'              => $this->prefix . '-modal-main',
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
		$dialog_attrs = array(
			'class' => 'modal-dialog ' . $this->size,
			'role'  => 'document',
		);
		return $attrs; # apply_filters( "{$this->prefix}_modal_dialog_attrs", $attrs );
	}


	/**  Modal Header  **/

	private function generate_header() { ?>
		<div <?php $this->apply_attrs( $this->get_modal_header_attrs() ); ?>>
			<button <?php $this->apply_attrs( $this->get_modal_header_button_close_attrs() ) ?>>
				<span aria-hidden="true">&times;</span>
			</button>
			<h4 id="<?php echo $this->prefix; ?>-title" class="modal-title text-center">
				<?php echo $this->title; ?>
			</h4>
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
		return $attrs; # apply_filters( "{$this->prefix}_modal_body_attrs", $attrs );
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


}
