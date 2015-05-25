<?php /* arrays.php */

// FIXME: add option for editor default content - http://www.smashingmagazine.com/2009/08/18/10-useful-wordpress-hook-hacks/

class Fluidity_Theme_Options extends Basic_Admin_Form {

  private static $TCC_text = array();

  protected static function translate_text() {
    return array('title'     => array('about'  => __('About / Contact','tcc-theme-options')),
                 'describe'  => array(__('Support Site:','tcc-theme-options'),
                                      __('For help with this plugin, or any other general support items, please contact us at any time','tcc-theme-options'),
                                      __('Copyright 2014-2015 TCC','tcc-theme-options')),
                 'plugin'    => array('label'  => __('Plugin Settings','tcc-theme-options'),
                                      'text'   => __("These following options control the plugin's behavior",'tcc-theme-options')),
                 'loca'      => array('label'  => __('Page Location','tcc-theme-options'),
                                      'text'   => __('You can choose where the Theme Options page appears','tcc-theme-options'),
                                      'source' => array('dashboard'  => __('Dashboard menu','tcc-theme-options'),
                                                        'appearance' => __('Appearance menu','tcc-theme-options'),
                                                        'settings'   => __('Settings menu','tcc-theme-options'))),
                 'wp_posi'   => array('label'  => __('Dashboard location','tcc-theme-options'),
                                      'text'   => __('This controls where on the WordPress Dashboard menu that Theme Options will appear','tcc-theme-options'),
                                      'source' => array('top'    => __('Top','tcc-theme-options'),
                                                        'bottom' => __('default','tcc-theme-options'))),
                 'collect'   => array('label'  => __('Share Usage','tcc-theme'),
                                      'text'   => __('Help us improve our programs by sharing how they get used','tcc-theme-options'),
                                      'source' => array('no'  => __("No, that info belongs to me and The Creative Collective can't have it.",'tcc-theme-option'),
                                                        'yes' => __("Yes, but only so long as The Creative Collective doesn't share it...With anyone...Ever.",'tcc-theme-options'))),
                 'deactive'  => array('label'  => __('Plugin Deactivation','tcc-theme-options'),
                                      'text'   => __('Data will be deleted when deactivating the plugin.','tcc-theme-options')),
                 'uninstall' => array('label'  => __('Plugin Removal','tcc-theme-options'),
                                      'text'   => __('Data will be deleted when removing the plugin.','tcc-theme-options')),
                 'version'   => array('label'  => __('Program Version','tcc-theme-options')),
                 'dbvers'    => array('label'  => __('Database Version','tcc-theme-options')));
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
  public static function options_menu_array($section='') {
    if (empty(self::$TCC_text)) self::$TCC_text = self::translate_text();
#    static $options = array();
    $options = array();
    if (empty($options)) {
      $options = apply_filters('tcc_options_menu_array',$options);
      if (!isset($options['about'])) {
        $options['about'] = array('describe' => array('TCC_Theme_Options_Values','describe_about'),
                                  'title'    => self::$TCC_text['title']['about'],
                                  'layout'   => self::options_layout('about'));
      }
    }
    return (empty($section)) ? $options : $options[$section];
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
  public static function about_options_layout() {
    $instance = Theme_Options_Plugin::get_instance();
    if (!$instance) $instance = new Theme_Options_Plugin(tcc_theme_plugin_info());
    $layout = array('version'   => array('default' => $instance->version,
                                         'label'   => self::$TCC_text['version']['label'],
                                         'render'  => 'display'),
                    'dbvers'    => array('default' => $instance->dbvers,
                                         'label'   => self::$TCC_text['dbvers']['label'],
                                         'render'  => 'skip'),
                    'plugin'    => array('label'   => self::$TCC_text['plugin']['label'],
                                         'text'    => self::$TCC_text['plugin']['text'],
                                         'render'  => 'title'),
                    'loca'      => array('default' => 'dashboard',
                                         'label'   => self::$TCC_text['loca']['label'],
                                         'text'    => self::$TCC_text['loca']['text'],
                                         'render'  => 'radio',
                                         'source'  => self::$TCC_text['loca']['source'],
                                         'change'  => 'showhidePosi();',
                                         'class'   => 'tcc-loca'),
                    'wp_posi'   => array('default' => 'Top',
                                         'label'   => self::$TCC_text['wp_posi']['label'],
                                         'text'    => self::$TCC_text['wp_posi']['text'],
                                         'render'  => 'select',
                                         'source'  => self::$TCC_text['wp_posi']['source'],
                                         'class'   => 'tcc-wp_posi'),
                    'collect'   => array('default' => 'no',
                                         'label'   => self::$TCC_text['collect']['label'],
                                         'text'    => self::$TCC_text['collect']['text'],
                                         'render'  => 'radio',
                                         'source'  => self::$TCC_text['collect']['source']),
                    'deactive'  => array('default' => 'no',
                                         'label'   => self::$TCC_text['deactive']['label'],
                                         'text'    => self::$TCC_text['deactive']['text'],
                                         'render'  => 'checkbox'),
                    'uninstall' => array('default' => 'yes',
                                         'label'   => self::$TCC_text['uninstall']['label'],
                                         'text'    => self::$TCC_text['uninstall']['text'],
                                         'render'  => 'checkbox'));
    $layout = apply_filters('tcc_about_options_layout',$layout);
    return $layout;
  }

  public static function describe_about() {
    echo '<p>'.self::$TCC_text['describe'][0].' <a href="the-creative-collective.com" target="tcc">The Creative Collective</a></p>';
    echo '<p>'.self::$TCC_text['describe'][1].'</p>';
    echo '<p>&copy; '.self::$TCC_text['describe'][2].'</p>';
  }

  public static function options_layout($section) {
    $lookup = $section.'_options_layout';
    $layout = array();
    $myclass = get_called_class();
    if (method_exists($myclass,$lookup)) {
      $layout = $myclass::$lookup();
    }
    return $layout;
  }

  public static function get_options_layout($section) {
    $mysection = self::options_menu_array($section);
    return $mysection['layout'];
  }

  /*  Retrieve option defaults  */
  public static function options_defaults($section='') {
    static $options = array();
    if (empty($options)) {
      $menu = self::options_menu_array();
      foreach($menu as $tab=>$list) {
        if (empty($list['layout'])) continue;
        foreach($list['layout'] as $option=>$settings) {
          if (isset($settings['default'])) $options[$tab][$option] = $settings['default'];
        }
      }
    }
    return (empty($section)) ? $options : ((isset($options[$section])) ? $options[$section] : array());
  }

  protected static function create_option_select($slug='',$base='',$full=false) {
    if (empty($slug)) return array();
    $result = array();
    $dir = self::check_dir($base);
    if (file_exists($dir)) {
      $files  = scandir($dir);
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
    }
    return $result;
  }

  private static function check_dir($base='') {
    $dir = get_stylesheet_directory();
    if (!empty($base)) $dir .= '/'.$base;
    if (!file_exists($dir)) {
      $dir = get_template_directory();
      if (!empty($base)) $dir .= '/'.$base;
    }
    return $dir;
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
