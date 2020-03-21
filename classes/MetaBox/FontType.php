<?php
/**
 *  Assign Font to post.
 *
 * @package Fluidity
 * @subpackage MetaBox
 * @since 20190604
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2019, Richard Coffee
 * @link https://github.com/RichardCoffee/custom-post-type/blob/master/classes/MetaBox/FontType.php
 */
defined( 'ABSPATH' ) || exit;
/**
 *  Allows the post author to assign a chosen font to be used or a post.
 */
class TCC_MetaBox_FontType extends TCC_MetaBox_MetaBox {

	/**
	 * @since 20190604
	 * @var string Indicates where the metabox should appear.
	 */
	protected $context = 'side';
	/**
	 * @since 20190604
	 * @var string The meta post data field name.
	 */
	protected $field = 'post_font_type';
	/**
	 * @since 20190604
	 * @var string The form security nonce.
	 */
	protected $nonce = 'fonttype_meta_box';
	/**
	 * @since 20190604
	 * @var string Indicates the relative priority of the metabox.
	 */
	protected $priority = 'default';
	/**
	 * @since 20190604
	 * @var TCC_Form_Field_Select
	 */
	protected $select = null;
	/**
	 * @since 20190605
	 * @var string Metabox slug.
	 */
	protected $slug = 'post_font_type';

	/**
	 *  Constructor function, set title and call parent constructor
	 *
	 * @since 20190605
	 * @param array contains data needed to for select
	 */
	public function __construct( $args = array() ) {
		$this->title = __( 'Select Font for Post', 'tcc-fluid' );
		parent::__construct( $args );
	}

	public function admin_enqueue_scripts() { }

	protected function initialize_select( $postID ) {
		$font    = get_theme_mod( "font_typography", 'Arial' );
		$current = get_post_meta( $postID, $this->field, $font );
		$choices = TCC_Theme_Typography::mixed_fonts();
		$args = array(
			'default'     => 'show',
			'field_name'  => $this->field,
			'field_value' => ( $current ) ? $current : 'show',
			'choices'     => array_merge(
				array(
					'default' => sprintf(
						__( 'Use theme default: %s', 'tcc-fluid' ),
						$font
					),
				),
				$choices
			),
		);
		$this->select = new TCC_Form_Field_Select( $args );
	}

	public function show_meta_box( $post ) {
		$this->initialize_select( $post->ID );
		wp_nonce_field( basename( __FILE__ ), $this->nonce ); ?>
		<div id="<?php e_esc_attr( $this->slug ); ?>"><?php
			$this->select->select(); ?>
		</div><?php
	}

	public function save_meta_box( $postID ) {
		if ( ! $this->pre_save_meta_box( $postID, basename( __FILE__ ) ) ) {
			return;
		}
		if ( array_key_exists( $this->field, $_POST ) ) {
			$this->initialize_select( $postID );
			$value = $this->select->sanitize( wp_unslash( $_POST[ $this->field ] ) );
			if ( $value === 'default' ) {
				delete_post_meta( $postID, $this->field );
			} else {
				update_post_meta( $postID, $this->field, $value );
			}
		}
	}


}
