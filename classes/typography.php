<?php

/* sources: http://wptheming.com/2012/06/loading-google-fonts-from-theme-options/
 *          http://theme.fm/2011/08/providing-typography-options-in-your-wordpress-themes-1576/
 */


class TCC_Typography {


  public static function os_fonts() {
    // OS Font Defaults
    $os_faces = array('Arial'          => 'Arial, sans-serif',
                      'Avant Garde'    => '"Avant Garde", sans-serif',
                      'Cambria'        => 'Cambria, Georgia, serif',
                      'Copse'          => 'Copse, sans-serif', // duplicate in google_fonts()
                      'Garamond'       => 'Garamond, "Hoefler Text", Times New Roman, Times, serif',
                      'Georgia'        => 'Georgia, serif',
                      'Helvitica Neue' => '"Helvetica Neue", Helvetica, sans-serif',
                      'Tahoma'         => 'Tahoma, Geneva, sans-serif');
    return apply_filters('tcc_os_fonts',$os_faces);
  }

  public static function google_fonts() {
    // Google Font Defaults
    $google_faces = array('Arvo'         => 'Arvo, serif',
                          'Copse'        => 'Copse, sans-serif', // duplicate in so_fonts()
                          'Droid Sans'   => 'Droid Sans, sans-serif',
                          'Droid Serif'  => 'Droid Serif, serif',
                          'Lato'         => 'Lato, sans-serif',
                          'Lobster'      => 'Lobster, cursive',
                          'Nobile'       => 'Nobile, sans-serif',
                          'Open Sans'    => 'Open Sans, sans-serif',
                          'Oswald'       => 'Oswald, sans-serif',
                          'Pacifico'     => 'Pacifico, cursive',
                          'Rokkit'       => 'Rokkitt, serif',
                          'PT Sans'      => 'PT Sans, sans-serif',
                          'Quattrocento' => 'Quattrocento, serif',
                          'Raleway'      => 'Raleway, cursive',
                          'Ubuntu'       => 'Ubuntu, sans-serif',
                          'Yanone Kaffeesatz' => 'Yanone Kaffeesatz, sans-serif');
    return apply_filters('tcc_google_fonts',$google_faces);
  }

  public static function mixed_fonts() {
    $mixed_fonts = array_unique(array_merge(self::os_fonts(),self::google_fonts()));
    asort($mixed_fonts);
    return apply_filters('tcc_mixed_fonts',$mixed_fonts);
  }

  public static function load_google_fonts() {
    $google_fonts = array_keys(self::google_fonts());
    $font_options = get_option('tcc_options_design');
    if ($font_options) {
      if (isset($font_options['font'])) {
        $current  = $font_options['font'];
        $os_fonts = self::os_fonts();
        if (!in_array($current,$os_fonts)) {
          $google_fonts = self::google_fonts();
          if (in_array($current,$google_fonts)) {
            $myfont = explode(',',$google_fonts[$current]);
            $myfont = str_replace(" ","+",$myfont[0]);
            if ($myfont=='Raleway') $myfont = 'Raleway:100';
            wp_enqueue_style("typography_$myfont","http://fonts.googleapis.com/css?family=$myfont",false,null,'all');
          }
        }
      }
    }
  }

  public static function typography_styles() {
    $font_options = get_option('tcc_options_design');
    if ($font_options) {
      if (isset($font_options['font'])) {
        $current = $font_options['font'];
        $mixed   = self::mixed_fonts();
        $font    = $mixed[$current];
        $size    = (isset($font_options['size'])) ? intval($font_options['size']) : 14;
        $output  = "html {";
#        $output .= " color:        $color;";
        $output .= " font-family:  $font;";
#        $output .= " font-weight:  $weight;";
        $output .= " font-size:    {$size}px;";
        $output .= "}";
        echo $output;
      }
    }
  }

}

add_action('tcc_custom_css',    array('TCC_Typography','typography_styles'),1);
add_action('wp_enqueue_scripts',array('TCC_Typography','load_google_fonts'));

?>
