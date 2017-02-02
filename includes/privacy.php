<?php
/*
 *
 *  sources: https://gist.github.com/mattyrob/2e492e5ecb92233eb307f7efd039c121
 *           https://github.com/dannyvankooten/my-precious
 */

if ( ! function_exists( 'add_privacy_filters' ) ) {
	function add_privacy_filters() {
		add_filter( 'pre_site_option_blog_count', 'privacy_blog_count',    100, 3 );
		add_filter( 'pre_site_option_user_count', 'privacy_user_count',    100, 3 );
#		add_filter( 'http_request_args',          'privacy_request_args', 9999, 2 );
#		add_filter( 'pre_http_request',           'privacy_http_request',    1, 3 );
	}
	add_action( 'wp_version_check', 'add_privacy_filters' );
}

if ( ! function_exists( 'privacy_blog_count' ) ) {
	function privacy_blog_count( $count, $option, $network_id ) {
		$privacy = tcc_privacy( 'blogs' );
log_entry($count,$option,$network_id,$privacy);
		if ( $privacy && ( $privacy === 'no' ) ) {
			return 1;
		}
		return $count;
	}
}

if ( ! function_exists( 'privacy_user_count' ) ) {
	function privacy_user_count( $count, $option, $network_id ) {
		$privacy = tcc_privacy( 'users' );
log_entry($count,$option,$network_id,$privacy);
		if ( $privacy ) {
			switch( $privacy ) {
				case 'all':
					return false;
				case 'some':
					$users = get_user_count();
					return intval( ( $user / 10 ), 10 );
				case 'one':
					return 1;
				case 'many':
					$users = get_user_count();
					return rand( 1, ( $users * 10 ) );
			}
		}
		return get_user_count();
	}
}

if ( ! function_exists( 'privacy_request_args' ) ) {
	function privacy_request_args( $args, $url ) {

log_entry($url,$args);
		#	only act on requests to api.wordpress.org
		if( strpos( $url, '://api.wordpress.org/' ) !== 5 ) {
			return $args;
		}

		#	strip site URL from headers & user-agent
		if ( tcc_privacy( 'blog' ) === 'no' ) {
			if ( isset( $args['headers']['wp_blog'] ) ) { unset( $args['headers']['wp_blog'] ); }
			$args['user-agent'] = sprintf( 'WordPress/%s', $GLOBALS['wp_version'] );
			$args['headers']['User-Agent'] = sprintf( 'WordPress/%s', $GLOBALS['wp_version'] );
		}
		if ( ( tcc_privacy( 'install' ) === 'no' ) || ( tcc_privacy( 'blogs' ) === 'no' ) ) {
			if ( isset( $args['headers']['wp_install'] ) ) { unset( $args['headers']['wp_install'] ); } }

		if ( isset( $args['headers']['Referer'] ) ) {
			unset( $args['headers']['Referer'] );
		}
/*
		if ( strpos( $url, '://api.wordpress.org/plugins/update-check/' === 5 ) ) {
			$my_plugins = array( 'wp-fix/myplugs.php', 'wpmq/wpmq.php', 're-id.php', 'emailer.php', 'esahg/esahg.php', 'subscribe2_html/subscribe2.php', 'wp-db-backup/wp-db-backup.php' );
			$plugins = json_decode( $request['body']['plugins'] );
			foreach ( $my_plugins as $my_plugin ) {
				unset( $plugins->plugins->$my_plugin );
			}
			unset( $plugins->active );
			$request['body']['plugins'] = json_encode( $plugins );
		}
		if ( stripos( $url, '://api.wordpress.org/themes/update-check' ) === 5 ) {
			$themes = json_decode( $request['body']['themes'] );
			unset( $themes->themes->RxPool5 );
			unset( $themes->active );
			$request['body']['themes'] = json_encode( $themes );
		} //*/

log_entry($args);
		return $args;
	}
}

if ( ! function_exists( 'privacy_http_request' ) ) {
	function privacy_http_request( $preempt, $args, $url ) {
log_entry($preempt,$args,$url);
		if( $preempt !== false ) {
			return $preempt;
		}

		#	only act on requests to api.wordpress.org
		if( strpos( $url, '://api.wordpress.org/core/version-check' ) !== 5 ) {
			return $preempt;
		}

		#	did we clean this request already?
		if( ! empty( $args['_privacy_retained'] ) ) {
			return $preempt;
		}

		#	make request
		$args['_privacy_retained'] = true;
		$result = wp_remote_request( $url, $args );
log_entry($result);
		return $result;
	}
}


if ( ! function_exists( 'tcc_privacy' ) ) {
	function tcc_privacy( $option ) {
		static $data;
		if ( empty( $data ) ) {
			$data = get_option( 'tcc_options_privacy' ); }
		if ( isset( $data[ $option ] ) ) {
			return $data[ $option ]; }
		return '';
	}
}
