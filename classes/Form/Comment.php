<?php
/**
 *  The comment form.
 *
 * @package Fluidity
 * @subpackage Comments
 * @since 20170416
 * @author Richard Coffee <richard.coffee@rtcenterprises.net>
 * @copyright Copyright (c) 2018, Richard Coffee
 * @link https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/Form/Comment.php
 * @see wp-includes/comment.php
 * @see wp-includes/comment-template.php
 * @link http://www.wpbeginner.com/wp-themes/how-to-style-wordpress-comment-form/
 * @link http://justintadlock.com/archives/2010/07/21/using-the-wordpress-comment-form
 */
defined( 'ABSPATH' ) || exit;


class TCC_Form_Comment {


#	 * @since 20170416
	protected $field_cols  = '60';     #	WordPress default: 45
#	 * @since 20170416
	protected $field_rows  = '6';      #	WordPress default: 8
#	 * @since 20170416
	protected $input_width = '40';     #	WordPress default: 30
#	 * @since 20170416
	protected $max_author  = '245';    #	WordPress default: 245
#	 * @since 20170416
	protected $max_email   = '100';    #	WordPress default: 100
#	 * @since 20170416
	protected $max_url     = '200';    #	WordPress default: 200
#	 * @since 20170417
	protected $permalink;
#	 * @since 20170417
	protected $post_id     = 0;
#	 * @since 20170417
	protected $prefix      = 'fluid';  #	class filter/action prefix
#	 * @since 20170417
	protected $require     = false;
#	 * @since 20170416
	protected $strings     = array();

#	 * @since 20180314
	use TCC_Trait_Attributes;

#	 * @since 20170416
	public function __construct() {
		$this->set_post_id();
		$this->author  = $this->author();
		$this->require = get_option( 'require_name_email' );
		$this->strings = $this->strings();
		add_filter( 'comment_form_default_fields', [ $this, 'comment_form_default_fields'  ] );
		add_filter( 'comment_form_fields',         [ $this, 'move_comment_field_to_bottom' ] );
	}

#	 * @since 20170418
	protected function set_post_id( $post_id = 0 ) {
		$post_id = intval( $post_id, 10 );
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		$this->post_id = $post_id;
	}

#	 * @since 20170417
	protected function author() {
		return array_merge( $this->commenter(), $this->user() );
	}

#	 * @since 20170417
	protected function user() {
		$name = $email = $url = '';
		$user = wp_get_current_user();
		if ( $user->exists() ) {
			$name  = $user->display_name;
			$email = $user->user_email;
			$url   = $user->user_url;
		}
		return compact( 'name', 'email', 'url' );
	}

#	 * @since 20170417
	protected function commenter() {
		$author    = array();
		$commenter = wp_get_current_commenter();
		if ( ! empty( $commenter['comment_author'] ) ) {
			$author['name'] = sanitize_user( $commenter['comment_author'] );
		}
		if ( ! empty( $commenter['comment_author_email'] ) && ( $email = is_email( $commenter['comment_author_email'] ) ) ) {
			$author['email'] = $email;
		}
		if ( ! empty( $commenter['comment_author_url'] ) ) {
			$author['url'] = esc_url_raw( $commenter['comment_author_url'] );
		}
		return $author;
	}

#	 * @since 20170416
	protected function strings() {
		$strings = array(
			'author'        => __( 'Your Good Name',                     'tcc-fluid' ),
			'author_req'    => __( 'Your Good Name - Required Field',    'tcc-fluid' ),
			'comment_field' => __( 'Let us know what you have to say',   'tcc-fluid' ),
			'comment_notes_before'     => __( 'Your email address will not be published.', 'tcc-fluid' ),
			'comment_notes_before_req' => __( 'Your email address is required but will not be published.', 'tcc-fluid' ),
			'email'         => __( 'Your Email Please',                  'tcc-fluid' ),
			'email_req'     => __( 'Your Email Please - Required Field', 'tcc-fluid' ),
			'consent'       => __( 'Save my name and email in a browser cookie for the next time I comment.', 'tcc-fluid' ),
			'logged_in_as'  => _x( 'Logged in as %1$s. %2$sLog out?%3$s', '1: User name,  2,3: html start and end of link to log-out page', 'tcc-fluid' ),
			'must_log_in'   => _x( 'You must be %slogged in%s to post a comment.', 'start and end of html for link to log-in page', 'tcc-fluid' ),
			'profile'       => __( 'Edit your profile.',    'tcc-fluid' ),
			'title_reply'   => __( 'Got Something To Say:', 'tcc-fluid' ),
			'url'           => __( 'Your Website',          'tcc-fluid' ),
		);
		$strings = apply_filters( "{$this->prefix}_comment_strings_base", $strings );
		$aria = array(
			'author'     => $strings['author'],
			'author_req' => $strings['author_req'],
			'comment'    => $strings['comment_field'],
			'email'      => $strings['email'],
			'email_req'  => $strings['email_req'],
			'logout'     => sprintf( $strings['logged_in_as'],  $this->author['name'], '', '' ),
			'profile'    => $strings['profile'],
			'url'        => $strings['url'],
		);
		$strings['aria']  = apply_filters( "{$this->prefix}_aria_strings",  $aria );
		$strings['title'] = apply_filters( "{$this->prefix}_title_strings", $aria );
		return apply_filters( "{$this->prefix}_comment_strings", $strings );
	}


