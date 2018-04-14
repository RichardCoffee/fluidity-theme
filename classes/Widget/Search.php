<?php

class TCC_Widget_Search extends TCC_Widget_Widget {

	function __construct() {
		$this->title = esc_html__( 'Search', 'tcc-fluid' );
		$this->desc  = esc_html__( 'Fluidity Search Form', 'tcc-fluid' );
		$this->slug  = 'tcc_search';
		parent::__construct();
		unregister_widget( 'WP_Widget_Search' );
	}

	public function inner_widget( $args, $instance ) {
		get_search_form();
	}

	protected function fluid_widget_title( $args, $instance ) {
		if ( ! empty( $args['before_title'] ) ) {
			echo "<div>";
		}
	}

}
