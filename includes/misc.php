<?php

// Source? Purpose?
function contextual_static_front_page_section($wp_customize) {
  $wp_customize->get_section('static_front_page')->active_callback = 'is_front_page';
}
add_action( 'customize_register', 'contextual_static_front_page_section', 11 );

// derived from:  http://codex.wordpress.org/Excerpt
function fluid_read_more_link($output) {
	global $post;
	$perm = get_permalink($post->ID);
	$read = apply_filters('tcc_read_more_text',__('Read More','tcc-fluid'));
	$brac = apply_filters('tcc_read_more_brackets',true);
	$css  = apply_filters('tcc_read_more_css','');
	$link = '<a class="read-more" href="'.esc_url($perm).'" itemprop="url">'.esc_html($read).'</a>';
	if ($brac) { $link = " [$link]"; }
	if ($css)  { $link = "<span class='$css'>$link</span>"; }
	return $link;
}
add_filter('excerpt_more', 'fluid_read_more_link');

##  simple query template
if (!function_exists('fluidity_show_query')) {
	function fluidity_show_query( array $args, string $template, $slug='' ) {
		$query = new WP_Query($args);
		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();
				get_template_part($template,$slug);
			}
		}
		wp_reset_postdata();
	}
}

if (!function_exists('fluidity_social_icons')) {
  function fluidity_social_icons() {
    $icons = get_option('tcc_options_social');
    if ($icons['active']==='yes') {
      if (has_action('fluidity_social_icons')) {
        do_action('fluidity_social_icons');
      } else {
        $size   = (isset($icons['size']))   ? $icons['size']   : '';
        $target = (isset($icons['target'])) ? $icons['target'] : 'target';
        unset($icons['active'],$icons['target'],$icons['size']);
        $social = array(); // FIXME: find another way to do this
        foreach($icons as $field=>$value) {
          $pos = strpos($field,'_color');
          if ($pos) {
            $split = explode('_',$field);
            $social[$split[0]]['color'] = $value;
          } else {
            $social[$field]['link'] = $value;
          }
        }
        $insta  = new TCC_Options_Social;
        $layout = $insta->social_layout();
        #log_entry($icons,$social,$layout); ?>
				<span class='fluidity-social-icons'><?php
					foreach( $social as $key => $set ) {
						if ( empty( $set['link'] ) ) continue;
						$tool = sprintf( esc_html_x( 'See us on %s', 'website name', 'tcc-fluid' ), $layout[ $key ] ['label'] );
						$attr = array(
							'class' => "fa fa-fw fa-$key-square $size",
							'href'  => $set['link'],
							'rel'   => 'nofollow',
							'style' => "color:{$set['color']};",
							'title' => ( $key === 'rss' ) ? esc_html__( 'Subscribe to our RSS feed', 'tcc-fluid' ) : $tool, // TODO: option to change this text
							'target'=> ( $target === 'target' ) ? "fluidity_$key" : "_blank",
							); ?>
						<a <?php apply_attrs( $attr ); ?>> </a>
					<?php } ?>
				</span><?php
			}
		}
	} //*/
}

if (!function_exists('fluid_user_profile_link')) {
  function fluid_user_profile_link() {
    $user = wp_get_current_user();
    $html = "<a href='";
    $html = get_option('siteurl');
    $html = "/wp-admin/profile.php'>";
    $html = $user->display_name;
    $html = "</a>";
    return apply_filters('tcc_user_profile_link',$html);
  }
}
/* FIXME
if (!function_exists('remove_post_revisions')) {

DELETE a,b,c FROM wp_posts a
LEFT JOIN wp_term_relationships b ON (a.ID = b.object_id)
LEFT JOIN wp_postmeta c ON (a.ID = c.post_id)
WHERE a.post_type = 'revision'

} //*/

if (!function_exists('has_page')) {
	function has_page( $title ) {
		return page_exists( $title );
	}
}

if (!function_exists('page_exists')) {
	#	http://www.tammyhartdesigns.com/tutorials/wordpress-how-to-determine-if-a-certain-page-exists
	function page_exists( $title ) {
		$pages = get_pages();
		$search = sanitize_title($title);
		foreach ($pages as $page) {
			if ($page->post_name===$search) {
				return true; } // FIXME: return url? -> home_url("/$search/");
		}
		return false;
	}
}

if (!function_exists('single_search_result')) {
	#	 http://www.hongkiat.com/blog/wordpress-tweaks-for-post-management/
	function single_search_result() {
		global $wp_query;
		if (is_search() || is_archive()) {
			if ($wp_query->post_count==1) {
				wp_redirect(get_permalink($wp_query->posts['0']->ID));
			}
		}
	}
	add_action('template_redirect','single_search_result');
}