	/**  comment form  **/

#	 * @since 20170416
	public function comment_form() {
		$this->permalink = apply_filters( 'the_permalink', get_permalink( $this->post_id ) );
		$comment_notes_before = ( $this->require ) ? $this->strings['comment_notes_before_req'] : $this->strings['comment_notes_before'];
		$args = array(
			'title_reply'          => $this->strings['title_reply'],
			'comment_field'        => '<p>' . $this->get_element( 'textarea', $this->comment_attrs() ) . '</p>',
			'must_log_in'          => '<p class="must-log-in">' .  $this->must_log_in() .  '</p>',
			'logged_in_as'         => '<p class="logged-in-as">' . $this->logged_in_as() . '</p>',
			'comment_notes_before' => '<p class="comment-notes"><span id="email-notes">' . esc_html( $comment_notes_before ) . '</span></p>',
			'comment_notes_after'  => '',
			'class_submit'         => 'btn btn-fluidity',
		);
		$args = apply_filters( "{$this->prefix}_comment_args", $args );
		comment_form( $args );
	}

#	 * @since 20170417
	protected function comment_fields() {
		$fields = array(
			'author'  => '<p class="comment-form-author">' . $this->get_tag( 'input', $this->author_attrs() ) . '</p>',
			'email'   => '<p class="comment-form-email">' .  $this->get_tag( 'input', $this->email_attrs() ) .  '</p>',
			'url'     => '<p class="comment-form-url">'  .   $this->get_tag( 'input', $this->url_attrs() ) .    '</p>',
			'cookies' => '<p class="comment-form-cookies-consent">' . $this->cookies_html() . '</p>',
		);
		return $fields;
	}

#	 * @since 20180805
	public function comment_form_default_fields( $fields ) {
		$theme = $this->comment_fields();
		return array_merge( $fields, array_intersect_key( $theme, $fields ) );
	}

#	 * @since 20170418
	public function move_comment_field_to_bottom( $fields ) {
		# move comment textarea to bottom
		$comment_field = $fields['comment'];
		unset( $fields['comment'] );
		$fields['comment'] = $comment_field;
		if ( isset( $fields['cookies'] ) ) {
			# move checkbox to under textarea
			$cookies = $fields['cookies'];
			unset( $fields['cookies'] );
			$fields['cookies'] = $cookies;
		}
		return $fields;
	}


