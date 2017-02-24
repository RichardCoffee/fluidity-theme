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

	use TCC_Trait_Singleton;

	protected function __construct() {
		$this->get_options();
		if ( $this->options ) {  #  opt-in only
			#	These first two filters are multisite only
			add_filter( 'pre_site_option_blog_count', array( $this, 'pre_site_option_blog_count' ), 10, 3 );
			add_filter( 'pre_site_option_user_count', array( $this, 'pre_site_option_user_count' ), 10, 3 );
			add_filter( 'pre_http_request',           array( $this, 'pre_http_request' ),            2, 3 );
			add_filter( 'http_request_args',          array( $this, 'http_request_args' ),          11, 2 );
log_entry($this);
		}
	}

	protected function get_options() {
		$options = get_option( 'tcc_options_privacy' );
		if ( ! $options ) {
			// FIXME - get defaults from TCC_Options_Privacy
		}
		$this->options = $options;
	}

	public function pre_site_option_blog_count( $count, $option, $network_id ) {
		if ( isset( $this->options['blogs'] ) && ( $this->options['blogs'] === 'no' ) ) {
			$count = 1;
		}
		return $count;
	}

	public function pre_site_option_user_count( $count, $option, $network_id ) {
		$privacy = $this->options['users'];
		if ( $privacy ) {
			#	if $count has a value, then use it.
			$users = ($count) ? $count : get_user_count();
			switch( $privacy ) {
				case 'all':
					$count = false;
				case 'some':
					$count = max( 1, intval( ( $users / rand(1, 10) ), 10 ) );
				case 'one':
					$count = 1;
				case 'many':
					$count = rand( 1, ( $users * 10 ) );
				default:
			}
		}
		return $count;
	}

	public function http_request_args( $args, $url ) {
		#	only act on requests to api.wordpress.org
		if ( stripos( $url, '://api.wordpress.org/' ) === false ) {
			return $args;
		}
		$args = $this->strip_site_url( $args );
		$args = $this->filter_plugins( $url, $args );
		$args = $this->filter_themes( $url, $args );
		return $args;
	}

	public function pre_http_request( $preempt, $args, $url ) {
		#	check if we have been here before
		if ( $preempt || isset( $args['_privacy_filter'] ) ) {
			return $preempt;
		}
log_entry($url);
		#	only act on requests to api.wordpress.org
		if  ( ( stripos( $url, '://api.wordpress.org/core/version-check/'   ) === false )
			&& ( stripos( $url, '://api.wordpress.org/plugins/update-check/' ) === false )
			&& ( stripos( $url, '://api.wordpress.org/themes/update-check/'  ) === false )
			&& ( stripos( $url, '://api.wordpress.org/translations/'         ) === false )	#	outgoing never seems to have data - need to see what this looks like
			) {
			return $preempt;
		}
		$url  = $this->filter_url( $url );
		#	These should already have been filtered by this time, but just in case...
		$args = $this->strip_site_url( $args );
		$args = $this->filter_plugins( $url, $args );
		$args = $this->filter_themes( $url, $args );
		#	make request
		$args['_privacy_filter'] = true;
log_entry($url,$args,$preempt);
return $preempt;
		$response = wp_remote_request( $url, $args );
		if ( is_wp_error( $response ) ) {
			log_entry( $response );
		} else {
			$body = trim( wp_remote_retrieve_body( $response ) );
			$body = json_decode( $body, true );
			log_entry( $response, $body );
		}
		return $response;
	}

