<?php

class TCC_MetaBox_Sidebar extends TCC_MetaBox_MetaBox {

	protected $context  = 'side';
	protected $field    = 'post_display_sidebar';
	protected $nonce    = 'sidebar_for_post';
	protected $priority = 'default';
	protected $radio    = null;  #  object of type TCC_Form_Field_Radio
	protected $slug     = 'show_sidebar_for_post';


	public function __construct( $args = array() ) {
		$this->title = __( 'Show/Hide Sidebar', 'tcc-fluid' );
		parent::__construct( $args );
	}

	public function admin_enqueue_scripts() { }

	protected function initialize_radio( $postID ) {
		$current = get_post_meta( $postID, $this->field, true );
		$args = array(
			'default'     => 'show',
			'field_name'  => $this->field,
			'field_value' => ( $current ) ? $current : 'show',
			'choices'     => array(
				'show' => __( 'Show sidebar when displaying this post.', 'tcc-fluid' ),
				'hide' => __( 'Hide sidebar when displaying this post.', 'tcc-fluid' ),
			),
		);
		$this->radio = new TCC_Form_Field_Radio( $args );
	}

	public function show_meta_box( $post ) {
		$this->initialize_radio( $post->ID );
		wp_nonce_field( basename( __FILE__ ), $this->nonce ); ?>
		<div id="<?php e_esc_attr( $this->slug ); ?>"><?php
			$this->radio->radio(); ?>
		</div><?php
	}

	public function save_meta_box( $postID ) {
		if ( ! $this->pre_save_meta_box( $postID, basename( __FILE__ ) ) ) {
			return;
		}
		if ( ! empty( $_POST[ $this->field ] ) ) {
			$this->initialize_radio( $postID );
			$value = $this->radio->sanitize( wp_unslash( $_POST[ $this->field ] ) );
			update_post_meta( $postID, $this->field, $value );
		}
	}

}
