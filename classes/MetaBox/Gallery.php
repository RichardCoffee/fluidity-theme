<?php
/*
 *  File:  clasess/MetaBox/Gallery.php
 *
 */

defined( 'ABSPATH' ) || exit;

class TCC_MetaBox_Gallery {

	protected $add_meta =  null;
	protected $button   = 'Assign/Upload Image';
	protected $callback =  null;
	protected $confirm  = 'Remove this image?';
	protected $context  = 'normal';
	protected $div_css  = 'section group gallery-images';
	protected $div_id   = 'post-gallery';
	protected $div_img  = 'col span_1_of_4 meta-image';
	protected $field    = 'tcc_gallery';
	protected $icon     = 'dashicons dashicons-trash delete-image';
	protected $img_css  = 'attachment-post-thumbnail img-responsive';
	protected $m_button = 'Assign Image';
	protected $m_title  = 'Assign/Upload Image';
	protected $nonce    = 'gallery_nonce';
	protected $priority = 'high';
	protected $slug     = 'gallery_meta_box';
	protected $title    = 'Image Gallery';
	protected $type     = 'post';

	use TCC_Trait_Magic;
	use TCC_Trait_ParseArgs;

	public function __construct( $args = array() ) {
		$this->button   = esc_html__( 'Assign/Upload Gallery Image', 'tcc-fluid' );
		$this->confirm  = esc_html__( 'Remove this image?', 'tcc-fluid' );
		$this->m_button = esc_html__( 'Assign Image', 'tcc-fluid' );
		$this->m_title  = esc_html__( 'Assign/Upload Gallery Image', 'tcc-fluid' );
		$this->title    = esc_html__( 'Image Gallery', 'tcc-fluid' );
		$this->parse_all_args( $args );
		$this->add_meta = ( $this->add_meta ) ? $this->add_meta : "add_meta_boxes_{$this->type}";
		$this->div_id   = "{$this->type}-gallery";
		$this->nonce    = "{$this->type}_gallery_nonce";
		add_action( $this->add_meta,           array( $this, 'add_meta_boxes' ) );
		add_action( 'admin_enqueue_scripts',   array( $this, 'admin_enqueue_scripts' ), 11 );  #  run later
		add_action( "save_post_{$this->type}", array( $this, 'save_meta_boxes' ) );
	}

	public function add_meta_boxes() {
		add_meta_box( $this->slug, $this->title, array( $this, 'gallery_meta_box' ), $this->type, $this->context, $this->priority, $this->callback );
	}

	public function admin_enqueue_scripts() {
		$screen = get_current_screen();
		if ( $screen && ( $screen->post_type === $this->type ) ) {
			wp_enqueue_style( 'tcc-gallery-css' );
			wp_enqueue_style( 'tcc-columns' );  #  provides 'section group col span_*_of_*' classes
			$data = array( 'button'  => $this->m_button,
			               'confirm' => $this->confirm,
			               'div_img' => $this->div_img,
			               'icon'    => $this->icon,
			               'div_id'  => $this->div_id,
			               'img_css' => $this->img_css,
			               'field'   => $this->field . '[]',
			               'title'   => $this->m_title,
			             );
			wp_localize_script( 'tcc-gallery-js', 'tcc_gallery', $data );
			wp_enqueue_script( 'tcc-gallery-js' );

		}
	}

	public function gallery_meta_box( $post ) {
		wp_nonce_field( basename( __FILE__ ), $this->nonce );
		do_action( 'tcc_gallery_meta_box_pre' ); ?>
		<div id="<?php echo $this->div_id; ?>" class="<?php echo $this->div_css; ?>"><?php
			$images = $this->get_gallery_images( $post->ID, true );
			foreach( $images as $imgID => $src ) { ?>
				<div class="<?php echo $this->div_img; ?>">
					<span class="<?php echo $this->icon; ?>"></span>
					<img class="<?php echo $this->img_css; ?>" src="<?php echo $src; ?>" data-id="<?php echo $imgID ?>">
				</div><?php
			} ?>
		</div>
		<button id="add-<?php echo $this->div_id; ?>" type="button"><?php echo $this->button; ?></button><?php
	}

	#	http://www.wpbeginner.com/wp-themes/how-to-get-all-post-attachments-in-wordpress-except-for-featured-image/
	public function get_gallery_images( $postID, $exclude = false ) {
		$images = array();
		if ( $postID ) {
			$data = array(
				'post_type'      => 'attachment',
				'posts_per_page' => -1,
				'post_parent'    => $postID
			);
			if ( $exclude ) {
				$data['exclude'] = get_post_thumbnail_id( $postID );
			}
			$attachments = get_posts( $data );
			if ( $attachments ) {
				usort( $attachments, function( $a, $b ) {
					return ( intval( $a->ID, 10 ) - intval( $b->ID, 10 ) );
				});
				foreach ( $attachments as $attachment ) {
					$image_src = wp_get_attachment_image_src( $attachment->ID, 'full' );
					$images[ $attachment->ID ] = $image_src[0];
				}
			}
		}
		return $images;
	}

	public function save_meta_boxes( $postID ) {
		remove_action( "save_post_{$this->type}", array( $this, 'save_meta_boxes' ) ); # prevent recursion
		if ( ! isset( $_POST[ $this->nonce ] ) )          return;
		if ( ! current_user_can( 'edit_post', $postID ) ) return;
		if ( wp_is_post_autosave( $postID ) )             return;
		if ( wp_is_post_revision( $postID ) )             return;
		if ( ! wp_verify_nonce( $_POST[ $this->nonce ], basename(__FILE__) ) ) return;
		$incoming = $_POST;
		if ( ! empty( $incoming[ $this->field ] ) ) {
			foreach( $incoming[ $this->field ] as $imageID ) {
				$check = intval( $imageID, 10 );
				if ( $check ) {
					wp_update_post( array(
						'ID'          => $check,
						'post_parent' => $postID
					));
				}
			}
		}
		if ( ! empty( $incoming['delete_image'] ) ) {
			foreach( $incoming['delete_image'] as $deleteID ) {
				$check = intval( $deleteID, 10 );
				if ( $check ) { wp_delete_post( $check ); }
			}
		}
	}

}