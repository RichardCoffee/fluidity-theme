<?php
/**
 * classes/Options/Survey.php
 *
 */
/**
 *  handles survey tasks
 *
 * @since 20180421
 */
class TCC_Options_Survey {

	public $destination = 'richard.coffee@gmail.com';

	public function __construct() {
		$survey = tcc_option( 'survey', 'about', 'no' );
		if ( $survey === 'yes' ) {
			add_action( 'take_fluid_survey', [ $this, 'take_fluid_survey' ] );
			if ( wp_next_scheduled( 'take_fluid_survey' ) === false ) {
				// create datetime object
				$date = new DateTime( date( 'Y-m-d' ) );
				// first day of next month
				$date->modify('first day of 1 month');
				// set time indexes to zero
				$date->setTime( 0, 0, 0 );
				// schedule event
				wp_schedule_event( $date->getTimestamp(), 'monthly', 'take_fluid_survey' );
			}
		}
	}

	public function take_fluid_survey( $args = array() ) {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$plugins = get_plugins();
		$subject = 'Fluid Survey - ' . get_home_url();
		$bodyhdr = __( '', 'tcc-fluid' );
		$body    = print_r( $plugins, true );
		$headers = array(
			'from' => get_bloginfo('admin_email'),
#			'cc'   => get_bloginfo('admin_email')
		);
		wp_mail( $this->destination, $subject, $body, $headers );
	}


}
