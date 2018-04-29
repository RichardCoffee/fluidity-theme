<?php

class TCC_MetaBox_PostDate extends TCC_MetaBox_MetaBox {

	protected $context  = 'side';
	protected $field    = 'postdate_display';
	protected $nonce    = 'postdate_meta_box';
	protected $priority = 'default';
	protected $radio    = null;  #  object of type TCC_Form_Field_Radio
	protected $slug     = 'postdate_meta_box';

	public function __construct( $args = array() ) {
		//  FIXME:  separate all text into filterable array
		$this->title = __( 'Displayed Post Date', 'tcc-fluid' );
		parent::__construct( $args );
	}

	public function admin_enqueue_scripts() { }

	protected function initialize_radio( $postID ) {
		$postdate = get_post_meta( $postID, $this->field, true );
		$current  = tcc_content( 'postdate', 'defaultpd' );
		$content  = new TCC_Options_Content;
		$layout   = $content->get_item( 'postdate' );
		$args = array(
			'default'     => 'defaultpd',
			'field_name'  => $this->field,
			'field_value' => ( $postdate ) ? $postdate : $layout['default'],
			'choices'     => array_merge(
				array(
					'defaultpd' => sprintf(
						__( 'Use theme default: %s', 'tcc-fluid' ),
						$layout['source'][ $current ]
					),
				),
				$layout['source']
			),
		);
		$this->radio = new TCC_Form_Field_Radio( $args );
fluid()->log('value:  '.$this->radio->field_value,$args);
	}

	public function show_meta_box( $post ) {
		$this->initialize_radio( $post->ID );
		wp_nonce_field( basename( __FILE__ ), $this->nonce ); ?>
		<div id="<?php e_esc_attr( $this->slug ); ?>">
			<?php $this->radio->radio(); ?>
		</div><?php
	}

	public function save_meta_box( $postID ) {
		if ( ! $this->pre_save_meta_box( $postID, basename( __FILE__ ) ) ) {
			return;
		}
		if ( ! empty( $_POST[ $this->field ] ) ) {
			$this->initialize_radio( $postID );
			$value = $this->radio->sanitize( $_POST[ $this->field ] );
			update_post_meta( $postID, $this->field, $value );
		}
	}

}
