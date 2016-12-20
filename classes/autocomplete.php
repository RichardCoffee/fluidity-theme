<?php

/*
 *  source:  original source is unknown, somewhere on the web
 *           https://code.tutsplus.com/tutorials/add-jquery-autocomplete-to-your-sites-search--wp-25155
 *           http://wordpress.stackexchange.com/questions/37202/ajax-and-autocomplete
 */

class TCC_AutoComplete {

	static $action = 'tcc_autocomplete';

	static function load() {
		add_action( 'init', array( __CLASS__, 'init'));
	}

	static function init() {
		if (!is_admin()) {
			wp_enqueue_style('tcc-autocomplete-css', get_theme_file_uri('css/ui-autocomplete.css'),	null,	TCC_THEME_VERSION);
		}
		wp_register_script('tcc-autocomplete-js', get_theme_file_uri('js/autocomplete.js'),			array('jquery-ui-autocomplete'),	TCC_THEME_VERSION, true);
		add_action( 'get_search_form',					array( __CLASS__, 'get_search_form' ) );
		add_action( 'wp_ajax_'.self::$action,			array( __CLASS__, 'autocomplete_suggestions' ) );
		add_action( 'wp_ajax_nopriv_'.self::$action, array( __CLASS__, 'autocomplete_suggestions' ) );
	}

	static function get_search_form( $form ) {
		$args = array(	'url'		=> admin_url('admin-ajax.php'),
							'action'	=> self::$action);
		wp_localize_script('tcc-autocomplete-js', 'TccAutocomplete', $args );
		wp_enqueue_script( 'tcc-autocomplete-js');
		return $form;
	}

	static function autocomplete_suggestions() {

		$args   = array( 's' => trim( esc_attr( strip_tags( $_REQUEST['term'] ) ) ) );
		$args   = apply_filters('autocomplete_args', $args);
		$posts  = new WP_Query($args);

#		$option = tcc_estate('search');
		$suggestions = array();
		if ($posts->have_posts()) {
			while ($posts->have_posts()) {
				$posts->the_post();
				$suggestions[] = array('label' => get_the_title(), 'link' => get_permalink());
			}
		}

		$suggestions = apply_filters('autocomplete_array', $suggestions);
		echo $_GET["callback"] . "(" . json_encode($suggestions) . ")";
		exit;

    }
}

TCC_AutoComplete::load();
