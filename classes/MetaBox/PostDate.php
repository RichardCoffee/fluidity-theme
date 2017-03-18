<?php

class TCC_MetaBox_PostDate extends TCC_MetaBox_MetaBox {

	protected $context = 'side';
	protected $field   = 'postdate_display';
	protected $nonce   = 'postdate_meta_box';
	protected $radio   = null;  #  object of type TCC_Form_Field_Radio
	protected $slug    = 'postdate_meta_box';

	public function __construct( $args = array() ) {
		$this->title = __( 'Displayed Post Date', 'tcc-fluid' );
		parent::__construct( $args );
		$this->initialize_radio();
	}

	public function admin_enqueue_scripts() { }

	protected function initialize_radio( $postID ) {
		$postdate = get_post_meta( $postID, 'postdate_display', true );
		$default  = __( 'Use theme default: %s', 'tcc-fluid' );
		$current  = tcc_option( 'postdate' );
		$cur_text = TCC_Options_Fluidity::instance()->get_option_source_text( 'content', 'postdate', $current );
		$args = array(
			'field_default' => 'default',
			'field_name'    => $this->field,
			'field_value'   => $postdate,
			'field_radio'   => array(
				'default'  => sprintf( $default, $cur_text ),
				'original' => __( 'Always show published date', 'tcc-fluid' ),
				'modified' => __( 'Show modified date, when applicable', 'tcc-fluid' ),
				'none'     => __( 'Never show a date for this post.', 'tcc-fluid' ),
			),
		);
		$radio = new TCC_Form_Field_Radio( $args );
	}

	public function show_meta_box( $post ) {
		$this->initialize_radio( $post->ID );
		wp_nonce_field( basename( __FILE__ ), $this->nonce ); ?>
		<div id="<?php echo $this->slug; ?>">
			<?php $this->radio->radio(); ?>
		</div><?php
	}

	public function save_meta_box( $postID ) {
		if ( ! $this->pre_save_meta_box( $postID, basename( __FILE__ ) ) ) {
			return;
		}
		if ( ! empty( $_POST[ $this->field ] ) ) {
			$value = sanitize_title( $_POST[ $this->field ] );
			if ( array_key_exists( $value, $this->radio->field_radio ) ) {
				update_post_meta( $postID, $this->field, $value );
			}
		}
	}

}
