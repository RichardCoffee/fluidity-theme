<?php


class TCC_Options_Privacy {

	private $base     = 'privacy';
	private $priority = 550;  #  internal theme option
	private $plugins  = array();
	private $themes   = array();

	public function __construct() {
		#	https://codex.wordpress.org/Function_Reference/get_plugins
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$this->plugins = get_plugins();
		$this->themes  = wp_get_themes();
		add_filter( 'fluidity_options_form_layout', array( $this, 'form_layout' ), $this->priority );
	}

	public function form_layout($form) {
		$form[$this->base] = array(
			'describe' => array( $this, 'title_description' ),
			'title'    => __( 'Privacy', 'tcc-fluid' ),
			'option'   => 'tcc_options_' . $this->base,
			'layout'   => $this->options_layout()
		);
		return $form;
	}

	public function title_description() {
		_e( 'Control the information that WordPress collects from your site.  The default settings here duplicate what WordPress currently collects.', 'tcc-fluid' );
	}

	public function options_layout() {
		$warning = __( '*** Turning any of these off means you will not be notified of upgrades! ***', 'tcc-fluid' );
		$layout  = array( 'default' => true );
		$layout['blog'] = array(
			'default' => 'yes',
			'label'   => __( 'Blog URL', 'tcc-fluid' ),
			'render'  => 'radio',
			'source'  => array(
				'yes'  => __( "Let WordPress know your site's url.", 'tcc-fluid' ),
				'no'   => __( 'Do not let them know where you are.', 'tcc-fluid' ),
			),
			'change'  => 'showhidePosi( this, ".privacy-blog-option", "yes" );',
			'divcss'  => 'privacy-blog-active'
		); //*/
#		if ( is_multisite() && is_main_site() ) {
			$layout['blogs'] = array(
				'default' => 'yes',
				'label'   => __( 'Multi-Site', 'tcc-fluid' ),
				'render'  => 'radio',
				'source'  => array(
					'yes'  => __( "Yes - Let WordPress know if you are running a multi-site blog.", 'tcc-fluid' ),
					'no'   => __( "No -- Tell WordPress you are running just a single blog.", 'tcc-fluid' ),
				),
				'change'  => 'showhidePosi( this, ".privacy-multi-option", "yes" );',
				'divcss'  => 'privacy-multi-active privacy-blog-option',
			); //*/
			$layout['install'] = array(
				'default' => 'yes',
				'label'   => __( 'Install URL', 'tcc-fluid' ),
				'render'  => 'radio',
				'source'  => array(
					'yes'  => __( 'Let WordPress know the url you installed WordPress to.', 'tcc-fluid' ),
					'no'   => __( 'Do not give WordPress this information.', 'tcc-fluid' ),
				),
				'divcss'  => 'privacy-blog-option privacy-multi-option',
			); //*/
#		}
		$layout['users'] = array(
			'default' => 'all',
			'label'   => __( 'Users', 'tcc-fluid' ),
			'render'  => 'radio',
			'source'  => array(
				'all'  => __( 'Accurately report to WordPress how many users you have.', 'tcc-fluid' ),
				'some' => __( 'Only let WordPress know that you have some users. ( actual users divided by 10 )', 'tcc-fluid' ),
				'one'  => __( 'Tell WordPress that you are the only user.', 'tcc-fluid' ),
				'many' => __( 'Just generate some random number to give WordPress.', 'tcc-fluid' ),
			),
		);
		$layout['plugins'] = array(
			'default'   => 'all',
			'label'     => __( 'Plugins', 'tcc-fluid' ),
			'render'    => 'radio',
			'source'    => array(
				'all'    => __( 'Let WordPress know what plugins you have installed.', 'tcc-fluid' ),
				'active' => __( 'Only report active plugins.', 'tcc-fluid' ),
				'filter' => __( 'Filter the plugin list that gets sent to WordPress.', 'tcc-fluid' ),
				'none'   => __( 'Do not let them know about your plugins.', 'tcc-fluid' ),
			),
			'change'    => 'showhidePosi( this, ".privacy-plugin-filter", "filter" );',
			'divcss'    => 'privacy-plugin-action',
		); //*/
		$layout['plugin_list'] = array(
			'default' => $this->get_plugin_defaults( 'yes' ),
			'preset'  => 'yes',
			'label'   => __( 'Plugin List', 'tcc-fluid' ),
			'text'    => $warning,
			'textcss' => 'red', // FIXME: bad css
			'render'  => 'radio_multiple',
			'source'  => $this->get_plugin_list(),
			'divcss'  => 'privacy-plugin-filter',
		); //*/
		$layout['themes'] = array(
			'default' => 'all',
			'label'   => __( 'Themes', 'tcc-fluid' ),
			'render'  => 'radio',
			'source'  => array(
				'all'    => __( 'Let WordPress know what themes you have installed.', 'tcc-fluid' ),
				'active' => __( 'Only let them know about your active theme.', 'tcc-fluid' ),
				'filter' => __( 'Filter the theme list that gets sent to WordPress.', 'tcc-fluid' ),
				'none'   => __( 'Do not let them know about your themes.', 'tcc-fluid' ),
			),
			'change'  => 'showhidePosi(this,".privacy-theme-filter","filter");',
			'divcss'  => 'privacy-theme-action',
		); //*/
		$layout['theme_list'] = array(
			'default' => $this->get_theme_defaults( 'yes' ),
			'preset'  => 'yes',
			'label'   => __( 'Theme List', 'tcc-fluid' ),
			'text'    => $warning,
			'textcss' => 'red', // FIXME: bad css
			'render'  => 'radio_multiple',
			'titles'  => array(
				__( 'On', 'tcc-fluid' ),
				__( 'Off', 'tcc-fluid' ),
				__( 'Description', 'tcc-fluid' ),
			),
			'source'  => $this->get_theme_list(),
			'divcss'  => 'privacy-theme-filter',
		); //*/
		$layout = apply_filters( "tcc_{$this->base}_options_layout", $layout );
		return $layout;
	}


