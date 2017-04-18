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
	public    $post_id;
	protected $prefix      = 'fluid';  #	class filter/action prefix
	protected $require     = false;
	protected $strings     = array();

	use TCC_Trait_Singleton;


	protected function __construct() {
		$this->author  = $this->author();
log_entry($this->author);
		$this->post_id = get_the_ID();
		$this->require = get_option( 'require_name_email' );
		$this->strings = $this->strings();
	}

	protected function author() {
		return array_merge( $this->user(), $this->commenter() );
	}

	protected function user() {
		$name = $email = $url = '';
		$user = wp_get_current_user();
		if ( $user->exists() ) {
			$data  = get_userdata( $user->ID );
log_entry($user,$data);
			$name  = $user->display_name;
			$email = $user->email;

		}
		return compact( 'name', 'email', 'url' );
	}

	protected function commenter() {
		$commenter = wp_get_current_commenter();
		$name  = $commenter['comment_author'];
		$email = $commenter['comment_author_email'];
		$url   = $commenter['comment_author_url'];
		return compact( 'name', 'email', 'url' );
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
			'logged_in_as'  => _x( '%1$sLogged in as %2$s%3$s. %4$sLog out?%5$s',
				'2: User name,  1,3: start and end of link to profile page,  4,5: start and end of link to log-out page', 'tcc-fluid' ),
			'must_log_in'   => _x( 'You must be %slogged in%s to post a comment.', 'start and end of link to log-in page', 'tcc-fluid' ),
			'title_reply'   => __( 'Got Something To Say:',              'tcc-fluid' ),
			'url'           => __( 'Your Website',                       'tcc-fluid' ),
		);
		$strings['aria'] = array(
			'author'     => $strings['author'],
			'author_req' => $strings['author_req'],
			'comment'    => $strings['comment_field'],
			'email'      => $strings['email'],
			'email_req'  => $strings['email_req'],
			'logout'     => _x( 'Logged in as %s. Log out?', 'User name', 'tcc-fluid' ),
			'profile'    => _x( 'Logged in as %s. Edit your profile.', 'User name', 'tcc-fluid' ),
			'url'        => $strings['url'],
		);
		$strings['title'] = $strings['aria'];
		return apply_filters( "{$this->prefix}_comment_strings", $strings );
	}


	/**  comment form  **/

	public function comment_form() {
		$this->permalink = apply_filters( 'the_permalink', get_permalink( $this->post_id ) );
		$args = array(
			'title_reply'          => $this->strings['title_reply'],
			'fields'               => $this->comment_fields(),
			'comment_field'        => '<p><textarea ' . get_applied_attrs( $this->comment_attrs() ) . '></textarea></p>',
			'must_log_in'          => '<p class="must-log-in">' .  $this->must_log_in() .  '</p>',
			'logged_in_as'         => '<p class="logged-in-as">' . $this->logged_in_as() . '</p>',
			'comment_notes_before' => '<p class="comment-notes"><span id="email-notes">' . esc_html( $this->strings['comment_notes_before'] ) . '</span></p>',
			'comment_notes_after'  => '',
			'class_submit'         => 'btn btn-fluidity',
		);
		if ( $this->required ) {
			$args['comment_notes_before'] = $this->strings['comment_notes_before_req'];
		}
		$args = apply_filters( "{$this->prefix}_comment_args", $args );
		comment_form( $args );
	}

	protected function comment_fields() {
		$fields = array(
			'author' => '<p class="comment-form-author"><input ' . get_applied_attrs( $this->author_attrs() ) . ' /></p>',
			'email'  => '<p class="comment-form-email"><input ' .  get_applied_attrs( $this->email_attrs() ) . ' /></p>',
			'url'    => '<p class="comment-form-url"><input '  .   get_applied_attrs( $this->url_attrs() ) . ' /></p>',
		);
		return apply_filters( 'comment_form_default_fields', $fields );
	}


	/**  field attributes  **/

	protected function author_attrs() {
		$attrs = array(
			'id'    => 'author',
			'class' => 'form-control',
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
			'class' => 'form-control',
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
			'class' => 'form-control',
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
			'class' => 'form-control',
			'name'  => 'comment',
			'cols'  => $this->field_cols,
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
		$link_html = '<a href="' . wp_login_url( $this->permalink ) . '">';
		return sprintf( $this->strings['must_log_in'], $link_html, '</a>' );
	}


	/**  logged_in_as  **/

	protected function logged_in_as() {
		$profile_link = '<a ' . get_applied_attrs( $this->profile_link_attrs() ) . '>';
		$logout_link  = '<a ' . get_applied_attrs( $this->logout_link_attrs() ) .  '>';
		return sprintf( $this->strings['logged_in_as'], $profile_link, $this->author['name'], '</a>', $logout_link, '</a>' );
	}

	protected function profile_link_attrs() {
		$attrs = array(
			'href'       => get_edit_user_link(),
			'aria-label' => sprintf( $this->strings['aria']['profile'], $this->author['name'] ),
			'target'     => 'site_profile',
		);
		$attrs['title'] = $attrs['aria-label'];
		return apply_filters( "{$this->prefix}_comment_profile_link_attrs", $attrs );
	}

	protected function logout_link_attrs() {
		$attrs = array(
			'href' => wp_logout_url( $this->permalink ),
			'aria-label' => $this->strings['aria']['logout'],
			'title'      => $this->strings['title']['logout'],
		);
		return apply_filters( "{$this->prefix}_comment_logout_link_attrs", $attrs );
	}


}
