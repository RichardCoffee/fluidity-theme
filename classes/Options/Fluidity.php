<?php
/**
 *  Handles displaying theme options.
 *
 * @package Fluidity
 * @subpackage Options
 * @since 20150523
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2015, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/Options/Fluidity.php
 */
defined( 'ABSPATH' ) || exit;

class TCC_Options_Fluidity extends TCC_Form_Admin {


	/**
	 * @since 20170307
	 * @var array  Contains instances of option classes.
	 */
	private $classes = array();
	/**
	 * @since 20150523
	 * @var array  Contains text.
	 */
	private static $text = null;

	/**
	 * @since 20170112
	 * @link https://github.com/RichardCoffee/custom-post-type/blob/master/classes/Trait/Singleton.php
	 */
	use TCC_Trait_Singleton;

	/**
	 *  Format some text for the options screen.
	 *
	 * @since 20150525
	 * @param array $text  Form text.
	 * @param array $orig  The original form text.
	 * @return array       Modified form text.
	 */
	public function form_trans_text( $text, $orig ) {
		$text['submit']['object']  = __( 'Options', 'tcc-fluid' );
		$text['submit']['subject'] = __( 'Theme', 'tcc-fluid' );
		return $text;
	}

	/**
	 *  Constructor method.
	 *
	 * @since 20150525
	 * @link https://codex.wordpress.org/Plugin_API/Admin_Screen_Reference
	 */
	protected function __construct() {
		$this->prefix = 'tcc_options_';
		$this->slug   = 'fluidity_options';
		$this->type   = 'tabbed';
		add_action( 'admin_menu',               [ $this, 'add_menu_option' ] );
		add_action( 'tcc_admin_menu_setup',     [ $this, 'initialize_options' ] );
		add_filter( 'form_text_' . $this->slug, [ $this, 'form_trans_text' ], 10, 2 );
		parent::__construct();
		add_action( 'current_screen', function() {
			$this->check_screen( 'options-general', 'add_currency_symbol' );
		} );
	}

	/**
	 *  Add the menu option.
	 *
	 * @since 20150525
	 */
	public function add_menu_option() {
		$cap = 'edit_theme_options';
		if ( current_user_can( $cap ) ) {
			$about = get_option( 'tcc_options_about' );
			if ( ! $about ) {
				$about = $this->get_defaults( 'about' );
			}
			$page = __( 'Theme Options', 'tcc-fluid' );
			$menu = __( 'Theme Options', 'tcc-fluid' );
			$func = array( $this, $this->render );
			if ( $about['loca'] === 'appearance' ) {
				#$this->hook_suffix = add_theme_page( $page, $menu, $cap, $this->slug, $func );
				#$this->hook_suffix = add_submenu_page( 'themes.php', $page, $menu, $cap, $this->slug, $func );
				$this->hook_suffix = call_user_func( 'add_theme_page', $page, $menu, $cap, $this->slug, $func ); # FIXME: hack
			} elseif ( $about['loca'] === 'settings' ) {
				#$this->hook_suffix = add_options_page( $page, $menu, $cap, $this->slug, $func );
				#$this->hook_suffix = add_submenu_page( 'options-general.php', $page, $menu, $cap, $this->slug, $func );
				$this->hook_suffix = call_user_func( 'add_options_page', $page, $menu, $cap, $this->slug, $func ); # FIXME: hack
			} else {
				$icon = 'dashicons-admin-settings';
				$priority = ( $about['wp_posi'] === 'top' ) ? '1.01302014' : '99.98697986';
				#$this->hook_suffix = add_menu_page( $page, $menu, $cap, $this->slug, $func, $icon, $priority );
				$this->hook_suffix = call_user_func( 'add_menu_page', $page, $menu, $cap, $this->slug, $func, $icon, $priority ); # FIXME: hack
			}
			do_action( 'tcc_admin_menu_setup' );
		}
	}

	/**
	 *  Get the options for the form.
	 *
	 * @since 20170304
	 */
	public function initialize_options() {
		$this->classes['Design']     = new TCC_Options_Design;    # 80
		$this->classes['Social']     = new TCC_Options_Social;    # 100
		$this->classes['Settings']   = new TCC_Options_Settings;  # 500
		$this->classes['APIControl'] = TCC_Options_APIControl::instance();  # 570
		$this->classes['Third']      = new TCC_Options_Third;     # 600
		if ( WP_DEBUG ) {
			$this->classes['Bootstrap'] = new TCC_Options_Bootstrap; # 700
		}
		$this->classes['About'] = new TCC_Options_About; # 1000
		$this->classes = apply_filters( 'fluidity_initialize_options', $this->classes );
	}


/*
 *      primary associative key: (string) section slug name
 *
 *   secondary associative keys: (all keys are required)
 *          describe: (string) name of the function to display the description text
 *                       or      see: http://codex.wordpress.org/Function_Reference/add_settings_section
 *                    (array)  class reference - see classes/design.php for example of this usage
 *             title: (string) title of the section tab
 *                               see: http://codex.wordpress.org/Function_Reference/add_settings_section
 *            option: (string) option name, used to save and retrieve the options
 *            layout: (array)  field data - see the section below describing the layout array
 *
 * @since 20150523
 * @param string $section  Name of the section desired.
 * @return array           Section or the entire form.
 */
	protected function form_layout( $section = '' ) {
		$form['title'] = __( 'Theme Options', 'tcc-fluid' );
		$form = apply_filters( 'fluidity_options_form_layout', $form );
		return ( empty( $section ) ) ? $form : $form[ $section ];
	}

