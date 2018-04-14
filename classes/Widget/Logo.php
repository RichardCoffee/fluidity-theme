<?php

class TCC_Widget_Logo extends TCC_Widget_Widget {

  public function __construct() {
    $this->title = esc_html__('Logo','tcc-fluid');
    $this->desc  = esc_html__('Fluidity - Displays your site logo','tcc-fluid');
    $this->slug  = 'tcc_logo';
    parent::__construct();
  }

  public function inner_widget($args,$instance) {
    $logo = tcc_design('logo'); ?>
    <a href="<?php echo esc_url(home_url()); ?>/">
      <img itemprop="logo" class="img-responsive" src='<?php echo $logo; ?>' alt="<?php bloginfo('name'); ?>" title="<?php bloginfo('name'); ?>">
    </a><?php
  }

	public function form($instance) {
		if (isset($instance['title']) && ($instance['title']===$this->title)) { $instance['title'] = ''; }
		parent::form($instance);
	}

}
