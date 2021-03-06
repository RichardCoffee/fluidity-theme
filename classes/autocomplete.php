<?php

/*
 *  source:  original source is unknown, somewhere on the web
 *           https://code.tutsplus.com/tutorials/add-jquery-autocomplete-to-your-sites-search--wp-25155
 *           http://wordpress.stackexchange.com/questions/37202/ajax-and-autocomplete
 */

class TCC_AutoComplete {

	static $action = 'fluid_autocomplete';

	static function load() {
		add_action( 'init', [ __CLASS__, 'init' ] );
	}

	static function init() {
		if ( wp_doing_ajax() ) {
			add_action( 'wp_ajax_' . static::$action,      [ __CLASS__, 'autocomplete_suggestions' ] );
			add_action( 'wp_ajax_nopriv_'.static::$action, [ __CLASS__, 'autocomplete_suggestions' ] );
		} else if ( ! is_admin() ) {
			wp_enqueue_style(   'fluid-autocomplete-css', get_theme_file_uri( 'css/ui-autocomplete.css' ), null, FLUIDITY_VERSION );
			wp_register_script( 'fluid-autocomplete-js',  get_theme_file_uri( 'js/autocomplete.js' ), [ 'jquery-ui-autocomplete' ], FLUIDITY_VERSION, true );
			add_action( 'pre_get_search_form', [ __CLASS__, 'pre_get_search_form' ] );
		}
	}

	static function pre_get_search_form() {
		$args = array(
			'url'    => admin_url( 'admin-ajax.php' ),
			'action' => static::$action
		);
		wp_localize_script( 'fluid-autocomplete-js', 'FluidAutocomplete', $args );
		wp_enqueue_script( 'fluid-autocomplete-js' );
	}

	static function autocomplete_suggestions() {
		$args  = array(
			'post_status' => 'publish',
			'nopaging'    => true,
			's' => trim( esc_attr( wp_strip_all_tags( wp_unslash( $_REQUEST['term'] ) ) ) )
		);
		$args  = apply_filters( 'autocomplete_args', $args );
		$posts = new WP_Query( $args );
#fluid(1)->log($_REQUEST,$args,$posts);
		$suggestions = array();
		if ( $posts->have_posts() ) {
			while ( $posts->have_posts() ) {
				$posts->the_post();
				$suggestions[] = array(
					'label' => get_the_title(),
					'link'  => get_permalink()
				);
			}
		}
		$suggestions = apply_filters( 'autocomplete_array', $suggestions );
		wp_send_json( $suggestions );
	}


}

TCC_AutoComplete::init();
