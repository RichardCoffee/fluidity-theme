<?php

/*
 *  source:  unknown, somewhere on the web
 */

class Fluid_AutoComplete {

	static $action = 'fluid_autocomplete';

	static function load() {
		add_action( 'init', array( __CLASS__, 'init'));
	}

	static function init() {
		#global $wp_scripts;
		#$jquery_ver = $wp_scripts->registered['jquery-ui-core']->ver;
		#wp_register_style("tcc-jquery-ui-css", "http://ajax.googleapis.com/ajax/libs/jqueryui/$jquery_ver/themes/ui-lightness/jquery-ui.min.css");
		##wp_register_style('my-jquery-ui','http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
		wp_register_style('tcc-autocomplete-css', get_theme_file_uri("css/ui-autocomplete.css"),	null,	FLUIDITY_VERSION);
		wp_register_script('tcc-autocomplete-js', get_theme_file_uri('js/autocomplete.js'),			array('jquery-ui-autocomplete'),	FLUIDITY_VERSION, true);
		add_action( 'get_search_form',					array( __CLASS__, 'get_search_form' ) );
		add_action( 'wp_ajax_'.self::$action,			array( __CLASS__, 'autocomplete_suggestions' ) );
		add_action( 'wp_ajax_nopriv_'.self::$action, array( __CLASS__, 'autocomplete_suggestions' ) );
	}

	static function get_search_form( $form ) {
		wp_enqueue_style('tcc-autocomplete-css');
		$args = array(	'url'		=> admin_url('admin-ajax.php'),
							'action'	=> self::$action);
		wp_localize_script('tcc-autocomplete-js', 'TccAutocomplete', $args );
		wp_enqueue_script( 'tcc-autocomplete-js');
		return $form;
	}

    static function autocomplete_suggestions() {
        $posts = get_posts( array(
            's' => trim( esc_attr( strip_tags( $_REQUEST['term'] ) ) ),
        ) );
        $suggestions=array();

        foreach ($posts as $post) {
            $suggestion = array();
            $suggestion['label'] = esc_html($post->post_title);
            $suggestion['link']  = get_permalink($post);
            $suggestions[]= $suggestion;
        }

        $response = $_GET["callback"] . "(" . json_encode($suggestions) . ")";
        echo $response;
        exit;
    }
}

Fluid_AutoComplete::load();
