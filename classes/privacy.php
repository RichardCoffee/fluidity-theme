<?php
/*
 *
 *  sources: https://gist.github.com/mattyrob/2e492e5ecb92233eb307f7efd039c121
 *           https://github.com/dannyvankooten/my-precious
 */

defined('ABSPATH') || exit;


class Privacy_My_Way {

	protected $options;

	use TCC_Trait_Singleton;

	protected function __construct() {
		$this->get_options();
log_entry($this->options);
		add_filter( 'pre_site_option_blog_count', array( $this, 'pre_site_option_blog_count' ),     10, 3 );
		add_filter( 'pre_site_option_user_count', array( $this, 'pre_site_option_user_count' ),     10, 3 );
		add_filter( 'http_request_args',          array( $this, 'http_request_args' ),            9999, 2 );
		add_filter( 'pre_http_request',           array( $this, 'pre_http_request' ),                2, 3 );
	}

	public function pre_site_option_blog_count( $count, $option, $network_id ) {

log_entry($count,$option,$network_id);
/*
		if ( $this->options === 'no' ) {
			$count = 1;
		} //*/
		return $count;
	}

	public function pre_site_option_user_count( $count, $option, $network_id ) {
		$privacy = $this->options[ 'users' ];

log_entry($count,$option,$network_id,$privacy);
/*
		if ( $privacy ) {
			switch( $privacy ) {
				case 'all':
					$count = false;
				case 'some':
					$users = get_user_count();
					$count = intval( ( $user / rand(1, 10) ), 10 );
				case 'one':
					$count = 1;
				case 'many':
					$users = get_user_count();
					$count = rand( 1, ( $users * 10 ) );
				default:
			}
		} //*/

#log_entry($count);

		return $count;
	}

	public function http_request_args( $args, $url ) {

		#	only act on requests to api.wordpress.org
		if ( stripos( $url, '://api.wordpress.org/' ) !== false ) {
			return $args;
		}

log_entry($url,$args);
/*
		#	strip site URL from headers & user-agent
		if ( $this->options['blog'] === 'no' ) {
			if ( isset( $args['headers']['wp_blog'] ) ) {
				unset( $args['headers']['wp_blog'] ); }
			if ( isset( $args['headers']['user-agent'] ) ) {
				$args['user-agent'] = sprintf( 'WordPress/%s', $GLOBALS['wp_version'] ); }
			if ( isset( $args['headers']['User-Agent'] ) ) {
				$args['headers']['User-Agent'] = sprintf( 'WordPress/%s', $GLOBALS['wp_version'] ); }
		}
/*
		if ( ( $this->options['install'] === 'no' ) || ( $this->options['blogs'] === 'no' ) ) {
			if ( isset( $args['headers']['wp_install'] ) ) {
				unset( $args['headers']['wp_install'] );
			}
		}

		if ( isset( $args['headers']['Referer'] ) ) {
			unset( $args['headers']['Referer'] ); }
/*
		if ( stripos( $url, '://api.wordpress.org/plugins/update-check/' ) !== false ) {
			$plugin_filter = $this->options['plugin_list'];
			$plugins = json_decode( $request['body']['plugins'] );
log_entry($plugins);
			foreach ( $plugin_filter as $plugin => $status ) {
				if ( $status === 'no' ) {
					if ( isset( $plugins->plugins->$plugin ) ) {
						unset( $plugins->plugins->$plugin );
					if ( isset( $plugins->active->$plugin ) ) {
						unset( $plugins->active->$plugin );
				}
			}
			$request['body']['plugins'] = json_encode( $plugins );
		}
/*
		if ( stripos( $url, '://api.wordpress.org/themes/update-check' ) !== false ) {
			$themes = json_decode( $request['body']['themes'] );
			unset( $themes->themes->RxPool5 );
			unset( $themes->active );
			$request['body']['themes'] = json_encode( $themes );
		} //*/

#log_entry($args);

		return $args;
	}

	public function pre_http_request( $preempt, $args, $url ) {

log_entry($preempt,$args,$url);

		if ( $preempt || isset( $args['_privacy_filter'] ) ) {
			return $preempt;
		}

		#	only act on requests to api.wordpress.org
		if ( ( stripos( $url, '://api.wordpress.org/core/version-check' ) === false ) &&
		     ( stripos( $url, '://api.wordpress.org/plugins/'           ) === false ) &&
		     ( stripos( $url, '://api.wordpress.org/themes/'            ) === false ) &&
		     ( stripos( $url, '://api.wordpress.org/translations/'      ) === false ) ) {
			return $preempt;
		}

return $preempt;

		#	make request
		$args['_privacy_filter'] = true;
		$result = wp_remote_request( $url, $args );

log_entry($result);

		return $result;
	}

	private function get_options() {
		$options = get_option( 'tcc_options_privacy' );
		if ( ! $options ) {
			
		}
		$this->options = $options;
	}


} # end of class Privacy_My_Way
