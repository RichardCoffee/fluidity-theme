<?php

class TCC_Widget_Login extends TCC_Widget_Widget {

	protected $id_base = 'fluid_login';

	public function __construct() {
		$this->title = esc_html__( 'Login', 'tcc-fluid' );
		$this->desc  = esc_html__( 'Fluidity Login form', 'tcc-fluid' );
		$this->slug  = $this->id_base; // TODO: DEPRECATE
		parent::__construct();
		add_filter( 'widget_display_callback', array( $this, 'fluid_login_title' ), 10, 3 );
	}

	public function inner_widget( $args, $instance ) {
		fluid_login()->login_form();
#		wp_login_form();
	}

	public function fluid_login_title( $instance, $widget, $args ) {
		if ( ( $this->id_base === $widget->id_base ) && is_user_logged_in() ) {
fluid()->log(
	$instance,
	$widget,
	$args
);
			$instance['title'] = esc_html__( 'Logout', 'tcc-fluid' );
		}
		return $instance;
	}


}
