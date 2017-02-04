<?php
/*
 *
 *  sources: https://gist.github.com/mattyrob/2e492e5ecb92233eb307f7efd039c121
 *           https://github.com/dannyvankooten/my-precious
 */
/*
if ( ! function_exists( 'privacy_blog_count' ) ) {
	function privacy_blog_count( $count, $option, $network_id ) {
		$privacy = tcc_privacy( 'blogs' );

log_entry($count,$option,$network_id,$privacy);
/*
		if ( $privacy && ( $privacy === 'no' ) ) {
			return 1;
		} //* /
		return $count;
	}
}

if ( ! function_exists( 'privacy_user_count' ) ) {
	function privacy_user_count( $count, $option, $network_id ) {
		$privacy = tcc_privacy( 'users' );

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
		} //* /

#log_entry($count);

		return $count;
	}
}

if ( ! function_exists( 'privacy_request_args' ) ) {
	function privacy_request_args( $args, $url ) {

		#	only act on requests to api.wordpress.org
		if ( stripos( $url, '://api.wordpress.org/' ) !== false ) {
			return $args;
		}

log_entry($url,$args);
/*
		#	strip site URL from headers & user-agent
		if ( tcc_privacy( 'blog' ) === 'no' ) {
			if ( isset( $args['headers']['wp_blog'] ) ) {
				unset( $args['headers']['wp_blog'] ); }
			if ( isset( $args['headers']['user-agent'] ) ) {
				$args['user-agent'] = sprintf( 'WordPress/%s', $GLOBALS['wp_version'] ); }
			if ( isset( $args['headers']['User-Agent'] ) ) {
				$args['headers']['User-Agent'] = sprintf( 'WordPress/%s', $GLOBALS['wp_version'] ); }
		}
/*
		if ( ( tcc_privacy( 'install' ) === 'no' ) || ( tcc_privacy( 'blogs' ) === 'no' ) ) {
			if ( isset( $args['headers']['wp_install'] ) ) {
				unset( $args['headers']['wp_install'] );
			}
		}

		if ( isset( $args['headers']['Referer'] ) ) {
			unset( $args['headers']['Referer'] ); }
/*
		if ( stripos( $url, '://api.wordpress.org/plugins/update-check/' ) !== false ) {
			$plugin_filter = tcc_privacy( 'plugin_list' );
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
		} //* /

#log_entry($args);

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
		if( strpos( $url, '://api.wordpress.org/core/version-check' ) !== false ) {
			return $preempt;
		}

		#	did we clean this request already?
		if( ! empty( $args['_privacy_retained'] ) ) {
			return $preempt;
		}

return $preempt;

		#	make request
		$args['_privacy_retained'] = true;
		$result = wp_remote_request( $url, $args );

log_entry($result);

		return $result;
	}
} //*/


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