if ( ! function_exists( 'wp_get_attachment' ) ) {
	#	http://stackoverflow.com/questions/25974196/how-to-get-wp-gallery-image-captions
	function wp_get_attachment( $attachment_id ) {
		$attachment = get_post( $attachment_id );
		$metadata   = get_post_meta( $attachment_id );
		$img_data   = unserialize( $metadata['_wp_attachment_metadata'][ 0 ] );
		$data = array(
			'alt'         => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
			'caption'     => $attachment->post_excerpt,
			'description' => $attachment->post_content,
			'href'        => get_permalink( $attachment->ID ),
			'sizes'       => attachment_sizes( $img_data, url_stem( $attachment->guid ) ),
			'src'         => $attachment->guid,
			'title'       => $attachment->post_title
		);
		return $data;
	}
}

if ( ! function_exists( 'attachment_sizes' ) ) {
	function attachment_sizes( $data, $stem ) {
		$sizes = array();
		foreach( $data['sizes'] as $size => $data ) {
			$sizes[ $size ] = $stem . $data['file'];
		}
		return $sizes;
	}
}

if ( ! function_exists( 'disable_website_field' ) ) {
	#	https://github.com/taniarascia/wp-functions
	function disable_website_field( $field ) { 
		if( isset($field['url']) ) {
			unset( $field['url'] );
		}
		return $field;
	}
	add_filter('comment_form_default_fields', 'disable_website_field');
}

if ( ! function_exists( 'get_the_slug' ) ) {
	#	http://www.tcbarrett.com/2013/05/wordpress-how-to-get-the-slug-of-your-post-or-page
	function get_the_slug( $id = null ) {
		if( empty( $id ) ) {
			global $post;
			if( empty($post) ) { return ''; } // No global $post var available.
			$id = $post->ID;
		}
		$slug = basename( get_permalink( $id ) );
		return $slug;
	}
}

if ( ! function_exists( 'the_slug' ) ) {
	#	http://www.tcbarrett.com/2013/05/wordpress-how-to-get-the-slug-of-your-post-or-page
	function the_slug( $id = null ) {
		echo apply_filters( 'the_slug', get_the_slug( $id ) );
	}
}

if ( ! function_exists( 'url_stem' ) ) {
	function url_stem( $url ) {
		$pos  = strrpos( $url, '/' );
		$stem = substr( $url, 0, $pos + 1 );
		return $stem;
	}
}

if (!function_exists('tcc_holiday_greeting')) {
	function tcc_holiday_greeting() {
		#	http://stackoverflow.com/questions/14907561/how-to-get-date-for-holidays-using-php
		$year = date('Y');
		$MLK  = date( 'm-d', strtotime( "january $year third monday" ) ); //marthin luthor king day
		$PD   = date( 'm-d', strtotime( "february $year third monday" ) ); //presidents day
		$Est  = date( 'm-d', easter_date( $year ) ); // easter
		$MD   = date( 'm-d', strtotime( "may $year last monday" ) ); // memorial day
		$LD   = date( 'm-d', strtotime( "september $year first monday" ) );  //labor day
		$CD   = date( 'm-d', strtotime( "october $year second monday" ) ); //columbus day
		$TG   = date( 'm-d', strtotime( "november $year last thursday" ) ); // thanksgiving
		$date = date( 'm-d' );
		switch( $date ) {
			case '01-01':
				$message = __( 'Happy New Year', 'tcc-fluid' );
				break;
			case $MLK:
				$message = __( 'Martin Luthor King Day', 'tcc-fluid' );
				break;
			case '02-14':
				$message = __( "Valentine's Day", 'tcc-fluid' );
				break;
			case $PD:
				$message = __( "Presidents' Day", 'tcc-fluid' );
				break;
			case $Est:
				$message = __( 'Happy Easter', 'tcc-fluid' ); // happy easter?  wtf?
				break;
			case $MD:
				$message = __( 'Memorial Day', 'tcc-fluid' );
				break;
			case '07-04':
				$message = __( 'Fourth of July', 'tcc-fluid' );
				break;
			case $LD:
				$message = __( 'Labor Day', 'tcc-fluid' );
				break;
			case $CD:
				$message = __( 'Columbus Day', 'tcc-fluid' );
				break;
			case $TG:
				$message = __( 'Happy Thanksgiving', 'tcc-fluid' );
				break;
			case '11-11':
				$message = __( "Veteran's Day", 'tcc-fluid' );
				break;
			case '12-25':
				$message = __( 'Merry Christmas', 'tcc-fluid' );
				break;
			default:
				$message = ( get_current_user_id() === 1 ) ? __( 'Your Royal Highness', 'tcc-fluid' ) : __( 'Welcome', 'tcc-fluid' );
		}
		return $message;
	}
}

function stupid_theme_checker () {
	wp_link_pages();
	posts_nav_link();
	paginate_links();
	the_posts_pagination();
	the_posts_navigation();
	next_posts_link();
	previous_posts_link();
	add_theme_support( "custom-header", $args );
}