	/**  Plugin functions  **/

	private function get_plugin_defaults( $preset ) {
		#	Start with a clean slate
		$options = $this->clean_plugin_defaults();
		#	Load missing items with the default value
		foreach( $this->plugins as $path => $plugin ) {
			if ( ! isset( $options[ $path ] ) ) {
				$options[ $path ] = $preset;
			}
		}
		return $options;
	}

	#	Removes deleted plugins by generating a new list
	private function clean_plugin_defaults() {
		#	The beginning
		$options = array();
		$current = tcc_privacy( 'plugin_list' );
		if ( $current ) {
			foreach( $current as $key => $status ) {
				if ( isset( $this->plugins[ $key ] ) ) {
					$options[ $key ] = $status;
				}
			}
		}
		return $options;
	}

	private function get_plugin_list() {
		$plugin_list = array();
		foreach ( $this->plugins as $path => $plugin ) {
			$title = '<a href="' . esc_attr( $plugin['PluginURI'] ) . '" target="' . esc_attr( $path ) . '">';
			$title.= esc_html( $plugin['Name'] ) . '</a> by ';
			$title.= '<a href="' . esc_attr( $plugin['AuthorURI'] ) . '" target="' . sanitize_title( $plugin['Author'] ) . '">';
			$title.= esc_html( $plugin['Author'] ) . '</a>';
			$plugin_list[ $path ] = $title;
		}
		return $plugin_list;
	}


	/**  Theme functions  **/

	private function get_theme_defaults( $preset ) {
		$options = $this->clean_theme_defaults();
		foreach( $this->themes as $slug => $theme ) {
			if ( ! isset( $options[ $slug ] ) ) {
				$options[ $slug ] = $preset;
			}
		}
		return $options;
	}

	#	removes deleted themes by generating a new list
	private function clean_theme_defaults() {
		$options = array();
		$current = tcc_privacy( 'theme_list' );
		if ( $current ) {
			foreach( $current as $key => $status ) {
				if ( isset( $this->plugins[ $key ] ) ) {
					$options[ $key ] = $status;
				}
			}
		}
		return $options;
	}

	private function get_theme_list() {
		$theme_list = array();
		foreach( $this->themes as $slug => $theme ) {
log_entry($slug);
			if ( strpos( 'twenty', $slug ) === 0 ) {
				continue;  #  Do not filter wordpress themes
			}
			$title = '<a href="' . esc_attr( $theme->get( 'ThemeURI' ) ) . '" target="' . esc_attr( $slug ) . '">';
			$title.= esc_html( $theme->get( 'Name' ) ) . '</a> by ';
			$title.= '<a href="' . esc_attr( $theme->get( 'AuthorURI' ) ) . '" target="' . sanitize_title( $theme->get( 'Author' ) ) . '">';
			$title.= esc_html( $theme->get( 'Author' ) ) . '</a>';
			$theme_list[ $slug ] = $title;
		}
		return $theme_list;
	}

}
