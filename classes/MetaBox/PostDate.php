<?php

class TCC_MetaBox_PostDate extends TCC_MetaBox_MetaBox {

	protected $context = 'side';
	protected $nonce   = 'postdate_meta_box';
	protected $slug    = 'postdate_meta_box';

	public function __construct( $args = array() ) {
		$this->title = __( 'Displayed Post Date', 'tcc-fluid' );
		parent::__construct( $args );
	}

	public function postdate_meta_box( $post ) {
		
	}

}
