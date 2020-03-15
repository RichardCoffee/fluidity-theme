<?php

# * @since 20200312
class TCC_Options_About extends TCC_Options_Options {


	protected $base     = 'about';
	protected $priority =  1000;


	protected function form_title() {
		return __( 'About / Contact', 'tcc-fluid' );
	}

	protected function form_icon() {
		return 'dashicons-welcome-view-site';
	}

#|  * @since 20150523
	public function describe_options() {
		$lib   = fluid();
		$html  = __( 'Support Site:  ', 'tcc-fluid' );
		$html .= $lib->get_element( 'a', [ 'href' => 'http://www.rtcenterprises.net', 'target' => 'rtc' ], _x( 'RTC Enterprises', 'company name', 'tcc-fluid' ) );
		$lib->element( 'p', [ ], $html, true );
		$lib->element( 'p', [ ], __( 'For help with this theme, or any other general support items, please contact us at any time', 'tcc-fluid' ) );
		$lib->element( 'p', [ ], '&copy; ' . __( 'Copyright 2014 RTC', 'tcc-fluid' ) );
	}

/*
 *  layout array
 *
 *      primary associative key: (string) field name
 *
 *   secondary associative keys: (all keys are required unless stated otherwise)
 *
 *          default: (mixed)   The default value of the field
 *             help: (string)  Help text, displayed as a title attribute
 *            label: (string)  Title of the field (required unless render is set to 'skip')
 *                               see: http://codex.wordpress.org/Function_Reference/add_settings_field
 *             text: (string)  Text displayed to the right of the field (optional field)
 *           render: (string)  How the field is to be displayed
 *                               Possible pre-set values are: checkbox, colorpicker, display, font, image, radio, select, text, textarea, wp_dropdown, skip
 *                               Another possible value is 'array', see notes for 'type'.
 *                               This string is interpreted as a function call, prefixed with 'render_'
 *                               The rendering function is passed one parameter, an associative array, like so:
 *                                 array('ID'=>{primary key}, 'value'=>{option value}, 'layout'=>{key array}, 'name'=>"{option}[{primary key}]");
 *            class: (string)  Used only if render is set to 'text'.  If set, the input class attribute will be set to this value.
 *                               Default class for a text input is 'regular-text'.
 *             type: (string)  Required only if render is set to 'array'. possible values are 'image' and 'text'.
 *                               functionality for this is only partially implemented.
 *           source:           Required only if render is set to 'font', 'radio', 'select', or 'wp_dropdown'
 *                   (array)   The array values will be used to generate the radio buttons / select listing.
 *                               This must be an associative array.
 *                     -or-
 *                   (string)  Name of the function that returns the select options (render: select)
 *                               example: http://codex.wordpress.org/Function_Reference/wp_dropdown_roles
 *                   (string)  Suffix name of the wp_dropdown_* function (render:  wp_dropdown)
 *                               example: http://codex.wordpress.org/Function_Reference/wp_dropdown_pages
 *           change: (string)  Used only if render is set to 'checkbox','font','radio','select', or 'text'.
 *                             Will be applied as an 'onchange' html attribute (optional)
 *            media:           Required only if render is set to 'image'.
 *                   (array)     title:  (string) Title displayed in media uploader
 *                               button: (string) Button text - used for both the admin and the media buttons
 *                               delete: (string) Delete text - used for admin button
 *             args: (array)   Required only if render is set to 'wp_dropdown'.
 *                               This array will be passed to the called function.
 *           divcss: (string)  If set, a div is created to surround the rendered object, with the string assigned to the class attribute of that div (optional)
 *          require: (boolean) If set, then when saving (as currently implemented) then a blank field will be set to the default value.
 *         (string): (mixed)   Any other key can be added to this array.
 *
 *                             The layout array is passed to the rendering function.
 *
#|  * @since 20150523
 */
	protected function options_layout() {
		$layout = array(
			'version' => array(
				'label'   => __( 'Theme Version', 'tcc-fluid' ),
				'text'    => FLUIDITY_VERSION,
				'render'  => 'display',
			),
			'theme' => array(
				'label'  => __( 'Theme Settings', 'tcc-fluid' ),
				'text'   => __( 'control the menu location of the theme options screen.', 'tcc-fluid' ),
				'render' => 'title',
			),
			'loca' => array(
				'default' => 'appearance',
				'label'   => __( 'Page Location', 'tcc-fluid' ),
				'text'    => __( 'You can choose where the Theme Options page appears', 'tcc-fluid' ),
				'help'    => __( 'I recommend you leave it on the default setting, which is Appearance', 'tcc-fluid' ),
				'render'  => 'radio',
				'source'  => array(
					'dashboard'  => __( 'Dashboard menu', 'tcc-fluid' ),
					'appearance' => __( 'Appearance menu', 'tcc-fluid' ),
					'settings'   => __( 'Settings menu', 'tcc-fluid' ),
				),
				'showhide' => array(
					'origin' => 'tcc-loca',
					'target' => 'tcc-wp_posi',
					'show'   => 'dashboard',
				),
				'divcss'  => 'tcc-loca', // showhide',
			),
			'wp_posi' => array(
				'default' => 'bottom',
				'label'   => __( 'Dashboard location', 'tcc-fluid' ),
				'text'    => __( 'This controls where on the WordPress Dashboard menu that Theme Options will appear', 'tcc-fluid' ),
				'help'    => __( 'Bottom is best for this option.  Having it at the top can be annoying.  YMMV', 'tcc-fluid' ),
				'render'  => 'radio',
				'source'  => array(
					'top'    => __( 'Top', 'tcc-fluid' ),
					'bottom' => __( 'Bottom', 'tcc-fluid' ),
				),
				'divcss'  => 'tcc-wp_posi',
			),
			'survey' => array(
				'default' => 'no',
				'label'   => __( 'Plugin Survey', 'tcc-fluid' ),
				'text'    => __( 'Can the theme author collect info on what plugins are installed?  It will help efforts to improve the theme.', 'tcc-fluid' ),
				'help'    => __( "WordPress is already collecting this information, and they didn't even ask.", 'tcc-fluid' ),
				'render'  => 'radio',
				'source'  => array(
					'no'  => __( 'No way, No how.', 'tcc-fluid' ),
					'yes' => __( 'Yes, the theme author can collect info on what plugins are currently installed.', 'tcc-fluid' ),
				),
				'postext' => __( "Only information returned by the WordPress function 'get_plugins()' would be collected.", 'tcc-fluid' )
			),
			'themedata' => array(
				'label'  => __( 'Theme Data', 'tcc-fluid' ),
				'text'   => __( 'control when theme data is removed', 'tcc-fluid' ),
				'render' => 'title'
			),
			'deledata' => array(
				'default' => 'uninstall',
				'label'   => __( 'Data Deletion', 'tcc-fluid' ),
				'render'  => 'radio',
				'source'  => array(
					'deactive'  => __( 'Delete theme data upon theme deactivation', 'tcc-fluid' ),
					'uninstall' => __( 'Delete theme data upon theme deletion', 'tcc-fluid' ),
					'nodelete'  => __( 'Do not delete data', 'tcc-fluid' ),
				),
			),
		);
		return $layout;
	}


}
