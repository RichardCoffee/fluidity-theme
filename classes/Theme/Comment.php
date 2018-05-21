<?php

#	wp-includes/comment.php
#	wp-includes/comment-template.php
#	http://www.wpbeginner.com/wp-themes/how-to-style-wordpress-comment-form/
#	http://justintadlock.com/archives/2010/07/21/using-the-wordpress-comment-form

class TCC_Theme_Comment {


	protected $field_cols  = '60';     #	WordPress default: 45
	protected $field_rows  = '6';      #	WordPress default: 8
	protected $input_width = '40';     #	WordPress default: 30
	protected $max_author  = '245';    #	WordPress default: 245
	protected $max_email   = '100';    #	WordPress default: 100
	protected $max_url     = '200';    #	WordPress default: 200
	protected $permalink;
	protected $post_id     = 0;
	protected $prefix      = 'fluid';  #	class filter/action prefix
	protected $require     = false;
	protected $strings     = array();

	use TCC_Trait_Attributes;

	public function __construct() {
		$this->set_post_id();
		$this->author  = $this->author();
		$this->require = get_option( 'require_name_email' );
		$this->strings = $this->strings();
	}

	public function set_post_id( $post_id = 0 ) {
		$post_id = intval( $post_id, 10 );
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		$this->post_id = $post_id;
	}

	protected function author() {
		return array_merge( $this->commenter(), $this->user() );
	}

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

	protected function strings() {
		$strings = array(
			'author'        => __( 'Your Good Name',                     'tcc-fluid' ),
			'author_req'    => __( 'Your Good Name - Required Field',    'tcc-fluid' ),
			'comment_field' => __( 'Let us know what you have to say',   'tcc-fluid' ),
			'comment_notes_before'     => __( 'Your email address will not be published.', 'tcc-fluid' ),
			'comment_notes_before_req' => __( 'Your email address is required but will not be published.', 'tcc-fluid' ),
			'email'         => __( 'Your Email Please',                  'tcc-fluid' ),
			'email_req'     => __( 'Your Email Please - Required Field', 'tcc-fluid' ),
			'logged_in_as'  => _x( 'Logged in as %1$s. %2$sLog out?%3$s', '1: User name,  2,3: html start and end of link to log-out page', 'tcc-fluid' ),
			'must_log_in'   => _x( 'You must be %slogged in%s to post a comment.', 'start and end of html for link to log-in page', 'tcc-fluid' ),
			'profile'       => __( 'Edit your profile.',    'tcc-fluid' ),
			'title_reply'   => __( 'Got Something To Say:', 'tcc-fluid' ),
			'url'           => __( 'Your Website',          'tcc-fluid' ),
		);
		$strings = apply_filters( "{$this->prefix}_comment_strings_base", $strings );
		$strings['aria'] = array(
			'author'     => $strings['author'],
			'author_req' => $strings['author_req'],
			'comment'    => $strings['comment_field'],
			'email'      => $strings['email'],
			'email_req'  => $strings['email_req'],
			'logout'     => sprintf( $strings['logged_in_as'],  $this->author['name'], '', '' ),
			'profile'    => $strings['profile'],
			'url'        => $strings['url'],
		);
		$strings['title'] = $strings['aria'];
		return apply_filters( "{$this->prefix}_comment_strings", $strings );
	}


	/**  comment form  **/

	public function comment_form() {
		$this->permalink = apply_filters( 'the_permalink', get_permalink( $this->post_id ) );
		$comment_notes_before = ( $this->require ) ? $this->strings['comment_notes_before_req'] : $this->strings['comment_notes_before'];
		$args = array(
			'title_reply'          => $this->strings['title_reply'],
			'fields'               => $this->comment_fields(),
			'comment_field'        => '<p><textarea ' . $this->get_apply_attrs( $this->comment_attrs() ) . '></textarea></p>',
			'must_log_in'          => '<p class="must-log-in">' .  $this->must_log_in() .  '</p>',
			'logged_in_as'         => '<p class="logged-in-as">' . $this->logged_in_as() . '</p>',
			'comment_notes_before' => '<p class="comment-notes"><span id="email-notes">' . esc_html( $comment_notes_before ) . '</span></p>',
			'comment_notes_after'  => '',
			'class_submit'         => 'btn btn-fluidity',
		);
		$args = apply_filters( "{$this->prefix}_comment_args", $args );
		add_filter( 'comment_form_fields', array( $this, 'move_comment_field_to_bottom' ) );
		comment_form( $args );
	}

	protected function comment_fields() {
		$fields = array(
			'author' => '<p class="comment-form-author">' . $this->get_apply_attrs_tag( 'input', $this->author_attrs() ) . '</p>',
			'email'  => '<p class="comment-form-email">' .  $this->get_apply_attrs_tag( 'input', $this->email_attrs() ) .  '</p>',
			'url'    => '<p class="comment-form-url">'  .   $this->get_apply_attrs_tag( 'input', $this->url_attrs() ) .    '</p>',
		);
		return apply_filters( 'comment_form_default_fields', $fields );
	}

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

	protected function must_log_in() {
		$link_html = $this->get_apply_attrs_tag( 'a', $this->must_log_in_link_attrs() );
		return sprintf( $this->strings['must_log_in'], $link_html, '</a>' );
	}

	protected function must_log_in_link_attrs() {
		$attrs = array(
			'href' => wp_login_url( $this->permalink ),
		);
		return apply_filters( "{$this->prefix}_comment_must_log_in_link_attrs", $attrs );
	}


	/**  logged_in_as  **/

	protected function logged_in_as() {
		$profile_link = $this->get_apply_attrs_element( 'a', $this->profile_link_attrs(), $this->author['name'] );
		$logout_link  = $this->get_apply_attrs_tag( 'a', $this->logout_link_attrs() );
		return sprintf( $this->strings['logged_in_as'], $profile_link, $logout_link, '</a>' );
	}

	protected function profile_link_attrs() {
		$attrs = array(
			'href'       => get_edit_user_link(),
			'aria-label' => $this->strings['aria']['profile'],
			'title'      => $this->strings['title']['profile'],
		);
		return apply_filters( "{$this->prefix}_comment_profile_link_attrs", $attrs );
	}

	protected function logout_link_attrs() {
		$attrs = array(
			'href'       => wp_logout_url( $this->permalink ),
			'aria-label' => $this->strings['aria']['logout'],
			'title'      => $this->strings['title']['logout'],
		);
		return apply_filters( "{$this->prefix}_comment_logout_link_attrs", $attrs );
	}


}
