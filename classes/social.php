<?php


class Theme_Social_Icons {

  public function __construct() { # Fluidity_Options_Form $form) {
    add_filter('fluidity_options_form_layout', array($this,'form_layout'),100);
    add_action('admin_enqueue_scripts',array($this,'admin_enqueue_scripts'));
  }

  public function form_layout($form) {
    $form['social'] = array('describe' => array($this,'describe_social'),
                            'title'    => __('Social Icons','tcc-fluid'),
                            'option'   => 'tcc_options_social',
                            'layout'   => $this->social_layout());
    return $form;
  }

  public function describe_social() {
    _e('Fluidity Social Icons (powered by Font Awesome)','tcc-fluid');
  }

  protected static function social_layout() {
    $layout = array('default'=>true);
    $layout['active'] = array('default' => 'no',
                              'label'   => __('Use Theme Icons','tcc-fluid'),
                              'help'    => __('Contact us if you need help with a third-party plugin','tcc-fluid'),
                              'render'  => 'radio',
                              'source'  => array('yes' => __("Yes - you want to use the theme's internal social icons",'tcc-fluid'),
                                                 'no'  => __("No -- you are using a plugin, or do not want social icons",'tcc-fluid')),
                              'change'  => 'showhideSocialIcons();',
                              'divcss'  => 'social-option-active');
    $icons = array('Bitbucket','Facebook','GitHub','Google Plus','LinkedIN','Pinterest','RSS','Tumblr','Twitter','Xing','YouTube');
    foreach($icons as $icon) {
      $key = sanitize_title($icon);
      $layout[$key] = array('default' => '',
                            'label'   => $icon,
                            'help'    => __('Your link information goes here','tcc-fluid'),
                            'render'  => 'text',
                            'divcss'  => 'social-option-icon');
    }
    return $layout;
  }

  public function admin_enqueue_scripts() {
    wp_register_script('tcc-social-icons.js', get_template_directory_uri()."/js/options.js", array('jquery'), false, true);
    wp_enqueue_script('tcc-social-icons.js');
  }


}
