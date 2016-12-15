<?php



class TCC_Options_Layout {

  private $base     = 'layout';
  private $priority = 35; # customizer priority

  public function __construct() {
    add_filter('fluidity_options_form_layout', array($this,'form_layout'),10);
    add_action('fluid-customizer', array($this,'options_customize_register'),$this->priority,2);
  }

  private function form_title() {
    return __('Layout','tcc-fluid');
  }

  public function form_layout($form) {
    $form[$this->base] = array('describe' => array($this,'describe_options'),
                               'title'    => $this->form_title(),
                               'option'   => 'tcc_options_'.$this->base,
                               'layout'   => $this->options_layout());
    return $form;
  }

  public function describe_options() {
    _e("Utilize these options to change the theme's style and layout.  These options also show up in the WordPress Customizer.",'tcc-fluid');
  }

  protected function options_layout() {
    $layout = array('default'=>true);
    $layout['width']   = array('default' => 'full',
                               'label'   => __('Width','tcc-fluid'),
                               'text'    => __('How much screen real estate do you want the theme to use?','tcc-fluid'),
                               'help'    => __('This determines the margins for the main body of the website','tcc-fluid'),
                               'render'  => 'radio',
                               'source'  => array('fluid'  => __('Full Width (small margins)','tcc-fluid'),
                                                  'narrow' => __('Standard Margins','tcc-fluid')));
    $layout['header']  = array('default' => 'static',
                               'label'   => __('Header','tcc-fluid'),
                               'text'    => __('How do you want the header to behave?','tcc-fluid'),
                               'render'  =>'radio',
                               'source'  => array('static' => __('Static - Simple standard layout','tcc-fluid'),
                                                  'fixed'  => __('Fixed - Stays at top of screen when scrolling','tcc-fluid'),
                                                  'reduce' => __('Reducing - Gets smaller when scrolling down','tcc-fluid'),
                                                  'hide'   => __('Hiding - Hidden when scrolling, show on hover','tcc-fluid')));
#    if (!file_exists(get_stylesheet_directory().'/template-parts/header-reduce.php') && !file_exists(get_template_directory().'/template-parts/header-reduce.php')) {
#        unset($layout['header']['source']['reduce']); }
    $layout['sidebar'] = array('default' => 'left',
                               'label'   => __('Sidebar','tcc-fluid'),
                               'text'    => __('Which side of the screen should the sidebar show up on?'),
                               'render'  => 'radio',
                               'source'  => array('none'  => __('No Sidebar','tcc-fluid'),
                                                  'left'  => __('Left side','tcc-fluid'),
                                                  'right' => __('Right side','tcc-fluid')));
    $layout['widget'] = array('default' => 'open',
                              'label'   => __('Widgets','tcc-fluid'),
                              'text'    => __('Should the sidebar widgets start open or closed, where applicable','tcc-fluid'),
                              'render'  => 'radio',
                              'source'  =>array('open'   => __('Open','tcc-fluid'),
                                                'closed' => __('Closed','tcc-fluid'),
                                                'perm'   => __('Do not provide option to users','tcc-fluid')));
    $layout['content'] = array('default' => 'content',
                               'label'   => __('Content','tcc-fluid'),
                               'text'    => __('Show full post content or just an excerpt on archive/category/search pages','tcc-fluid'),
                               'render'  => 'radio',
                               'source'  =>array('content' => __('Content','tcc-fluid'),
                                                 'excerpt' => __('Excerpt','tcc-fluid')));
    $layout['exdate']  = array('default' => 'show',
                               'label'   => __('Excerpt Date','tcc-fluid'),
                               'text'    => __('Should the date be displayed with excerpt?','tcc-fluid'),
                               'render'  => 'radio',
                               'source'  =>array('none' => __('No Date','tcc-fluid'),
                                                 'show' => __('Show Date','tcc-fluid')));
    $layout = apply_filters("tcc_{$this->base}_options_layout",$layout);
    return $layout;
  }

  public function options_customize_register($wp_customize, TCC_Options_Fluidity $form) {
    $wp_customize->add_section( 'fluid_'.$this->base, array('title' => $this->form_title(), 'priority' => $this->priority));
    $form->customizer_settings($wp_customize,$this->base);
  }


}
