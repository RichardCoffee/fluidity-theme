<?php
/**
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 */

class TCC_MetaBox_PostDate extends TCC_MetaBox_MetaBox {

	protected $context  = 'side';
	protected $field    = 'postdate_display';
	protected $excerpt  = array( 'field' => 'postdate_excerpt', 'object' => null );
	protected $nonce    = 'postdate_meta_box';
	protected $priority = 'default';
	protected $radio    = null;  #  object of type TCC_Form_Field_Radio
	protected $slug     = 'postdate_meta_box';

	public function __construct( $args = array() ) {
		$this->title = __( 'Displayed Post Date', 'tcc-fluid' );
		parent::__construct( $args );
	}

	public function admin_enqueue_scripts() { }

	protected function initialize_radio( $postID ) {
		$postdate = get_post_meta( $postID, $this->field, true );
		$layout   = fluid_customizer()->content_controls();
		$choices  = $layout['content']['controls']['postdate']['choices'];
		$default  = $layout['content']['controls']['postdate']['default'];
		$current  = get_theme_mod( 'content_postdate', $default );
		$args = array(
			'default'     => 'defaultpd',
			'field_name'  => $this->field,
			'field_value' => ( $postdate ) ? $postdate : 'defaultdp',
			'choices'     => array_merge(
				array(
					'defaultpd' => sprintf(
						__( 'Use theme default: %s', 'tcc-fluid' ),
						$choices[ $current ]
					),
				),
				$choices
			),
		);
		$this->radio  = new TCC_Form_Field_Radio( $args );
		$show_excerpt = in_array( get_theme_mod( 'content_exdate', 'show' ), [ 'postshow', 'posthide' ] );
		if ( $show_excerpt ) {
			$exdate = get_post_meta( $postID, $this->excerpt['field'], false );
			$ex_def = ( get_theme_mod( 'content_exdate', 'postshow' ) === 'posthide' ) ? 'hide' : 'show';
			$this->excerpt['header'] = $layout['content']['controls']['exdate']['label'];
			$this->excerpt['args']   = array(
				'default'     => $ex_def,
				'field_name'  => $this->excerpt['field'],
				'field_value' => ( $exdate ) ? $exdate : $ex_def,
				'choices'     => array(
					'show' => $layout['content']['controls']['exdate']['choices']['show'],
					'hide' => $layout['content']['controls']['exdate']['choices']['hide'],
				),
			);
			$this->excerpt['object'] = new TCC_Form_Field_Radio( $this->excerpt['args'] );
		}
	}

	public function show_meta_box( $post ) {
		$this->initialize_radio( $post->ID );
		wp_nonce_field( basename( __FILE__ ), $this->nonce ); ?>
		<div id="<?php e_esc_attr( $this->slug ); ?>"><?php
			$this->radio->radio();
			if ( $this->excerpt['object'] ) {
				$this->element( 'h3', [ ], $this->excerpt['header'] );
				$this->excerpt['object']->radio();
			} ?>
		</div><?php
	}

	public function save_meta_box( $postID ) {
		if ( ! $this->pre_save_meta_box( $postID, basename( __FILE__ ) ) ) {
			return;
		}
		if ( array_key_exists( $this->field, $_POST ) ) {
			$this->initialize_radio( $postID );
			$value = $this->radio->sanitize( $_POST[ $this->field ] );
			update_post_meta( $postID, $this->field, $value );
			if ( $this->excerpt['object'] && array_key_exists( $this->excerpt['field'], $_POST ) ) {
				$exdate = $this->excerpt['object']->sanitize( $_POST[ $this->excerpt['field'] ] );
				update_post_meta( $postID, $this->excerpt['field'], $exdate );
			}
		}
	}

}
