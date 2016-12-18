<?php

class TCC_Options_Parallax {

  private $base     = 'parallax';
  private $priority = 34; # customizer priority

  public function __construct() {
    add_filter('fluidity_options_form_layout', array($this,'form_layout'),10);
    add_action('fluid-customizer', array($this,'options_customize_register'),$this->priority,2);
  }

  private function form_title() {
    return __('Parallax','tcc-fluid');
  }

  public function form_layout($form) {
    $form[$this->base] = array('describe' => array($this,'describe_options'),
                               'title'    => $this->form_title(),
                               'option'   => 'tcc_options_'.$this->base,
                               'layout'   => $this->options_layout());
    return $form;
  }

  public function describe_options() { ?>
		<span>
			<?php esc_html_e('Parallax Options - assign background images for parallax effects.','tcc-fluid'); ?>
		</span><?php
  }

  protected function options_layout() {
    $media  = array('title'  => __('Assign/Upload Image','tcc-fluid'),
                    'button' => __('Assign Image', 'tcc-fluid'),
                    'delete' => __('Remove Image', 'tcc-fluid'));
    $layout = array('default'=>true);
    $layout['front']  = array('default' => '',
                              'label'   => __('Front Page','tcc-fluid'),
                              'render'  => 'image',
                              'divcss'  => 'upload-img',
                              'media'   => $media);
/*
    $layout['home']   = array('default' => '',
                              'label'   => __('Home/Blog','tcc-fluid'),
                              'render'  => 'image',
                              'divcss'  => 'upload-img',
                              'media'   => $media);//*/
    $layout = apply_filters("tcc_{$this->base}_options_layout",$layout);
    return $layout;
  }

  public function options_customize_register($wp_customize, TCC_Options_Fluidity $form) {
    $wp_customize->add_section( 'fluid_'.$this->base, array('title' => $this->form_title(), 'priority' => $this->priority));
    $form->customizer_settings($wp_customize,$this->base);
  }


}
