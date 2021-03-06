<?php

#  Reference:  https://neliosoftware.com/blog/top-6-secrets-successful-internationalization/

require_once( '/home/oem/work/svn/wpdev/tools/i18n/makepot.php' );
require_once( '/home/oem/work/php/fluidity/includes/debugging.php' );

date_default_timezone_set('UTC');

class Fluid_MakePOT extends MakePOT {

	public $new_rules = array(
		'esc_attr_ex'  => array( 'string',   'context' ),
		'esc_html_ex'  => array( 'string',   'context' ),
		'esc_html_n'   => array( 'singular', 'plural'  ),
		'esc_attr_nx'  => array( 'singular', 'plural', null, 'context' ),
		'esc_html_nx'  => array( 'singular', 'plural', null, 'context' ),
		'esc_html_enx' => array( 'singular', 'plural', null, 'context' ),
	);

	public function __construct() {
		$this->rules = array_merge( $this->rules, $this->new_rules );
		$this->meta['wp-plugin']['msgid-bugs-address'] = 'http://rtcenterprises.net/forums/forum/themes/';
		$this->meta['wp-theme' ]['msgid-bugs-address'] = 'http://rtcenterprises.net/forums/forum/themes/';
		parent::__construct();
		$this->extractor = new Fluid_StringExtractor( $this->rules );
	}


}

class Fluid_StringExtractor extends StringExtractor {

	function extract_from_directory( $dir, $excludes = array(), $includes = array(), $prefix = '' ) {
		$dir_excludes = array(
			'.git',
			'.sass-cache',
			'css',
			'docs',
			'fonts',
			'icons',
			'images',
			'js',
			'languages',
			'scss',
			'tests',
			'vendor',
		);
		if ( in_array( $dir, $dir_excludes, true ) ) {
			$translations = new Translations;
		} else {
echo "entering directory: $dir\n";
			$translations = parent::extract_from_directory( $dir, $excludes, $includes, $prefix );
		}
		return $translations;
	}

}

# copied straight from the dev file
// run the CLI only if the file
// wasn't included
$included_files = get_included_files();
if ($included_files[0] == __FILE__) {
	$makepot = new Fluid_MakePOT;
	if ((3 == count($argv) || 4 == count($argv)) && in_array($method = str_replace('-', '_', $argv[1]), get_class_methods($makepot))) {
		$res = call_user_func(array($makepot, $method), realpath($argv[2]), isset($argv[3])? $argv[3] : null);
		if (false === $res) {
			fwrite(STDERR, "Couldn't generate POT file!\n");
		}
	} else {
		$usage  = "Usage: php makepot.php PROJECT DIRECTORY [OUTPUT]\n\n";
		$usage .= "Generate POT file from the files in DIRECTORY [OUTPUT]\n";
		$usage .= "Available projects: ".implode(', ', $makepot->projects)."\n";
		fwrite(STDERR, $usage);
		exit(1);
	}
}
