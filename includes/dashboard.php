<?php


if ( ! function_exists( 'fluid_dashboard_site_activity' ) ) {

	add_action( 'wp_dashboard_setup', function() {
		if ( is_blog_admin() ) {
			remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
			wp_add_dashboard_widget( 'fluid_dashboard_activity', __( 'Activity' ), 'fluid_dashboard_site_activity' );
		}
	});

	#  pulled straight from wp-admin/includes/dashboard.php
	function fluid_dashboard_site_activity() {
		echo '<div id="activity-widget">';
		$recent_comments = wp_dashboard_recent_comments();  #  id = 'latest-comments'
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

	function fluid_site_activity_css() {
		if ( get_current_screen() === 'dashboard' ) {
			echo "\n#latest-comments {\n\tdisplay: grid;\n}\n";
#			echo "\n#latest-comments > ul.subsubsub {\n\twidth: 100%;\n}\n";
		}
	}
	add_action('tcc_custom_css_admin', 'fluid_site_activity_css' );

}
