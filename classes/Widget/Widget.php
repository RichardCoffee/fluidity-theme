<?php

class TCC_Widget_Widget extends WP_Widget {

	protected $title = '';
	protected $desc  = '';
	protected $slug  = '';

	use TCC_Trait_Attributes;

	public function __construct( $slug = '', $title = '', $desc = array() ) {
		$args = array(
			'description' => $this->desc,
			'customize_selective_refresh' => true,
		);
		parent::__construct( $this->slug, $this->title, $args );
		# https://developer.wordpress.org/themes/customize-api/tools-for-improved-user-experience/
		# https://make.wordpress.org/core/2016/03/22/implementing-selective-refresh-support-for-widgets/
		if ( is_active_widget( false, false, $this->id_base ) || is_customize_preview() ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );
		}
	}

	public function wp_enqueue_scripts() { }

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		$this->fluid_widget_title( $args, $instance );
		$this->inner_widget( $args, $instance );
		echo $args['after_widget'];
	}

	protected function fluid_widget_title( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'], $this->id_base );
		echo $args['before_title'] . $title . $args['after_title'];
	}

	public function form( $instance ) {
		$instance['title'] = ( isset( $instance['title'] ) ) ? $instance['title'] : $this->title;
		$text = esc_html__( 'Title:', 'tcc-fluid' );
		$this->form_field( $instance, 'title', $text );
	}

	protected function form_field( $instance, $slug, $text ) { ?>
		<p><?php
			$this->element( 'label', [ 'for' => $this->get_field_id( $slug ) ], $text );
			$attrs = array(
				'type'  => 'text',
				'class' => 'widefat',
				'id'    => $this->get_field_id( $slug ),
				'name'  => $this->get_field_name( $slug ),
				'value' => ( empty( $instance[ $slug ] ) ) ? '' : esc_attr( $instance[ $slug ] )
			);
			$this->element( 'input', $attrs ); ?>
		</p><?php
	}

	protected function form_checkbox( $instance, $slug, $text ) {
		$value = ( empty( $instance[ $slug ] ) ) ? 'off' : $instance[ $slug ]; ?>
		<p>
			<label><?php
				$attrs = array(
					'type' => 'checkbox',
					'id'   => $this->get_field_id( $slug ),
					'name' => $this->get_field_name( $slug ),
				);
				$this->checked( $attrs, $value, 'on' );
				$this->element( 'input', $attrs ); ?>
				&nbsp;<span>
					 <?php esc_html( $text ); ?>
				</span>
			</label>
		</p><?php
	}

	protected function form_radio( $instance, $slug, $text ) {
fluid()->log( $instance, $slug, $text );
		$args = array(
			
		); ?>
		<p>
		</p><?php
	}

	public function update( $new, $old ) {
		$instance = $old;
		$instance['title'] = ( ! empty( $new['title'] ) ) ? wp_strip_all_tags( $new['title'] ) : '';
		return $instance;
	}

}
