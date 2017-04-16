<?php
echo "included " . __FILE__ . "\n";
class TCC_Theme_Page {


	private $slug;

	use TCC_Trait_Singleton;


	protected function __construct( $new_slug = '' ) {
		if ( $new_slug ) {
			$this->slug = $this->set_page_slug( $new_slug );
		}
	}

	public function get_page_slug() {
		if ( empty( $this->slug ) ) {
			$this->slug = $this->determine_page_slug();
		}
		return $this->slug;
	}

	public function set_page_slug( $new_slug ) {
		if ( is_string( $new_slug ) ) {
			$this->slug = sanitize_title( $new_slug );
		}
	}

	#	http://www.wpaustralia.org/wordpress-forums/topic/pre_get_posts-and-is_front_page/
	private function determine_page_slug() {
		$slug = 'page';
		if ( defined( 'TCC_PAGE_SLUG' ) ) {
			$slug = TCC_PAGE_SLUG;
		} else if ( ! is_admin() && $wp_query->is_main_query() ) {
			if ( is_home() && empty( $wp_query->query_string ) ) {
				$slug = 'home';
#			} else if ( ( $wp_query->get( 'page_id' ) === get_option( 'page_on_front' ) && get_option( 'page_on_front' ) ) || empty( $wp_query->query_string ) ) {
			} else if ( ( $front_page = get_option( 'page_on_front' ) ) && ( $wp_query->get( 'page_id' ) === $front_page ) ) {
				$slug = 'front';
			} else {
				$page = get_queried_object();  #  $wp_query->queried_object
				if ( is_object( $page ) && isset( $page->post_type ) && ( $page->post_type === 'page' ) ) {
					$slug = $page->post_name;
				}
			}
		}
		return $slug;
	}

}
