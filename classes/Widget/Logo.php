<?php

class TCC_Widget_Logo extends TCC_Widget_Widget {

  public function __construct() {
    $this->title = esc_html__('Logo','tcc-fluid');
    $this->desc  = esc_html__('Fluidity - Displays your site logo','tcc-fluid');
    $this->slug  = 'tcc_logo';
    parent::__construct();
  }

	public function inner_widget($args,$instance) {
		fluid_header_logo();
	}

	public function form($instance) {
		if (isset($instance['title']) && ($instance['title']===$this->title)) { $instance['title'] = ''; }
		parent::form($instance);
	}

}
