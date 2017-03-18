<?php

#define( 'FLUIDITY_HOME', '~/www/wp-content/themes/fluidity/' );
#require_once( '../includes/loader.php' );

require_once( '../classes/Trait/Singleton.php' );

abstract class Abstract_Test {


}

class Singleton_Test extends Abstract_Test {

    use TCC_Trait_Singleton;

    protected function __construct() {
        echo "\nCreated Singleton_Test instance\n";
    }

}

$instance = Singleton_Test::instance();