	/**
	 *  Retrieve the section layout.
	 *
	 * @since 20150523
	 * @param string $section  Name of the section for the layout.
	 * @return array           Section layout.
	 */
	protected function options_layout( $section ) {
		$lookup = $section.'_options_layout';
		$layout = array();
		if ( method_exists( $this, $lookup ) ) {
			$layout = $this->$lookup();
		}
		return $layout;
	}

	/**
	 *  Get options for the form.
	 *
	 * @since 20170307
	 * @return array  The form options.
	 */
	public function get_options() {
		static $values = array();
		if ( empty( $values ) ) {
			if ( empty( $this->classes ) ) {
				$this->initialize_options();
			}
			if ( empty( $this->form ) ) {
				$this->form = $this->form_layout();
			}
			foreach( $this->form as $key => $section ) {
				if ( is_array( $section ) && isset( $section['option'] ) ) {
					$values[ $key ] = get_option( $section['option'], array() );
				}
			}
		}
		return $values;
	}

	/**
	 *  Get an array of file names.  Written to retrieve a list of color files.
	 *
	 * @since 20150523
	 * @param string $slug  Filename slug.
	 * @param string $base  Relative path to files
	 * @param bool   $full  Whether the full file name should be returned.
	 * @return array        Files meeting the desired criteria.
	 * /
	protected function create_file_select( $slug, $base = '', $full = false ) {
		$dir = get_stylesheet_directory();
		if ( ! empty( $base ) ) { $dir .= '/' . $base; }
		$files  = scandir( $dir );
		$result = array();
		foreach( $files as $filename ) {
			if ( strpos( $filename, $slug ) === false ) { continue; }
			$data = get_file_data( $dir . '/' . $filename, array( 'name' => 'Name' ) );
			if ( $data ) {
				$sname = $filename;
				if ( ! $full ) {
					$pos1  = strpos( $filename, '-' );
					$pos1  = ( $pos1 === false ) ? 0 : $pos1 + 1;
					$pos2  = strpos( $filename, '.' );
					$sname = substr( $filename, $pos1, ( $pos2 - $pos1 ) );
				}
			}
			$result[ $sname ] = $descrip;
		}
		return $result;
	}

	/**
	 *  Create the layout required.
	 *
	 * @since 20150523
	 * @param array  $data  Source data for the layout.
	 * @param string $text  Text for the layout label.
	 * @return array        The completed layout.
	 * /
	protected static function create_select_layout( $data, $text ) {
		if ( is_array( $text ) ) {
			$select = $text;
		} else {
			$select = array( 'label' => $text );
		}
		$select['default'] = reset( $data );
		$select['render']  = 'select';
		$select['source']  = $data;
		return $select;
	}

	/**
	 *  Call a method on the desired screen.
	 *
	 * @since 20180323
	 * @param string $check   The desired screen.
	 * @param string $method  The method to call.
	 * @param array  $args    Data for the method.
	 */
	private function check_screen( $check, $method, $args = array() ) {
		if ( function_exists( 'get_current_screen' ) ) {
			if ( get_current_screen()->base === $check ) {
				$this->$method( $args );
			}
		} else { fluid()->log("no screen for '$check'"); }
	}

	/**
	 *  Add the currency symbol field to the current screen.
	 *
	 * @since 20170211
	 */
	private function add_currency_symbol() {
		$default     = _x( '$', 'primary currency symbol', 'tcc-fluid' );
		$field_name  = 'currency_symbol';
		$description = __( 'Currency Symbol', 'tcc-fluid' );
		$args = apply_filters(
			'fluid_currency_symbol',
			array(
				'field_css'     => 'small-text',
				'field_default' => $default,
				'field_name'    => $field_name,
				'group'         => 'general',
				'description'   => $description,
				'sanitize'      => 'sanitize_text_field',
			)
		);
		new TCC_Form_Field_Admin( $args );
	}


}
