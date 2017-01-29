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
#log_entry(self::$terms);
		add_filter( 'wp_nav_menu_objects', array( $this, 'tcc_navmenu_catagory_count' ) );
	}

	public function tcc_navmenu_catagory_count( $items ) {
		if (self::$terms) {
			foreach( $items as $item ) {
				#      Operate on children only                       Check to see if the menu item is in our taxonomy
				if ( ( intval( $item->menu_item_parent, 10 ) > 0 ) && array_key_exists( $item->object_id, self::$terms ) ) {
					$css = ( self::$terms[ $item->object_id ] === 0 ) ? 'no-count' : 'count';
					$css.= " term-count term-count-{$this->taxonomy}";
					$item->title = sprintf( '%s <span class="%s">%s</span>',
					                        $item->title,
					                        esc_attr($css),
					                        number_format_i18n( self::$terms[ $item->object_id ] ) );
					log_entry($item);
				}
			}
		}
		return $items;
	}

	private function get_taxonomy_count() {
		$terms = get_terms( array( 'taxonomy' => $this->taxonomy, 'hide_empty' => false, ) );
#log_entry($terms);
		if ( $terms ) {
			foreach( $terms as $term ) {
				self::$terms[ $term->term_id ] = $term->count;
			}
		}
	}


}
