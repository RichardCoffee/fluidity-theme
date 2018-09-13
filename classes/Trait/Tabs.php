<?php
/**
 *  insert tab functions
 *
 * @package Fluidity
 * @subpackage Traits
 * @since 20180913
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 */
/**
 *  check for wordpress
 */
defined( 'ABSPATH' ) || exit;
/**
 *  adds methods to handle displaying tabs and tab content
 *
 * @since 20180913
 */
trait TCC_Trait_Tabs {

	/**
	 *  associative array of all tabs
	 *
	 * @since 20180913
	 * @var array
	 */
	protected $tabs = array();
	/**
	 *  the active tab
	 *
	 * @since 20180913
	 * @var string
	 */
	protected $tab_active_key = 'the-active-tab-key';
	/**
	 *  set to true to use fade effect
	 *
	 * @since 20180913
	 * @var bool
	 */
	protected $tab_fade_effect = false;

	/**
	 *  display the tabs
	 *
	 * @since 20180913
	 * @uses TCC_Trait_Attributes::apply_attrs()
	 * @uses WordPress::sanitize_title()
	 * @uses TCC_Trait_Attributes::get_element()
	 * @uses TCC_Trait_Attributes::element()
	 */
	protected function tabs() {
		$ul = array(
			'class' => 'nav nav-tabs',
			'role'  => 'tablist',
		); ?>
		<ul <?php $this->apply_attrs( $ul ); ?>><?php
			$li = array(
				'role' => 'presentation'
			);
			foreach ( $this->tabs as $tab => $title ) {
				$id = sanitize_title( $tab );
				$attrs = array(
					'href' => '#' . $id,
					'role' => 'tab',
					'aria-controls' => $id,
					'data-toggle'   => 'tab',
				);
				$anchor = $this->get_element( 'a', $attrs, $title );
				$li = ( $this->tab_active_key === $id ) ? [ 'class' => 'active', 'role' => 'presentation' ] : [ 'role' => 'presentation' ];
				$this->element( 'li', $li, $anchor, true );
			} ?>
		</ul><?php
	}

	/**
	 *  display the tab content using class methods
	 *
	 * @since 20180913
	 * @uses WordPress::sanitize_title()
	 * @uses TCC_Trait_Attributes::tag()
	 */
	protected function tab_content() {
		$panel = array(
			'role'  => 'tabpanel',
			'class' => 'tab-pane' . ( ( $this->tab_fade_effect ) ? ' fade' : ''),
		);
		foreach( $this->tabs as $tab => $title ) {
			$id = sanitize_title( $tab );
			$method = 'tab_content_ ' . $id;
			if ( method_exists( $this, $method ) ) {
				$attrs = $this->tab_check_active_key( $panel, $id );
				$this->tag( 'div', $attrs );
					$this->$method();
				echo '</div>';
			}
else { fluid()->log( $method ); }
		}
	}

	/**
	 *  add class for active tab
	 *
	 * @since 20180913
	 * @param array $attrs
	 * @param string $tab
	 * @return array
	 */
	private function tab_check_active_key( $attrs, $tab ) {
		$attrs['id'] = $tab;
		if ( $this->tab_active_key === $tab ) {
			$attrs['class'] .= ' active';
		}
		return $attrs;
	}


}
