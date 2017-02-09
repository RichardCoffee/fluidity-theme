<?php

/*
 * includes/author.php
 *
 */

if ( ! function_exists( 'tcc_before_posts_author' ) ) {
	function tcc_before_posts_author() {
#		$current = ( isset( $_GET['author_name'] ) ) ? get_user_by( 'slug', $author_name ) : get_userdata( intval( $author ) ); wtf?
		$current = get_userdata( get_query_var( 'author', null ) );
		$role    = ( $current ) ? $current->roles[0] : ''; // TODO: filter roles
		get_template_part( 'template-parts/profile', $role ); ?>
		<div class='<?php echo esc_attr( $title_class ); ?>' itemprop='headline'>
			<h3 class='text-center'>
				<?php echo esc_html($title_posts); ?>
			</h3>
		</div><?php
	}
	add_action( 'tcc_before_posts_author', 'tcc_before_posts_author' );
}

if ( ! function_exists( 'tcc_start_author_loop' ) ) {
	function tcc_start_author_loop() {
		$args = array( 'lg' => 4, 'md' => 4, 'sm' => 6, 'xs' => 12 );
		clearfix()->initialize( $args );
	}
	add_action( 'tcc_before_posts_author', 'tcc_start_author_loop', 20 );
}
