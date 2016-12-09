class Incremental_Suggest {

    static function on_load() {

        add_action( 'init', array( __CLASS__, 'init' ) );
    }

    static function init() {

        add_action( 'wp_print_scripts', array( __CLASS__, 'wp_print_scripts' ) );
        add_action( 'get_search_form', array( __CLASS__, 'get_search_form' ) );
        add_action( 'wp_print_footer_scripts', array( __CLASS__, 'wp_print_footer_scripts' ), 11 );
        add_action( 'wp_ajax_incremental_suggest', array( __CLASS__, 'wp_ajax_incremental_suggest' ) );
        add_action( 'wp_ajax_nopriv_incremental_suggest', array( __CLASS__, 'wp_ajax_incremental_suggest' ) );
    }

    static function wp_print_scripts() {

        ?>
    <style type="text/css">
        .ac_results {
            padding: 0;
            margin: 0;
            list-style: none;
            position: absolute;
            z-index: 10000;
            display: none;
            border-width: 1px;
            border-style: solid;
        }

        .ac_results li {
            padding: 2px 5px;
            white-space: nowrap;
            text-align: left;
        }

        .ac_over {
            cursor: pointer;
        }

        .ac_match {
            text-decoration: underline;
        }
    </style>
    <?php
    }

    static function get_search_form( $form ) {

        wp_enqueue_script( 'suggest' );

        return $form;
    }

    static function wp_print_footer_scripts() {

        ?>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#s').suggest('<?php echo admin_url( 'admin-ajax.php' ); ?>' + '?action=incremental_suggest');
        });
    </script><?php
    }

    static function wp_ajax_incremental_suggest() {

        $posts = get_posts( array(
            's' => $_REQUEST['q'],
        ) );

        $titles = wp_list_pluck( $posts, 'post_title' );
        $titles = array_map( 'esc_html', $titles );
        echo implode( "\n", $titles );

        die;
    }
}

Incremental_Suggest::on_load();