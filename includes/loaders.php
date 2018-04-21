<?php
/*
 * @brief Autoloads class files
 */
if ( ! function_exists( 'fluidity_class_loader' ) ) {
	function fluidity_class_loader( $class ) {
		if ( substr( $class, 0, 4 ) === 'TCC_' ) {
			$load = str_replace( '_', '/', substr( $class, ( strpos( $class, '_' ) + 1 ) ) );
			$file = FLUIDITY_HOME . 'classes/' .  $load . '.php';
			if ( is_readable( $file ) ) {
				include $file;
			}
		}
	}
	spl_autoload_register( 'fluidity_class_loader' );
}

/**
 * Returns an instance of the Theme Library class
 *
 * @since 2.3.0
 * @staticvar TCC_Theme_Library $library
 * @return TCC_Theme_Library the instance
 */
if ( ! function_exists( 'fluid' ) ) {
   function fluid() {
      static $library;
      if ( empty( $library ) ) {
         $library = new TCC_Theme_Library;
      }
      return $library;
   }
}

/**
 * Returns an instance of the ClearFix class
 *
 * @since 2.3.0
 *
 * @staticvar TCC_Theme_ClearFix $library
 *
 * @return TCC_Theme_ClearFix the instance
 */
if ( ! function_exists( 'clearfix' ) ) {
   function clearfix() {
      static $library;
      if ( empty( $library ) ) {
         $library = new TCC_Theme_ClearFix;
      }
      return $library;
   }
}

/**
 * Returns an instance of the Comment class
 *
 * @since 2.3.0
 * @staticvar TCC_Theme_Comment $library
 * @return TCC_Theme_Comment the instance
 */
if ( ! function_exists( 'fluid_comment' ) ) {
   function fluid_comment() {
      static $library;
      if ( empty( $library ) ) {
         $library = new TCC_Theme_Comment;
      }
      return $library;
   }
}

/**
 * Returns an instance of the Customizer class
 *
 * @since 3.0.0
 * @staticvar TCC_Options_Customizer $library
 * @return TCC_Options_Customizer
 */
if ( ! function_exists( 'fluid_customizer' ) ) {
   function fluid_customizer() {
      static $library;
      if ( empty( $library ) ) {
         $library = new TCC_Options_Customizer;
      }
      return $library;
   }
}

/**
 * Returns an instance of a metabox for an image gallery
 *
 * @since 20180420
 * @return object
 */
if ( ! function_exists( 'fluid_gallery' ) ) {
	function fluid_gallery( $args = array( 'type' => 'post' ) ) {
		if ( get_theme_mod( 'theme_gallery', 'no' ) === 'yes' ) {
			return new TCC_MetaBox_GalleryView( $args );
		}
		return null;
	}
}

/**
 * Returns an instance of the Theme Login class
 *
 * @since 2.3.0
 * @staticvar TCC_Theme_Login $library
 * @return TCC_Theme_Login the instance
 */
if ( ! function_exists( 'fluid_login' ) ) {
   function fluid_login() {
      static $library;
      if ( empty( $library ) ) {
         $library = new TCC_Theme_Login();
      }
      return $library;
   }
}

/**
 * Returns an instance of the Options class
 *
 * @since 2.3.0
 * @staticvar TCC_Options_FLuidity $library
 * @return TCC_Options_FLuidity the instance
 */
if ( ! function_exists( 'fluid_options' ) ) {
   function fluid_options() {
      static $library;
      if ( empty( $library ) ) {
         $library = TCC_Options_Fluidity::instance();
      }
      return $library;
   }
}

/**
 * Returns an instance of the Register Sidebars class
 *
 * @since 2.3.0
 * @staticvar TCC_Register_Sidebars $library
 * @return TCC_Register_Sidebars the instance
 */
if ( ! function_exists( 'fluid_register_sidebars' ) ) {
   function fluid_register_sidebars() {
      static $library;
      if ( empty( $library ) ) {
         $library = new TCC_Register_Sidebars;
      }
      return $library;
   }
}

/**
 * Returns an instance of the Form Sanitize class
 *
 * @since 3.0.0
 * @staticvar TCC_Form_Sanitize $library
 * @return TCC_Form_Sanitize
 */
if ( ! function_exists( 'fluid_sanitize' ) ) {
   function fluid_sanitize() {
      static $library;
      if ( empty( $library ) ) {
         $library = new TCC_Form_Sanitize;
      }
      return $library;
   }
}

/**
 * Returns an instance of the Theme Support class
 *
 * @since 2.3.0
 * @staticvar TCC_Theme_Support $library
 * @return TCC_Theme_Support the instance
 */
if ( ! function_exists( 'fluid_theme_support' ) ) {
   function fluid_theme_support() {
      static $library;
      if ( empty( $library ) ) {
         $library = new TCC_Theme_Support;
      }
      return $library;
   }
}

