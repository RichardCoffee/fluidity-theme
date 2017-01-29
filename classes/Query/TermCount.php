<?php
/**
 * Class TCC_Query_TermCount
 *
 * Add support for inserting term counts in nav menus
 *
 * @since 2.1.1
 *
 */
class TCC_Query_TermCount {

	protected $taxonomy = 'category';

	protected static $terms;

	use TCC_Trait_Singleton;
	use TCC_Trait_ParseArgs;

	protected function __construct( $args ) {
		$this->parse_args( $args );
		self::$terms = get_terms( array( 'taxonomy' => $this->taxonomy, 'hide_empty' => false, ) );
log_entry(self::$terms);
		add_filter( 'wp_nav_menu_objects', array( $this, 'tcc_navmenu_catagory_count' ) );
	}

	public function tcc_navmenu_catagory_count( $items ) {
		foreach( $items as $item ) {
			if ( $item->object === $this->taxonomy ) {
				log_entry($item);
			}
		}
		return $items;
	}


}
