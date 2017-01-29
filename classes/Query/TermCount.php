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

	protected static $terms = array();

	use TCC_Trait_ParseArgs;

	public function __construct( $args = array() ) {
		$this->parse_args( $args );
		$this->get_taxonomy_count();
log_entry(self::$terms);
		add_filter( 'wp_nav_menu_objects', array( $this, 'tcc_navmenu_catagory_count' ) );
	}

	public function tcc_navmenu_catagory_count( $items ) {
		if (self::$terms) {
			foreach( $items as $item ) {
				#    Check for taxonomy                     Children only
				if ( $item->object === $this->taxonomy && ( intval( $item->menu_item_parent, 10 ) > 0 ) ) {
					log_entry($item);
				}
			}
		}
		return $items;
	}

	private function get_taxonomy_count() {
		$terms = get_terms( array( 'taxonomy' => $this->taxonomy, 'hide_empty' => false, ) );
log_entry($terms);
		if ( $terms ) {
			foreach( $terms as $term ) {
				self::$terms[ $term->term_id ] = $term->count;
			}
		}
	}


}