/**
 *  @brief  Strip site URL from headers & user-agent.
 *
 *		I would consider including the url in user-agent as a matter of courtesy.
 *		Besides, what is the point in not giving them your website url?  Don't
 *		you want more people to see it?  Privacy does not mean you shouldn't say
 *		hi to your neighbors. I really think this whole header section is a moot
 *		point.  Also, what if the devs at wordpress.org have decided to cause the
 *		version check/update to fail because of no url?
 *
 */
	protected function strip_site_url( $args ) {
		if ( ! isset( $args['_privacy_strip_site'] ) ) {
			if ( $this->options['blog'] === 'no' ) {
				if ( isset( $args['headers']['wp_blog'] ) ) {
					unset( $args['headers']['wp_blog'] );
				}
				if ( isset( $args['user-agent'] ) ) {
					$args['user-agent'] = sprintf( 'WordPress/%s', $GLOBALS['wp_version'] );
				}
				if ( isset( $args['headers']['user-agent'] ) ) {
					$args['headers']['user-agent'] = sprintf( 'WordPress/%s', $GLOBALS['wp_version'] );
					log_entry( 'header:user-agent has been seen.' );
				}
				if ( isset( $args['headers']['User-Agent'] ) ) { // Anybody seen this?
					$args['headers']['User-Agent'] = sprintf( 'WordPress/%s', $GLOBALS['wp_version'] );
					log_entry( 'header:User-Agent has been seen.' );
				}
				#	Why remove this? I have not seen it...
				if ( isset( $args['headers']['Referer'] ) ) {
					unset( $args['headers']['Referer'] );
					log_entry( 'headers:Referer has been deleted.' );
				}
			}
			if ( isset( $this->options['install'] ) && ( $this->options['install'] === 'no' ) ) {
				if ( isset( $args['headers']['wp_install'] ) ) {
					unset( $args['headers']['wp_install'] );
				}
			}
			$args['_privacy_strip_site'] = true;
		}
		return $args;
	}

	protected function filter_plugins( $url, $args ) {
		if ( stripos( $url, '://api.wordpress.org/plugins/update-check/' ) !== false ) {
			if ( ! isset( $args['_privacy_filter_plugins'] ) ) {
				if ( ! empty( $args['body']['plugins'] ) ) {
					$plugins = json_decode( $args['body']['plugins'] );
					$new_set = new stdClass;
					if ( $this->options['plugins'] === 'none' ) {
						$plugins = $new_set;
					} else if ( $this->options['plugins'] === 'active' ) {
						foreach( $plugins->plugins as $plugin => $info ) {
							if ( in_array( $plugin, (array)$plugins->active ) ) {
								$new_set->$plugin = $info;
							}
						}
						$plugins->plugins = $new_set;
					} else if ( $this->options['plugins'] === 'filter' ) {
						$plugin_filter = $this->options['plugin_list'];
						foreach ( $plugin_filter as $plugin => $status ) {
							if ( ( $status === 'no' ) || ( $plugin === 'privacy-my-way' ) ) {
								if ( isset( $plugins->plugins->$plugin ) ) {
									unset( $plugins->plugins->$plugin );
								}
							}
						}
						#	Rebuild active plugins object
						$count  = 1;
						foreach( (array)$plugins->active as $key => $plugin ) {
							if ( isset( $plugins->plugins->$plugin ) ) {
								$new_set->$count = $plugin;
								$count++;
							}
						}
						$plugins->active = $new_set;
					}
log_entry('plugins:  ' . $this->options['plugins'],$plugins);
					$args['body']['plugins'] = wp_json_encode( $plugins );
					$args['_privacy_filter_plugins'] = true;
				}
			}
		}
		return $args;
	}

	protected function filter_themes( $url, $args ) {
		if ( stripos( $url, '://api.wordpress.org/themes/update-check/' ) !== false ) {
			if ( ! isset( $args['_privacy_filter_themes'] ) ) {
				if ( ! empty( $args['body']['themes'] ) ) {
					$themes = json_decode( $args['body']['themes'] );
log_entry($url,$themes);
					#	Report no themes installed
					if ( $this->options['themes'] === 'none' ) {
						$themes = new stdClass;
						$themes->active = '';
						$themes->themes = new stdClass;
					#	Report only active theme, plus parent if active is child
					} else if ( $this->options['themes'] === 'active' ) {
						$installed = new stdClass;
						$active    = $themes->active;
						$installed->$active = $themes->themes->$active;
						#	Check for child theme
						if ( $installed->$active->Template !== $installed->$active->Stylesheet ) {
							$parent = $installed->$active->Template;
							$installed->$parent = $themes->themes->$parent;
						}
						$themes->themes = $installed;
					#	Filter themes
					} else if ( $this->options['themes'] === 'filter' ) {
						$theme_filter  = $this->options['theme_list'];
						#	Store site active theme
						$active_backup = $themes->active;
						#	Loop through our filter list
						foreach ( $theme_filter as $theme => $status ) {
							#	Is theme still installed?
							if ( isset( $themes->themes->$theme ) ) {
								#	Is the theme being filtered?
								if ( ( $status === 'no' ) ) {
									unset( $themes->themes->$theme );
									#	Is this the active theme?
									$active_backup = ( $active_backup === $theme ) ? '' : $active_backup;
								} else {
									#	Should a different active theme be reported?
									$active_backup = ( $active_backup ) ? $active_backup : $theme;
								}
							} else {
								#	Is this the active theme?
								$active_backup = ( $active_backup === $theme ) ? '' : $active_backup;
							}
						}
						$themes->active = $active_backup;
					}
log_entry('themes:  '.$this->options['themes'],$themes);
					$args['body']['themes'] = wp_json_encode( $themes );
					$args['_privacy_filter_plugins'] = true;
				}
			}
		}
		return $args;
	}

	protected function filter_url( $url ) {
$orig = $url;
		#$keys = array( 'php', 'locale', 'mysql', 'local_package', 'blogs', 'users', 'multisite_enabled', 'initial_db_version',);
		$url_array = parse_url( $url );
		#	Do we need to filter?
		if ( isset( $url_array['query'] ) ) {
			$arg_array = wp_parse_args( $url_array['query'] );
log_entry($url_array,$arg_array);
			#	If multisite then these have already been filtered
			if ( ! is_multisite() ) {
				if ( isset( $arg_array['blogs'] ) ) {
					$blogs = $this->pre_site_option_blog_count( $arg_array['blogs'], 'fluid_blog_count', '' );
					$url   = add_query_arg( 'blogs', $blogs, $url );
				}
				if ( isset( $arg_array['users'] ) ) {
					$users = $this->pre_site_option_user_count( $arg_array['users'], 'fluid_user_count', '' );
					$url   = add_query_arg( 'users', $users, $url );
				}
			}
			#	I really think that fibbing on this is a bad idea, but I am quite definitely pro-choice.
			if ( isset( $arg_array['multisite_enabled'] ) && ( $this->options['blogs'] === 'no' ) ) {
				$url = add_query_arg( 'multisite_enabled', '0', $url );
			}
log_entry(0,$orig,$url);
		}
return $orig;
		return $url;
	}


} # end of class Privacy_My_Way
