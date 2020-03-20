<?php
/**
 *  Provides layout for API Control options.
 *
 * @package Fluidity
 * @subpackage Options
 * @since 20170503
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2017, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/Options/APIControl.php
 */
defined( 'ABSPATH' ) || exit;

class TCC_Options_APIControl extends TCC_Options_Options {


	/**
	 * @since 20170503
	 * @var string  Layout slug.
	 */
	protected $base     = 'apicontrol';
	/**
	 * @since 20170503
	 * @var integer  Layout priority, or where it shows up in the order.
	 */
	protected $priority = 570;


	/**
	 * @since 20200320
	 * @link https://github.com/RichardCoffee/custom-post-type/blob/master/classes/Trait/Singleton.php
	 */
	use TCC_Trait_Singleton;


	/**
	 *  Provides the layout title.
	 *
	 * @since 20170503
	 * @return string  Layout title.
	 */
	protected function form_title() {
		return __( 'API Control', 'tcc-fluid' );
	}

	/**
	 *  Provides the icon slug.
	 *
	 * @since 20180924
	 * @return string  Icon slug.
	 */
	protected function form_icon() {
		return 'dashicons-heart';
	}

	/**
	 *  Display the layout description.
	 *
	 * @since 20170503
	 */
	public function describe_options() {
		esc_html_e( 'REST API Control', 'tcc-fluid' );
	}

	/**
	 *  Provides the form layout information.
	 *
	 * @since 20170503
	 * @return array  The form layout.
	 */
	protected function options_layout( $all = false ) {
		$layout = array(
			'default' => true,
			'heart' => array(
				'default' => 'on',
				'label'   => __( 'WP Heartbeat', 'tcc-fluid' ),
				'text'    => __( 'Control the status of the WordPress Heartbeat API', 'tcc-fluid' ),
				'help'    => __( 'The Heartbeat API will always remain active on these pages: post.php, post-new.php, and admin.php', 'tcc-fluid' ),
				'render'  => 'radio',
				'source'  => array(
					'on'   => __( 'On', 'tcc-fluid' ),
					'off'  => __( 'Off', 'tcc-fluid' ),
				),
			),
			'status' => array(
				'default' => 'on',
				'label'   => __( 'REST API', 'tcc-fluid' ),
				'text'    => __( 'Control access to your site REST API', 'tcc-fluid' ),
				'help'    => __( 'Be very careful with this option.  Any value other than ON runs the risk of breaking your site!', 'tcc-fluid' ),
				'render'  => 'radio',
				'source'  => array(
					'on'     => __( 'On - the default value.', 'tcc-fluid' ),
					'admin'  => __( 'Allow access to admin only.', 'tcc-fluid' ),
					'users'  => __( 'Allow access to logged-in users only.', 'tcc-fluid' ),
					'filter' => __( 'Filter public access.', 'tcc-fluid' ),
					'off'    => __( 'Off - this may break things.', 'tcc-fluid' ),
				),
				'showhide' => array(
					'origin' => 'master-rest-api',
					'target' => 'control-rest-api',
					'show'   => 'filter',
				),
				'divcss' => 'master-rest-api',
			),
			'namespaces' => array(
				'label'  => __( 'Namespaces', 'tcc-fluid' ),
				'text'   => __( 'Control established routes', 'tcc-fluid' ),
				'render' => 'display',
				'divcss' => 'control-rest-api',
			),
		);
		$endpoints = $this->get_endpoints();
		if ( $endpoints ) {
			$route_text = __( 'Check to block access to these routes.', 'tcc-fluid' );
			foreach( $endpoints as $key => $routes ) {
				$layout_key = 'ep/' . $key;
				$source     = array();
				foreach( $routes as $route ) {
					$source[ $route ] = ucwords( $route );
				}
				$layout[ $layout_key ] = array(
					'label'  => $key,
					'text'   => $route_text,
					'render' => 'checkbox_multiple',
					'source' => $source,
					'divcss' => 'control-rest-api',
				);
			}
		}
$this->get_allowed_endpoints();
		return $layout;
	}

	/**
	 *  Get all available endpoints.
	 *
	 * @since 20170504
	 * @return array  List of endpoints.
	 */
	private function get_endpoints() {
		$request  = new WP_REST_Request( 'GET', '/' );
		$response = rest_do_request( $request );
		$data     = $response->get_data();
		$namespace= $data['namespaces'];
		$routes   = $data['routes'];
		$linked   = array();
		foreach( $routes as $key => $route ) {
			$namespace = $route['namespace'];
			if ( $namespace ) {
				$parsed = explode( '/', $key );
				if ( ! empty( $parsed[3] ) ) {
					if ( ! isset( $linked[ $namespace ] ) ) {
						$linked[ $namespace ] = array();
					}
					if ( ! in_array( $parsed[3], $linked[ $namespace ], true ) ) {
						$linked[ $namespace ][] = $parsed[3];
					}
				}
			}
		}
		return $linked;
	}

	/**
	 *
	 * @since 20170504
	 */
	public function get_allowed_endpoints() {
		$options = get_option( 'tcc_options_apicontrol' );
#		fluid()->log( $options );
	}

	/**
	 *  Customizer layout data.
	 *
	 * @since 20180404
	 * @return array  Customizer data.
	 */
	protected function customizer_data() {
		$data = array(
			array(
			),
		);
		return apply_filters( "fluid_{$this->base}_customizer_data", $data );
	}


}
