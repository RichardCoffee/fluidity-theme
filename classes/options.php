<?php /* arrays.php */

require_once('admin-form.php');

class Fluidity_Options_Form extends Basic_Admin_Form {

  private static $instance = null;
  private static $text     = null;

  public function form_trans_text($text,$orig) {
    $text['object']  = __('Options','tcc-fluid');
    $text['subject'] = __('Theme','tcc-fluid');
    return $text;
  } //*/

  protected function __construct() {
    self::$instance = $this;
    $this->prefix   = 'tcc_options_';
    $this->slug     = 'fluidity_options';
    $this->type     = 'tabbed';
    add_action('admin_menu',     array($this,'add_menu_option'));
    add_filter('basic_form_text',array($this,'form_trans_text'),10,2);
    parent::__construct();
  }

  public static function get_instance() {
    if (!self::$instance)   self::$instance = new Fluidity_Options_Form;
    return self::$instance;
  }

  public function add_menu_option() {
    $cap  = 'edit_theme_options';
    if (current_user_can($cap)) {
      $about = get_option('tcc_options_about');
      if (!$about) $about = $this->get_defaults('about');
      $page = __('Theme Options','tcc-fluid');
      $menu = __('Theme Options','tcc-fluid');
      $func = array($this,$this->render);
      if ($about['loca']=='appearance') {
        add_theme_page($page,$menu,$cap,$this->slug,$func);
      } else if ($about['loca']=='settings') {
        add_options_page($page,$menu,$cap,$this->slug,$func);
      } else {
        $icon = 'dashicons-admin-settings';
        $priority = ($about['wp_posi']=='top') ? '1.01302014' : '99.9122473024';
        add_menu_page($page,$menu,$cap,$this->slug,$func,$icon,$priority);
      }
      do_action('tcc_admin_menu_setup');
    }
  }

/*
 *      primary associative key: (string) section slug name
 *
 *   secondary associative keys: (all keys are required)
 *          describe: (string) name of the function to display the description text
 *                             see: http://codex.wordpress.org/Function_Reference/add_settings_section
 *             title: (string) title of the section tab
 *                             see: http://codex.wordpress.org/Function_Reference/add_settings_section
 *            layout: (array)  field data - see the section below describing the layout array
 *
 */
  protected function form_layout($section='') {
    $form['title'] = __('Theme Options','tcc-fluid');
    $form = apply_filters('tcc_options_form_layout',$form);
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
 *            label: (string)  Title of the field (required unless render is set to 'skip')
 *                               see: http://codex.wordpress.org/Function_Reference/add_settings_field
 *             text: (string)  Text displayed to the right of the field (optional field)
 *           render: (string)  How the field is to be displayed
 *                             Possible pre-set values are: checkbox, colorpicker, display, image, radio, select, text, textarea, wp_dropdown, skip
 *                             Another possible value is 'array', see notes for 'type'.
 *                             This string is interpreted as a function call, prefixed with 'render_'
 *                             The rendering function is given one parameter, an associative array, like so:
 *                               array('ID'=>$key, 'value'=>$value, 'layout'=>$layout, 'name'=>"option_screen[$key]");
 *            large: (boolean) Used only if render is set to 'text'.  If set to true, then 'large-text' will be used as the input class
 *             type: (string)  required only if render is set to 'array'. possible values are 'image' and 'text'.
 *                             functionality for this is only partially implemented.
 *           source:           This key is required only if render is set to 'radio', 'select', or 'wp_dropdown'
 *                   (array)   The array values will be used to generate the radio buttons / select listing.
 *                             This must be an associative array.
 *                     -or-
 *                   (string)  Name of the function that returns the select options (render: select)
 *                               example: http://codex.wordpress.org/Function_Reference/wp_dropdown_roles
 *                   (string)  Suffix name of the wp_dropdown_* function (render:  wp_dropdown)
 *                               example: http://codex.wordpress.org/Function_Reference/wp_dropdown_pages
 *           change: (string)  Will be applied as an 'onchange' html attribute.
 *            media:           Used only if render is set to 'image'.
 *                   (array)   title:  (string) Title displayed in media uploader
 *                             button: (string) Button text - used for both the admin and the media buttons
 *             args: (array)   Used only if render is set to 'wp_dropdown'.
 *                             This array will be passed to the called function.
 *            class: (string)  A span is created to surround the rendered object.  If set, this class is applied to that span
 *          require: (boolean) If set, then when saving (as currently implemented) a blank field will be set to the default value.
 *         (string): (mixed)   Any other key can be added to this array.
 *
 *                             The layout array is passed to the rendering function.
 *
 */
  private function about_options_layout() {
    $layout = array('version'   => array('default' => '1.0',
                                         'label'   => __('Theme Version','tcc-fluid'),
                                         'render'  => 'display'),
                    'theme'     => array('label'   => __('Theme Settings','tcc-fluid'),
                                         'text'    => __("These following options control the theme's behavior",'tcc-fluid'),
                                         'render'  => 'title'),
                    'loca'      => array('default' => 'appearance',
                                         'label'   => __('Page Location','tcc-fluid'),
                                         'text'    => __('You can choose where the Theme Options page appears','tcc-fluid'),
                                         'render'  => 'radio',
                                         'source'  => array('dashboard'  => __('Dashboard menu','tcc-fluid'),
                                                            'appearance' => __('Appearance menu','tcc-fluid'),
                                                            'settings'   => __('Settings menu','tcc-fluid')),
                                         'change'  => 'showhidePosi();',
                                         'class'   => 'tcc-loca'),
                    'wp_posi'   => array('default' => 'Top',
                                         'label'   => __('Dashboard location','tcc-fluid'),
                                         'text'    => __('This controls where on the WordPress Dashboard menu that Theme Options will appear','tcc-fluid'),
                                         'render'  => 'select',
                                         'source'  => array('top'    => __('Top','tcc-fluid'),
                                                            'bottom' => __('default','tcc-fluid')),
                                         'class'   => 'tcc-wp_posi'));
    $layout = apply_filters('tcc_about_options_layout',$layout);
    return $layout;
  }

  public function describe_about() {
    $describe = array(__('Support Site:','tcc-fluid'),
                      _x('The Creative Collective','company name','tcc-fluid'),
                      __('For help with this theme, or any other general support items, please contact us at any time','tcc-fluid'),
                      __('Copyright 2014-2016 TCC','tcc-fluid'));
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

  protected function create_file_select($slug='',$base='',$full=false) {
    if (empty($slug)) return array();
    $dir = get_stylesheet_directory();
    if (!empty($base)) $dir .= '/'.$base;
    $files  = scandir($dir);
    $result = array();
    foreach($files as $filename) {
      if (strpos($filename,$slug)===false) continue;
      // FIXME:  use WP's get_file_data instead of this mess
      $handle = fopen($dir.'/'.$filename, "r");
      if ($handle) {
        $descrip = self::get_descript($handle);
        if ($descrip) {
          $sname = $filename;
          if (!$full) {
            $pos1  = strpos($filename,'-');
            $pos1  = ($pos1===false) ? 0 : $pos1+1;
            $pos2  = strpos($filename,'.');
            $sname = substr($filename,$pos1,($pos2-$pos1));
          }
          $result[$sname] = $descrip;
        }
        fclose($handle);
      }
    }
    return $result;
  }

  private static function get_descript($fh) {
    $retval = false;
    $line   = fgets($fh);
    if (!strpos($line,'Name:')===false) {
      $cpos   = strpos($line,':');
      $apos   = strpos($line,'*/');
      $retval = substr($line,$cpos+2,($apos-$cpos-3));
    }
    return $retval;
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

}

$fluidity_theme_options = Fluidity_Options_Form::get_instance();

?>
