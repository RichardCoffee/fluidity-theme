<?php

class TCC_Widgets_Logo extends TCC_Widgets_Basic {

  function __construct() {
    $this->title = esc_html__('Logo','tcc-fluid');
    $this->desc  = esc_html__('Fluidity - Displays your site logo','tcc-fluid');
    $this->slug  = 'tcc_logo';
    parent::__construct();
  }

  public function inner_widget($args,$instance) {
    $logo = tcc_design('logo'); ?>
    <a href="<?php self::$micro->bloginfo('url'); ?>/">
      <img itemprop="logo" class="img-responsive" src='<?php echo $logo; ?>' alt="<?php bloginfo('name'); ?>" title="<?php bloginfo('name'); ?>">
    </a><?php
  }

}
