<?php

class TCC_Theme_ClearFix {

	private $lg = 0;
	private $md = 0;
	private $sm = 0;
	private $xs = 0;

	private $active = false;
	private $count  = 0;

	use TCC_Trait_ParseArgs;
	use TCC_Trait_Singleton;

	protected function __construct() {
		$this->active = false;
	}

	public function initialize( $args ) {
		if ( ! $this->active ) {
			$this->parse_args( $args );
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
