<?php
/*
 *
 *  sources: https://core.trac.wordpress.org/ticket/16778
 *           https://gist.github.com/mattyrob/2e492e5ecb92233eb307f7efd039c121
 *           https://github.com/dannyvankooten/my-precious
 */

defined('ABSPATH') || exit;


class Privacy_My_Way {

	protected $options;

	private $blog_count_done = false; # debug symbol
	private $user_count_done = false; # debug symbol

	use TCC_Trait_Singleton;

	protected function __construct() {
		$this->get_options();
		#	I don't think these first two filters work as is - the callbacks are never being called - testing continues
		add_filter( 'pre_site_option_blog_count', array( $this, 'pre_site_option_blog_count' ),     10, 3 );
		add_filter( 'pre_site_option_user_count', array( $this, 'pre_site_option_user_count' ),     10, 3 );
		add_filter( 'http_request_args',          array( $this, 'http_request_args' ),            9999, 2 );
		add_filter( 'pre_http_request',           array( $this, 'pre_http_request' ),                2, 3 );
	}

	public function pre_site_option_blog_count( $count, $option, $network_id ) {
log_entry($count,$option,$network_id);
		if ( $this->options['blogs'] === 'no' ) {
			$count = 1;
		} //*/
$this->blog_count_done = true;  // for debugging
		return $count;
	}

	public function pre_site_option_user_count( $count, $option, $network_id ) {
		$privacy = $this->options['users'];
log_entry($count,$option,$network_id,$privacy);
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
log_entry($count);
$this->user_count_done = true; // debugging
		return $count;
	}

	public function http_request_args( $args, $url ) {

		#	only act on requests to api.wordpress.org
		if ( stripos( $url, '://api.wordpress.org/' ) !== false ) {
			return $args;
		}

log_entry($url,$args);
return $args;
#	These lines are commented out because I consider including the url in user-agent as a matter of courtesy.
#		Besides, what is the point in not giving them your website url?  Don't you want more people to see it?
#		Privacy does not mean you can't say hi to your neighbors.  I really think this whole header section is a moot point.
#		Also, what if devs at wordpress.org cause the version check/update to fail because of no url?
		#	strip site URL from headers & user-agent
/*		if ( $this->options['blog'] === 'no' ) {
			if ( isset( $args['headers']['wp_blog'] ) ) {
				$args['headers']['wp_blog'] = network_site_url(); }
			if ( isset( $args['headers']['user-agent'] ) ) {
				$args['user-agent'] = sprintf( 'WordPress/%s', $GLOBALS['wp_version'] ); }
			if ( isset( $args['headers']['User-Agent'] ) ) { // Anybody seen this here?
				$args['headers']['User-Agent'] = sprintf( 'WordPress/%s', $GLOBALS['wp_version'] ); }
		} //*/
log_entry($url,$args);
return $args;
/*		if ( ( $this->options['install'] === 'no' ) || ( $this->options['blogs'] === 'no' ) ) {
			if ( isset( $args['headers']['wp_install'] ) ) {
				$args['headers']['wp_install'] = network_site_url(); // whoa, what? isn't this the same thing?
			}
		} //*/
		#	Why remove this?
/*		if ( isset( $args['headers']['Referer'] ) ) {
			unset( $args['headers']['Referer'] ); } //*/
log_entry($url,$args);
return $args;
/*		if ( stripos( $url, '://api.wordpress.org/plugins/update-check/' ) !== false ) {
			$plugin_filter = $this->options['plugin_list'];
			$plugins = json_decode( $args['body']['plugins'] );
log_entry($plugins);
return $args;
			foreach ( $plugin_filter as $plugin => $status ) {
				if ( $status === 'no' ) {
					if ( isset( $plugins->plugins->$plugin ) ) {
						unset( $plugins->plugins->$plugin );
					if ( isset( $plugins->active->$plugin ) ) {
						unset( $plugins->active->$plugin );
				}
			}
			$args['body']['plugins'] = json_encode( $plugins );
		} //*/
log_entry($url,$args);
return $args;
		if ( stripos( $url, '://api.wordpress.org/themes/update-check/' ) !== false ) {
			$theme_filter = $this->options['theme_list'];
			$themes = json_decode( $args['body']['themes'] );
			foreach ( $theme_filter as $theme => $status ) {
				if ( $status === 'no' ) {
					if ( isset( $themes->plugins->$theme ) ) {
						unset( $themes->plugins->$theme );
					}
					if ( isset( $themes->active->$theme ) ) {
						unset( $themes->active->$theme );
					}
				}
			}
			$args['body']['themes'] = json_encode( $themes );
		} //*/
log_entry($args,$themes);
		return $args;
	}

	public function pre_http_request( $preempt, $args, $url ) {
		#	check if we, or someone else, has been here before
		if ( $preempt || isset( $args['_privacy_filter'] ) ) {
			return $preempt;
		}
		#	only act on requests to api.wordpress.org
		if  ( ( stripos( $url, '://api.wordpress.org/core/version-check/'   ) === false )
			&& ( stripos( $url, '://api.wordpress.org/plugins/update-check/' ) === false )
			&& ( stripos( $url, '://api.wordpress.org/themes/update-check/'  ) === false )
#			&& ( stripos( $url, '://api.wordpress.org/translations/'         ) === false )	#	outgoing never has data - need to see what this looks like
			) {
			return $preempt;
		}
log_entry($url,$args);
return $preempt;
		#	Remove/Change url args
		#$keys = array( 'php', 'locale', 'mysql', 'local_package', 'blogs', 'users', 'multisite_enabled', 'initial_db_version',);
		$url_array = parse_url($url);
		$arg_array = wp_parge_args( $url_array['query'] );
log_entry($url,$args,$url_array,$arg_array);
return $preempt;
		if ( isset( $arg_array['blogs'] ) ) {
			$blogs = $this->pre_site_option_blog_count( $arg_array['blogs'], 'fluid_blog_count', '' );
			$url   = add_query_arg( 'blogs', $blogs, $url );
		}
log_entry($url,$args,$url_array,$arg_array);
return $preempt;
		if ( isset( $arg_array['users'] ) ) {
			$users = $this->pre_site_option_user_count( $arg_array['users'], 'fluid_user_count', '' );
			$url   = add_query_arg( 'users', $users, $url );
		}
log_entry($url,$args,$url_array,$arg_array);
return $preempt;
		if ( isset( $arg_array['multisite_enabled'] ) && ( $this->options['blogs'] === 'no' ) ) {
			$arg_array['multisite_enabled'] = 0;
			$url = add_query_arg( 'multisite_enabled', '0', $url );
		}
log_entry($url,$args,$url_array,$arg_array);
return $preempt;
		#	make request
		$args['_privacy_filter'] = true;
log_entry($url,$args);
return $preempt;
		$result = wp_remote_request( $url, $args );
log_entry($result);
		return $result;
	}

	private function get_options() {
		$options = get_option( 'tcc_options_privacy' );
		if ( ! $options ) {
			// TODO
		}
		$this->options = $options;
	}


} # end of class Privacy_My_Way