	/**  field attributes  **/

#	 * @since 20170416
	protected function author_attrs() {
		$attrs = array(
			'id'    => 'author',
			'class' => 'form-control',  #  bootstrap css class
			'name'  => 'author',
			'type'  => 'text',
			'value' => $this->author['name'],
			'size'  => $this->input_width,
			'maxlength'   => $this->max_author,
			'aria-label'  => $this->strings['aria']['author'],
			'placeholder' => $this->strings['author'],
			'title'       => $this->strings['title']['author'],
		);
		if ( $this->require ) {
			$attrs['aria-required'] = 'true';
			$attrs['required']      = 'required';
			$attrs['aria-label']    = $this->strings['aria']['author_req'];
			$attrs['placeholder']   = $this->strings['author_req'];
			$attrs['title']         = $this->strings['title']['author_req'];
		}
		return apply_filters( "{$this->prefix}_comment_author_attrs", $attrs );
	}

#	 * @since 20170416
	protected function email_attrs() {
		$attrs = array(
			'id'    => 'email',
			'class' => 'form-control',  #  bootstrap css class
			'name'  => 'email',
			'type'  => 'email',
			'value' => $this->author['email'],
			'size'  => $this->input_width,
			'maxlength'   => $this->max_email,
			'aria-label'  => $this->strings['aria']['email'],
			'placeholder' => $this->strings['email'],
			'title'       => $this->strings['title']['email'],
		);
		if ( $this->require ) {
			$attrs['aria-required'] = 'true';
			$attrs['required']      = 'required';
			$attrs['aria-label']    = $this->strings['aria']['email_req'];
			$attrs['placeholder']   = $this->strings['email_req'];
			$attrs['title']         = $this->strings['title']['email_req'];
		}
		return apply_filters( "{$this->prefix}_comment_email_attrs", $attrs );
	}

#	 * @since 20170416
	protected function url_attrs() {
		$attrs = array(
			'id'    => 'url',
			'class' => 'form-control',  #  bootstrap css class
			'name'  => 'url',
			'type'  => 'url',
			'value' => $this->author['url'],
			'size'  => $this->input_width,
			'maxlength'   => $this->max_url,
			'aria-label'  => $this->strings['aria']['url'],
			'placeholder' => $this->strings['url'],
			'title'       => $this->strings['title']['url'],
		);
		return apply_filters( "{$this->prefix}_comment_url_attrs", $attrs );
	}

#	 * @since 20180521
	protected function cookies_html() {
		$attrs = array(
			'field_id'    => 'wp-comment-cookies-consent',
			'field_name'  => 'wp-comment-cookies-consent',
			'type'        => 'checkbox',
			'field_value' => 'yes',
			'description' => $this->strings['consent'],
			'checked'     => ( ! empty( $this->author['email'] ) ),
		);
		$attrs    = apply_filters( "{$this->prefix}_comment_cookies_attrs", $attrs );
		$checkbox = new TCC_Form_Field_CheckBox( $attrs );
		return $checkbox->get_checkbox();
	}

#	 * @since 20170416
	protected function comment_attrs() {
		$attrs = array(
			'id'    => 'comment',
			'class' => 'form-control',  #  bootstrap css class
			'name'  => 'comment',
#			'cols'  => $this->field_cols,
			'rows'  => $this->field_rows,
			'aria-required' => 'true',
			'required'      => 'required',
			'aria-label'    => $this->strings['aria']['comment'],
			'placeholder'   => $this->strings['comment_field'],
			'title'         => $this->strings['title']['comment'],
		);
		return apply_filters( "{$this->prefix}_comment_args", $attrs );
	}


	/**  must_log_in  **/

#	 * @since 20170417
	protected function must_log_in() {
		$link_html = $this->get_tag( 'a', $this->must_log_in_link_attrs() );
		return sprintf( $this->strings['must_log_in'], $link_html, '</a>' );
	}

#	 * @since 20170418
	protected function must_log_in_link_attrs() {
		$attrs = array(
			'href' => wp_login_url( $this->permalink ),
		);
		return apply_filters( "{$this->prefix}_comment_must_log_in_link_attrs", $attrs );
	}


	/**  logged_in_as  **/

#	 * @since 20170417
	protected function logged_in_as() {
		$profile_link = $this->get_element( 'a', $this->profile_link_attrs(), $this->author['name'] );
		$logout_link  = $this->get_tag( 'a', $this->logout_link_attrs() );
		return sprintf( $this->strings['logged_in_as'], $profile_link, $logout_link, '</a>' );
	}

#	 * @since 20170417
	protected function profile_link_attrs() {
		$attrs = array(
			'href'       => get_edit_user_link(),
			'aria-label' => $this->strings['aria']['profile'],
			'title'      => $this->strings['title']['profile'],
			//  This attribute was added on 20170417, removed on 20180423, added again on 20200408.  Why was it removed?
			'target'     => 'rtce_edit_profile',
		);
		return apply_filters( "{$this->prefix}_comment_profile_link_attrs", $attrs );
	}

#	 * @since 20170417
	protected function logout_link_attrs() {
		$attrs = array(
			'href'       => wp_logout_url( $this->permalink ),
			'aria-label' => $this->strings['aria']['logout'],
			'title'      => $this->strings['title']['logout'],
		);
		return apply_filters( "{$this->prefix}_comment_logout_link_attrs", $attrs );
	}


}
