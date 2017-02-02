<?php


class TCC_Options_Privacy {

	private $base = 'privacy';
	private $sort = 550;

	public function __construct() {
		add_filter('fluidity_options_form_layout', array($this,'form_layout'),$this->sort);
	}

	public function form_layout($form) {
		$form[$this->base] = array('describe' => array($this,'title_description'),
		                           'title'    => __('Privacy','tcc-fluid'),
		                           'option'   => 'tcc_options_' . $this->base,
		                           'layout'   => $this->options_layout());
		return $form;
	}

	public function title_description() {
		_e('Control the information that WordPress gets from your site.  The default settings here duplicate what WordPress currently collects.','tcc-fluid');
	}

	public function options_layout() {
		$layout = array('default'=>true);

		$layout['blog'] = array('default' => 'yes',
		                        'label'   => __('Blog URL','tcc-fluid'),
		                        'render'  => 'radio',
		                        'source'  => array('yes' => __("Let WordPress know your site's url.",'tcc-fluid'),
		                                           'no'  => __('Do not let them know where you are.','tcc-fluid'))); //*/

		$layout['blogs'] = array('default' => 'yes',
		                         'label'   => __('Multi-Site','tcc-fluid'),
		                         'render'  => 'radio',
		                         'source'  => array('yes' => __("Yes - Let WordPress know if you are running a multi-site blog.",'tcc-fluid'),
		                                            'no'  => __("No -- Tell WordPress you are running just a single blog.",'tcc-fluid')),
		                         'change'  => 'showhidePosi(this,".privacy-install-url","yes");',
		                         'divcss'  => 'privacy-blogs'); //*/

		$layout['install'] = array('default' => 'yes',
		                           'label'   => __('Install URL','tcc-fluid'),
		                           'render'  => 'radio',
		                           'source'  => array('yes' => __("Let WordPress know the url you installed WordPress to.",'tcc-fluid'),
		                                              'no'  => __('Do not give WordPress this information.','tcc-fluid')),
		                           'divcss'  => 'privacy-install-url'); //*/

		$layout['users'] = array('default' => 'all',
		                         'label'   => __('Users','tcc-fluid'),
		                         'render'  => 'radio',
		                         'source'  => array('all'  => __('Accurately report to WordPress how many users you have.','tcc-fluid'),
		                                            'some' => __('Only let WordPress know that you have some users. ( actual users divided by 10 )','tcc-fluid'),
		                                            'one'  => __('Tell WordPress that you are the only user.','tcc-fluid'),
		                                            'many' => __('Just generate some random number to give WordPress.','tcc-fluid')));

		$layout['plugins'] = array('default' => 'all',
		                           'label'   => __('Plugins','tcc-fluid'),
		                           'render'  => 'radio',
		                           'source'  => array('all'    => __("Let WordPress know what plugins you have installed.",'tcc-fluid'),
		                                              'filter' => __('Filter the plugin list that gets sent to WordPress.','tcc-fluid'),
		                                              'none'   => __('Do not let them know about your plugins.','tcc-fluid')),
		                           'change'  => 'showhidePosi(this,".privacy-plugin-filter","filter");',
		                           'divcss'  => 'privacy-plugin-option'); //*/

		$layout['plugin_list'] = array('default' => array(),
		                               'label'   => __('Plugin List','tcc-fluid'),
		                               'render'  => 'checkbox_multiple',
		                               'source'  => $this->get_plugin_list(),
		                               'divcss'  => 'privacy-plugin-filter'); //*/

/*		$layout['themes'] = array('default' => 'all',
		                          'label'   => __('Themes','tcc-fluid'),
		                          'render'  => 'radio',
		                          'source'  => array('all'    => __("Let WordPress know what themes you have installed.",'tcc-fluid'),
		                                             'filter' => __('Filter the theme list that gets sent to WordPress.','tcc-fluid'),
		                                             'none'   => __('Do not let them know about your themes.','tcc-fluid')),
		                          'change'  => 'showhidePosi(this,".privacy-theme-filter","filter");',
		                          'divcss'  => 'privacy-theme-option'); //*/



/*
$plugins = get_plugins();
$themes  = wp_get_themes();
log_entry($plugins,$themes); //*/

    $layout = apply_filters("tcc_{$this->base}_options_layout",$layout);
    return $layout;
  }

	private function get_plugin_list() {
		$plugins = get_plugins();
		$plugin_list  = array();
		foreach ( $plugins as $path => $plugin ) {
			$index = ( empty( $plugin['TextDomain'] ) ) ? basename( dirname( $path ) ) : $plugin['TextDomain'];
			$title = '<a href="' . esc_attr( $plugin['PluginURI'] ) . '" target="' . esc_attr( $index ) . '">';
			$title.= esc_html( $plugin['Name'] ) . '</a> by ';
			$title.= '<a href="' . esc_attr( $plugin['AuthorURI'] ) . '" target="' . sanitize_title( $plugin['Author'] ) . '">';
			$title.= esc_html( $plugin['Author'] ) . '</a>';
			$plugin_list[$index] = $title;
		}
		return $plugin_list;
	}

}
