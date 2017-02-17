<?php /* arrays.php */

class TCC_Options_Fluidity extends TCC_Form_Admin {

  private static $text     = null;

  use TCC_Trait_Singleton;

  public function form_trans_text($text,$orig) {
    $text['submit']['object']  = __('Options','tcc-fluid');
    $text['submit']['subject'] = __('Theme','tcc-fluid');
    return $text;
  } //*/

  protected function __construct() {
    $this->prefix   = 'tcc_options_';
    $this->slug     = 'fluidity_options';
    $this->type     = 'tabbed';
    add_action('admin_menu',     array($this,'add_menu_option'));
    add_filter('form_text_'.$this->slug,array($this,'form_trans_text'),10,2);
    parent::__construct();
    new TCC_Options_Layout;	# 35
    new TCC_Options_Design;	# 34
    new TCC_Options_Social;
    #if (tcc_design('paral')==='yes') { new TCC_Options_Parallax; }
    new TCC_Options_Settings;
    new TCC_Options_Privacy;
    if ( WP_DEBUG ) {
      new TCC_Options_Bootstrap;
    }
    $this->add_currency_symbol();
  }

  public function add_menu_option() {
    $cap = 'edit_theme_options';
    if (current_user_can($cap)) {
      $about = get_option('tcc_options_about');
      if (!$about) $about = $this->get_defaults('about');
      $page = __('Theme Options','tcc-fluid');
      $menu = __('Theme Options','tcc-fluid');
      $func = array($this,$this->render);
      if ($about['loca']=='appearance') {
        $this->hook_suffix = add_theme_page($page,$menu,$cap,$this->slug,$func);
      } elseif ($about['loca']=='settings') {
        $this->hook_suffix = add_options_page($page,$menu,$cap,$this->slug,$func);
      } else {
        $icon = 'dashicons-admin-settings';
        $priority = ($about['wp_posi']=='top') ? '1.01302014' : '99.9122473024';
        $this->hook_suffix = add_menu_page($page,$menu,$cap,$this->slug,$func,$icon,$priority);
      }
      do_action('tcc_admin_menu_setup');
    }
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
 */
  protected function form_layout($section='') {
    $form['title'] = __('Theme Options','tcc-fluid');
    $form = apply_filters('fluidity_options_form_layout',$form);
    $form['about'] = array('describe' => 'describe_about',
                           'title'    => __('About / Contact','tcc-fluid'),
                           'option'   => 'tcc_options_about',
                           'layout'   => $this->options_layout('about'));
    return (empty($section)) ? $form : $form[$section];
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
 *           change: (string)  Required only if render is set to 'checkbox','font','radio','select', and 'text'.
 ^                             Will be applied as an 'onchange' html attribute (optional)
 *            media:           Required only if render is set to 'image'.
 *                   (array)     title:  (string) Title displayed in media uploader
 *                               button: (string) Button text - used for both the admin and the media buttons
 *                               delete: (string) Delete text - used for admin button
 *             args: (array)   Required only if render is set to 'wp_dropdown'.
 *                               This array will be passed to the called function.
 *           divcss: (string)  A div is created to surround the rendered object.  If set, this string is assigned to the class attribute of that div (optional)
 *          require: (boolean) If set, then when saving (as currently implemented) a blank field will be set to the default value.
 *         (string): (mixed)   Any other key can be added to this array.
 *
 *                             The layout array is passed to the rendering function.
 *
 */
  private function about_options_layout() {
    $layout = array('version'   => array('text'		=> FLUIDITY_VERSION,
                                         'label'   => __('Theme Version','tcc-fluid'),
                                         'render'  => 'display'),
                    'theme'     => array('label'   => __('Theme Settings','tcc-fluid'),
                                         'text'    => __('control the menu location of the theme options screen.','tcc-fluid'),
                                         'render'  => 'title'),
                    'loca'      => array('default' => 'appearance',
                                         'label'   => __('Page Location','tcc-fluid'),
                                         'text'    => __('You can choose where the Theme Options page appears','tcc-fluid'),
                                         'help'    => __('I recommend you leave it on the default setting, which is Appearance','tcc-fluid'),
                                         'render'  => 'radio',
                                         'source'  => array('dashboard'  => __('Dashboard menu','tcc-fluid'),
                                                            'appearance' => __('Appearance menu','tcc-fluid'),
                                                            'settings'   => __('Settings menu','tcc-fluid')),
                                         'change'  => 'showhidePosi(this,".tcc-wp_posi","dashboard");',
                                         'showhide'=> array('item' => '.tcc-wp_posi',
                                                            'show' => 'dashboard'),
                                         'divcss'  => 'tcc-loca showhide'),
                    'wp_posi'   => array('default' => 'bottom',
                                         'label'   => __('Dashboard location','tcc-fluid'),
                                         'text'    => __('This controls where on the WordPress Dashboard menu that Theme Options will appear','tcc-fluid'),
                                         'help'    => __('Bottom is best for this option.  Having it at the top can be annoying.  YMMV','tcc-fluid'),
                                         'render'  => 'radio',
                                         'source'  => array('top'    => __('Top','tcc-fluid'),
                                                            'bottom' => __('Bottom','tcc-fluid')),
                                         'divcss'  => 'tcc-wp_posi'),
                    'themedata' => array('label'   => __('Theme Data','tcc-fluid'),
                                         'text'    => __('control when theme data is removed','tcc-fluid'),
                                         'render'  => 'title'),
                    'deledata'  => array('default' => 'uninstall',
                                         'label'   => __('Data Deletion','tcc-fluid'),
                                         'render'  => 'radio',
                                         'source'  => array('deactive'  => __('Delete theme data upon theme deactivation','tcc-fluid'),
                                                            'uninstall' => __('Delete theme data upon theme deletion','tcc-fluid'),
                                                            'nodelete'  => __('Do not delete data','tcc-fluid'))),
);
    $layout = apply_filters('tcc_about_options_layout',$layout);
    return $layout;
  }

  public function describe_about() {
    $describe = array(__('Support Site:','tcc-fluid'),
                      _x('The Creative Collective','company name','tcc-fluid'),
                      __('For help with this theme, or any other general support items, please contact us at any time','tcc-fluid'),
                      __('Copyright 2014-2017 TCC','tcc-fluid'));
    echo "<p>{$describe[0]} <a href='the-creative-collective.com' target='tcc'>{$describe[1]}</a></p>";
    echo "<p>{$describe[2]}</p>";
    echo "<p>&copy; {$describe[3]}</p>";
  }

  protected function options_layout($section) {
    $lookup = $section.'_options_layout';
    $layout = array();
    if (method_exists($this,$lookup)) {
      $layout = $this->$lookup();
    }
    return $layout;
  }

  protected function get_options_layout($section) {
    return $this->form[$section]['layout'];
  }

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

  protected static function create_select_layout($data,$text) {
    if (is_array($text)) {
      $select = $text;
    } else {
      $select = array('label' => $text);
    }
    $select['default'] = reset($data);
    $select['render']  = 'select';
    $select['source']  = $data;
    return $select;
  }

	private function add_currency_symbol() {
		$args = array(
			'field_css'     => 'small-text',
			'field_default' => _x( '$', 'primary currency symbol - use your own judgement', 'tcc-fluid' ),
			'field_name'    => 'currency_symbol',
			'group'         => 'general',
			'label_text'    => esc_html__( 'Currency Symbol', 'tcc-fluid' ),
			'sanitize'      => 'sanitize_text_field',
		);
		new TCC_Form_Field_Admin( $args );
	}


}
