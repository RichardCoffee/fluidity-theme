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
		#wp_register_style('my-jquery-ui','http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
		wp_register_style('tcc-autocomplete', get_theme_file_uri("css/ui-autocomplete.css"), null, FLUIDITY_VERSION);
		add_action( 'get_search_form', array( __CLASS__, 'get_search_form' ) );
		add_action( 'wp_print_footer_scripts', array( __CLASS__, 'print_footer_scripts' ), 11 );
		add_action( 'wp_ajax_'.self::$action, array( __CLASS__, 'autocomplete_suggestions' ) );
		add_action( 'wp_ajax_nopriv_'.self::$action, array( __CLASS__, 'autocomplete_suggestions' ) );
	}

    static function get_search_form( $form ) {
        wp_enqueue_script('jquery-ui-autocomplete');
        wp_enqueue_style('tcc-autocomplete');
        return $form;
    }

    static function print_footer_scripts() { ?>
    	<script type="text/javascript">
    		jQuery(document).ready(function ($){
        	var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
        	var ajaxaction = '<?php echo self::$action ?>';
        	$("#secondary #searchform #s").autocomplete({
            delay: 0,
            minLength: 0,
            source: function(req, response){
            	$.getJSON(ajaxurl+'?callback=?&action='+ajaxaction, req, response);
            },
            select: function(event, ui) {
            	window.location.href=ui.item.link;
            },
        	});
    		});
    	</script><?php
    }

    static function autocomplete_suggestions() {
        $posts = get_posts( array(
            's' => trim( esc_attr( strip_tags( $_REQUEST['term'] ) ) ),
        ) );
        $suggestions=array();

        foreach ($posts as $post):
            $suggestion = array();
            $suggestion['label'] = esc_html($post->post_title);
            $suggestion['link'] = get_permalink($post);
            $suggestions[]= $suggestion;
        endforeach;

        $response = $_GET["callback"] . "(" . json_encode($suggestions) . ")";
        echo $response;
        exit;
    }
}

Fluid_AutoComplete::load();
