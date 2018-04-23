<?php

class TCC_NavWalker_Taxonomy extends Walker_Nav_Menu {

	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {

fluid()->log(func_get_args());

		if (!$element) return;

		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );

	}

}
