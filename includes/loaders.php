<?php
/*
 * @brief Autoloads class files
 * @since 20161209
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
 * @since 20180314
 * @staticvar TCC_Theme_Library $library
 * @return TCC_Theme_Library the instance
 */
if ( ! function_exists( 'fluid' ) ) {
	function fluid( $force = false ) {
		static $library;
		if ( empty( $library ) ) {
			$library = new TCC_Theme_Library;
		}
		if ( $force ) {
			#  force log entry during ajax call
			$library->logging_force = $force;
		}
		return $library;
	}
}

/**
 * Returns an instance of the ClearFix class
 *
 * @since 20170209
 * @staticvar TCC_Theme_ClearFix $library
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
 * @since 20160227
 * @staticvar TCC_Form_Comment $library
 * @return TCC_Form_Comment the instance
 */
if ( ! function_exists( 'fluid_comment' ) ) {
	function fluid_comment() {
		static $library;
		if ( empty( $library ) ) {
			$library = new TCC_Form_Comment;
		}
		return $library;
	}
}

/**
 * Returns an instance of TCC_Theme_ColorScheme
 *
 * @since 20180430
 * @staticvar TCC_Theme_ColorScheme $library
 * @return TCC_Theme_ColorScheme
 */
if ( ! function_exists( 'fluid_color' ) ) {
	function fluid_color() {
		static $library;
		if ( empty( $library ) ) {
			$library = new TCC_Theme_ColorScheme;
		}
		return $library;
	}
}

/**
 * Returns an instance of the Customizer class
 *
 * @since 20180404
 * @staticvar TCC_Options_Customizer $library
 * @return TCC_Options_Customizer
 */
if ( ! function_exists( 'fluid_customizer' ) ) {
	function fluid_customizer() {
		static $library;
		if ( empty( $library ) ) {
			$library = new TCC_Theme_Customizer;
		}
		return $library;
	}
}

/**
 * Returns an instance of the Forums class
 *
 * @since 20180905
 * @param array $args
 * @return TCC_NavWalker_Forums
 */
if ( ! function_exists( 'fluid_forums' ) ) {
	function fluid_forums( $args = array() ) {
		return new TCC_NavWalker_Forums( $args );
	}
}

/**
 * Returns an instance of the Theme Login class
 *
 * @since 20170121
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
 *  returns an instance of the Theme Navigation class
 *
 * @since 20180810
 * @param string $taxonomy
 * @return TCC_Theme_Navigation
 */
if ( ! function_exists( 'fluid_navigation' ) ) {
	function fluid_navigation( $taxonomy = 'category' ) {
		return new TCC_Theme_Navigation( [ 'taxonomy' => $taxonomy ] );
	}
}

/**
 * Returns an instance of the Options class
 *
 * @since 20180314
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
 * Returns an instance of the Theme Pagination class
 *
 * @since 20180819
 * @staticvar TCC_Theme_Pagination $library
 * @return TCC_Theme_Pagination
 */
if ( ! function_exists( 'fluid_pagination' ) ) {
	function fluid_pagination() {
		static $library;
		if ( empty( $library ) ) {
			$library = new TCC_Theme_Pagination;
		}
		return $library;
	}
}

/**
 * Returns an instance of the Register Sidebars class
 *
 * @since 20180324
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
 * @since 20180403
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
 * Returns an instance of the Theme Sidebar class
 *
 * @since 20180501
 * @param array $args
 * @return TCC_Theme_Sidebar
 */
if ( ! function_exists( 'fluid_sidebar' ) ) {
	function fluid_sidebar( $args = [ 'sidebar' => 'standard' ] ) {
		return new TCC_Theme_Sidebar( $args );
	}
}

/**
 * Returns an instance of the Taxonomy class
 *
 * @since 20180816
 * @param array $args
 * @return TCC_NavWalker_Taxonomy
 */
if ( ! function_exists( 'fluid_taxonomy' ) ) {
	function fluid_taxonomy( $args = array() ) {
		return new TCC_NavWalker_Taxonomy( $args );
	}
}

/**
 * Returns an instance of the Theme Support class
 *
 * @since 20180324
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

/**
 * load the theme text domain
 *
 */
if ( ! function_exists( 'fluid_load_textdomain' ) ) {
	function fluid_load_textdomain(){
		$data = get_file_data( FLUIDITY_HOME . 'style.css', array( 'textdomain' => 'Text Domain', 'domainpath' => 'Domain Path' ) );
		load_theme_textdomain( $data['textdomain'], get_template_directory() . $data['domainpath'] );
	}
	add_action( 'after_setup_theme', 'fluid_load_textdomain' );
}
