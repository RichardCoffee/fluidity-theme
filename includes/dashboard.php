<?php


if ( ! function_exists( 'fluid_dashboard_site_activity' ) ) {
log_entry('fluid_dashboard_site_activity');
	add_action( 'wp_dashboard_setup', function() {
log_entry('wp_dashboard_setup');
##		if ( is_blog_admin() ) {
			remove_meta_box( 'dashboard_activity', 'dashboard', 'side' );
#			wp_add_dashboard_widget( 'fluid_dashboard_activity', __( 'Activity' ), 'fluid_dashboard_site_activity' );
##		}
	});
	#  pulled straight from wp-admin/includes/dashboard.php
	function fluid_dashboard_site_activity() {

		echo '<div id="activity-widget">';

		$recent_comments = wp_dashboard_recent_comments();

		$future_posts = wp_dashboard_recent_posts( array(
			'max'     => 5,
			'status'  => 'future',
			'order'   => 'ASC',
			'title'   => __( 'Publishing Soon' ),
			'id'      => 'future-posts',
		) );

		$recent_posts = wp_dashboard_recent_posts( array(
			'max'     => 5,
			'status'  => 'publish',
			'order'   => 'DESC',
			'title'   => __( 'Recently Published' ),
			'id'      => 'published-posts',
		) );

		if ( !$future_posts && !$recent_posts && !$recent_comments ) {
			echo '<div class="no-activity">';
			echo '<p class="smiley" aria-hidden="true"></p>';
			echo '<p>' . __( 'No activity yet!' ) . '</p>';
			echo '</div>';
		}

		echo '</div>';
	}
}
