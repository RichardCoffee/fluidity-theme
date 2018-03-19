<?php

class TCC_Theme_ClearFix {

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
			$init = array_merge( array( 'lg' => 4, 'md' => 4, 'sm' => 6, 'xs' => 12 ), $args );
			$this->parse_args( $init );
			$this->count  = 0;
			$this->active = true;
		}
	}

	public function div_class( $css = '' ) {
		if ( $this->active ) {
			$css .= ( $this->lg ) ? ' col-lg-' . $this->lg : '';
			$css .= ( $this->md ) ? ' col-md-' . $this->md : '';
			$css .= ( $this->sm ) ? ' col-sm-' . $this->sm : '';
			$css .= ( $this->xs ) ? ' col-xs-' . $this->xs : '';
		}
		echo $css;
	}

	public function apply() {
		if ( $this->active ) {
			$this->count++;
			if ( $this->lg && ( $this->count % ( intval( ( 12 / $this->lg ) ) ) === 0 ) ) echo "<div class='clearfix visible-lg-block'></div>";
			if ( $this->md && ( $this->count % ( intval( ( 12 / $this->md ) ) ) === 0 ) ) echo "<div class='clearfix visible-md-block'></div>";
			if ( $this->sm && ( $this->count % ( intval( ( 12 / $this->sm ) ) ) === 0 ) ) echo "<div class='clearfix visible-sm-block'></div>";
			if ( $this->xs && ( $this->count % ( intval( ( 12 / $this->xs ) ) ) === 0 ) ) echo "<div class='clearfix visible-xs-block'></div>";
		}
	}


}
