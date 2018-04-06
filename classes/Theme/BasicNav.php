<?php
/**
 * classes/Theme/BasicNav.php
 *
 * @package Fluidity
 * @subpackage Navigation
 * @since 2.3.0
 */
/**
 * abstract class that provides generic post navigation functions
 *
 * @since 2.3.0
 */
abstract class TCC_Theme_BasicNav {

	/**
	 * css class assigned to <nav> object
	 *
	 * @since 2.3.0
	 * @var string
	 */
	protected $nav_css = 'posts-navigation';

	/**
	 * text used for title and aria-label on <nav> object
	 *
	 * @since 2.3.0
	 * @var string
	 */
	protected $sr_text = '';

	use TCC_Trait_Attributes;
	use TCC_Trait_ParseArgs;

	/**
	 * method to generate links html
	 *
	 * @since 2.3.0
	 */
	abstract protected function generate_links();

	/**
	 * basic construct method for class
	 *
	 * @since 2.3.0
	 * @param array $args
	 */
	public function __construct( $args = array() ) {
		$this->sr_text = __( 'Post navigation' ,' tcc-fluid' );
		$this->parse_args( $args );
	}

	/**
	 * replicates wordpress navigation template use
	 *
	 * @since 2.3.0
	 * @return string
	 */
	protected function generate_navigation() {
		$template = apply_filters( 'navigation_markup_template', null, $this->nav_css );
		if ( $template ) {
			$html = sprintf( $template, sanitize_html_class( $this->nav_css ), esc_html( $this->sr_text ), $this->generate_links() );
		} else {
			if ( $template = $this->generate_markup() ) {
				$html = sprintf( $template, $this->generate_links() );
			} else {
				$html = '';
			}
		}
		return $html;
	}

	/**
	 * creates div.nav-links html for wrapping navigation links
	 *
	 * @since 2.3.0
	 * @return string
	 */
	protected function generate_markup() {
		$attrs = array(
			'class' => 'navigation noprint ' . $this->nav_css,
			'title' => $this->sr_text,
			'aria-label' => $this->sr_text,
			'role'  => 'navigation',
		);
		$html = $this->get_apply_attrs_tag( 'nav', $attrs );
		$html.= '<div class="nav-links">%s</div>';
		$html.= '</nav>';
		return $html;
	}
/*
	protected function generate_links() {
		if ( ( ! $this->show_older ) && ( ! $this->show_newer ) ) {
			return '';
		}
		ob_start(); ?>
		<div class="row">
			<ul class="<?php echo esc_attr( $this->ul_css ); ?>"><?php
				if ( $this->show_older ) {
					$li_attrs = array(
						'class' => 'previous '. $this->li_css,
						'title' => $this->older_link,
					);
					$this->apply_attrs_tag( 'li', $li_attrs );
						previous_post_link( '%link', $this->left, $this->same_term, $this->excluded_terms, $this->taxonomy ); ?>
					</li><?php
				}
				if ( $this->show_newer ) {
					$li_attrs = array(
						'class' => 'next '. $this->li_css,
						'title' => $this->newer_link,
					);
					$this->apply_attrs_tag( 'li', $li_attrs );
						next_post_link( '%link', $this->right, $this->same_term, $this->excluded_terms, $this->taxonomy ); ?>
					</li><?php
				} ?>
			</ul>
		</div><?php
		return ob_get_clean();
	} //*/


}
