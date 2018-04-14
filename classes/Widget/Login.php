<?php

class TCC_Widget_Login extends TCC_Widget_Widget {

	public function __construct() {
		$this->title = esc_html__( 'Login', 'tcc-fluid' );
		$this->desc  = esc_html__( 'Fluidity Login form', 'tcc-fluid' );
		$this->slug  = 'tcc_login';
		parent::__construct();
		add_filter( 'widget_display_callback', array( $this, 'fluid_login_title' ), 10, 3 );
	}

	public function inner_widget( $args, $instance ) {
		fluid_login()->login_form();
#		wp_login_form();
	}

	public function fluid_login_title( $title, $widget, $id ) {
fluid()->log(
	'title:  ' . $title,
	'   id:  ' . $id
	$widget
);
		if ( ( $this->slug === $id ) && is_user_logged_in() ) {
			$instance['title'] = esc_html__( 'Logout', 'tcc-fluid' );
		}
	}


}
