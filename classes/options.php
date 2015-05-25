<?php /* arrays.php */

include_once('basic-form.php');

class Fluidity_Options_Form extends Basic_Admin_Form {

  private static $instance;

  private static function translated_text() {
    return array('title'     => array('about'  => __('About / Contact','tcc-fluid'),
                                      'menu'   => __('Theme Options','tcc-fluid')),
                 'describe'  => array(__('Support Site:','tcc-fluid'),
                                      __('For help with this theme, or any other general support items, please contact us at any time','tcc-fluid'),
                                      __('Copyright 2014-2015 TCC','tcc-fluid')),
                 'theme'     => array('label'  => __('Theme Settings','tcc-fluid'),
                                      'text'   => __("These following options control the theme's behavior",'tcc-fluid')),
                 'loca'      => array('label'  => __('Page Location','tcc-fluid'),
                                      'text'   => __('You can choose where the Theme Options page appears','tcc-fluid'),
                                      'source' => array('dashboard'  => __('Dashboard menu','tcc-fluid'),
                                                        'appearance' => __('Appearance menu','tcc-fluid'),
                                                        'settings'   => __('Settings menu','tcc-fluid'))),
                 'wp_posi'   => array('label'  => __('Dashboard location','tcc-fluid'),
                                      'text'   => __('This controls where on the WordPress Dashboard menu that Theme Options will appear','tcc-fluid'),
                                      'source' => array('top'    => __('Top','tcc-fluid'),
                                                        'bottom' => __('default','tcc-fluid'))),
                 'version'   => array('label'  => __('Theme Version','tcc-fluid')));
  }

/*  protected function form_trans_text($text,$orig) {
    $text = parent::form_trans_text();
    $text['object']  = __('Options','tcc-fluid');
    $text['subject'] = __('Theme','tcc-fluid');
    return apply_filters('tcc_form_text',$text,$text);
  } //*/

  protected function __construct() {
    self::$instance = $this;
    $this->prefix = 'tcc_options_';
    $this->slug   = 'fluidity_options';
    $this->type   = 'tabbed';
#    add_filter('basic_form_text','form_trans_text',10,2);
    parent::__construct();
  }

  public static function get_instance() {
    if (!self::$instance)   self::$instance = new Fluidity_Options_Form;
    if (empty(self::$text)) self::$text     = self::translated_text();
    return self::$instance;
  }

  public static function add_menu_option() {
    $menu_cap  = 'edit_theme_options';
    if (current_user_can($menu_cap)) {
      $about = get_option('tcc_options_about');
      if (!$about) $about = $this->get_defaults('about');
      $page_title = self::$text['title']['menu'];
      $menu_title = self::$text['title']['menu'];
      $menu_func  = array($this,$this->render);
      if ($about['loca']=='appearance') {
        add_theme_page($page_title,$menu_title,$menu_cap,$this->slug,$menu_func);
      } else if ($about['loca']=='settings') {
        add_options_page($page_title,$menu_title,$menu_cap,$this->slug,$menu_func);
      } else {
        $icon_name = 'dashicons-admin-settings';
        $priority  = ($about['wp_posi']=='top') ? '1.01302014' : '99.9122473024';
        add_menu_page($page_title,$menu_title,$menu_cap,$this->slug,$menu_func,$icon_name,$priority);
      }
      do_action('tcc_admin_menu_setup');
    }
  }

/*
 *  options array - note: the array is only built once!
 *
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
    if (empty($this->form)) {
      $this->form = apply_filters('tcc_options_menu_array',$this->form);
      if (!isset($this->form['about'])) {
        $this->form['about'] = array('describe' => array($this,'describe_about'),
                                     'title'    => self::$text['title']['about'],
                                     'option'   => 'tcc_options_about',
                                     'layout'   => $this->options_layout('about'));
      }
    }
    return (empty($section)) ? $this->form : $this->form[$section];
  }

/*
 *  layout array
 *
 *      primary associative key: (string) field name
 *
 *   secondary associative keys: (all keys are required unless stated otherwise)
 *
 *          default: (mixed)  The default value of the field
 *            label: (string) Title of the field (required unless render is set to 'skip')
 *                            see: http://codex.wordpress.org/Function_Reference/add_settings_field
 *             text: (string) Text displayed to the right of the field (optional field)
 *           render: (string) How the field is to be displayed
 *                            Possible pre-set values are: checkbox, colorpicker, display, image, radio, select, text, textarea, wp_dropdown, skip
 *                            This string is interpreted as a function call, prefixed with 'render_'
 *                            The rendering function is given one parameter, an associative array, like so:
 *                                array('ID'=>$key, 'value'=>$value, 'layout'=>$layout, 'name'=>"option_screen[$key]");
 *           source: This key is required only if render is set to 'radio', 'select', or 'wp_dropdown'
 *                   (array)  The array values will be used to generate the 'select' listing.
 *                            This must be an associative array.
 *                     -or-
 *                   (string) Name of the function that returns the select options (render: select)
 *                            example: http://codex.wordpress.org/Function_Reference/wp_dropdown_roles
 *                   (string) suffix name of the wp_dropdown_* function (render:  wp_dropdown)
 *                            example: http://codex.wordpress.org/Function_Reference/wp_dropdown_pages
 *            media: This key is required only if render is set to 'image'
 *                   (array)  title:  (string) Title displayed in media uploader
 *                            button: (string) Button text - used for both the admin and the media buttons
 *             args: (array)  Used only if render is set to 'wp_dropdown'.
 *                            This array will be passed to the called function.
 *            class: (string) A span is created to surround the rendered object.  This class is applied to that span (optional field)
 *         (string): (mixed)  Any other key can be added to this array.
 *                            The layout array is passed to the rendering function.
 *
 */
  private function about_options_layout() {


    $instance = Theme_Options_Plugin::get_instance();
    if (!$instance) $instance = new Theme_Options_Plugin(tcc_theme_plugin_info());




    $layout = array('version'   => array('default' => $instance->version,
                                         'label'   => self::$text['version']['label'],
                                         'render'  => 'display'),
                    'dbvers'    => array('default' => $instance->dbvers,
                                         'label'   => self::$text['dbvers']['label'],
                                         'render'  => 'skip'),
                    'theme'     => array('label'   => self::$text['theme']['label'],
                                         'text'    => self::$text['theme']['text'],
                                         'render'  => 'title'),
                    'loca'      => array('default' => 'appearance',
                                         'label'   => self::$text['loca']['label'],
                                         'text'    => self::$text['loca']['text'],
                                         'render'  => 'radio',
                                         'source'  => self::$text['loca']['source'],
                                         'change'  => 'showhidePosi();',
                                         'class'   => 'tcc-loca'),
                    'wp_posi'   => array('default' => 'Top',
                                         'label'   => self::$text['wp_posi']['label'],
                                         'text'    => self::$text['wp_posi']['text'],
                                         'render'  => 'select',
                                         'source'  => self::$text['wp_posi']['source'],
                                         'class'   => 'tcc-wp_posi'));
    $layout = apply_filters('tcc_about_options_layout',$layout);
    return $layout;
  }

  public function describe_about() {
    echo '<p>'.self::$text['describe'][0].' <a href="the-creative-collective.com" target="tcc">The Creative Collective</a></p>';
    echo '<p>'.self::$text['describe'][1].'</p>';
    echo '<p>&copy; '.self::$text['describe'][2].'</p>';
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

?>
