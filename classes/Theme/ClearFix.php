<?php

class TCC_Theme_ClearFix {

	protected $lx = 0;
	protected $lg = 0;
	protected $md = 0;
	protected $sm = 0;
	protected $xs = 0;

	protected $active = false;
	protected $count  = 0;

	use TCC_Trait_ParseArgs;

	public function __construct() {
		$this->active = false;
	}

	public function initialize( $args ) {
		if ( ! $this->active ) {
			$defaults = array(
				'lx' => 4,
				'lg' => 4,
				'md' => 6,
				'sm' => 12,
				'xs' => 12
			);
			$init = array_merge( $defaults, array_intersect_key( $args, $defaults ) );
			$this->parse_args( $init );
			$this->count  = 0;
			$this->active = true;
		}
	}

	public function div_class( $css = '' ) {
		e_esc_attr( $this->get_div_class( $css ) );
	}

	public function get_div_class( $css = '' ) {
		if ( $this->active ) {
			$css .= ( $this->xl ) ? ' col-xl-' . $this->lg : '';
			$css .= ( $this->lg ) ? ' col-lg-' . $this->lg : '';
			$css .= ( $this->md ) ? ' col-md-' . $this->md : '';
			$css .= ( $this->sm ) ? ' col-sm-' . $this->sm : '';
			$css .= ( $this->xs ) ? ' col-'    . $this->xs : '';
		}
		return $css;
	}

	public function apply() {
		if ( $this->active ) {
			$this->count++;
			if ( $this->xl && ( $this->count % ( intval( ( 12 / $this->xl ) ) ) === 0 ) ) echo "<div class='clearfix visible-xl-block'></div>";
			if ( $this->lg && ( $this->count % ( intval( ( 12 / $this->lg ) ) ) === 0 ) ) echo "<div class='clearfix visible-lg-block'></div>";
			if ( $this->md && ( $this->count % ( intval( ( 12 / $this->md ) ) ) === 0 ) ) echo "<div class='clearfix visible-md-block'></div>";
			if ( $this->sm && ( $this->count % ( intval( ( 12 / $this->sm ) ) ) === 0 ) ) echo "<div class='clearfix visible-sm-block'></div>";
			if ( $this->xs && ( $this->count % ( intval( ( 12 / $this->xs ) ) ) === 0 ) ) echo "<div class='clearfix visible-xs-block'></div>";
		}
	}


}
