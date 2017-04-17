<?php

class TCC_Theme_Comment {


	protected $field_cols  = '60';   #	WordPress default: 45
	protected $field_rows  = '6';    #	WordPress default: 8
	protected $input_width = '40';   #	WordPress default: 30
	protected $max_author  = '245';  #	WordPress default: 245
	protected $max_email   = '100';  #	WordPress default: 100
	protected $max_url     = '200';  #	WordPress default: 200
	protected $strings;

	use TCC_Trait_Singleton;


	protected function __construct() {
		$this->strings = $this->strings();
		$this->author  = $this->author();
		$this->require = get_option( 'require_name_email' );
	}

	protected function strings() {
		$strings = array(
			'title'      => __( 'Got Something To Say:',                     'tcc-fluid' ),
			'before'     => __( 'Your email address will not be published.', 'tcc-fluid' ),
			'comment'    => __( 'Let us know what you have to say',          'tcc-fluid' ),
			'author'     => __( 'Your Good Name',                            'tcc-fluid' ),
			'author_req' => __( 'Your Good Name - Required Field',           'tcc-fluid' ),
			'email'      => __( 'Your Email Please',                         'tcc-fluid' ),
			'email_req'  => __( 'Your Email Please - Required Field',        'tcc-fluid' ),
			'url'        => __( 'Your Website',                              'tcc-fluid' ),
		);
		return $strings;
	}

	protected function author() {
		$commenter = wp_get_current_commenter();
		$user      = wp_get_current_user();
log_entry($commenter,$user);
#		$identity  = $user->exists() ? $user->display_name : '';
	}

	public function comment_form() {
		$args = array(
			'title_reply'          => $this->strings['title'],
			'fields'               => $this->comment_fields(),
			'comment_field'        => '<p><textarea ' . get_applied_attrs( $this->comment_attrs() ) . '></textarea></p>',
			'comment_notes_before' => '<p class="comment-notes"><span id="email-notes">' . esc_html( $this->strings['before'] ) . '</span></p>',
			'comment_notes_after'  => '',
			'class_submit'         => 'btn btn-fluidity',
		);
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

	protected function author_attrs() {
		$attrs = array(
			'id'    => 'author',
			'name'  => 'author',
			'type'  => 'text',
			'value' => '',
			'size'  => $this->input_width,
			'maxlength'   => $this->max_author,
			'aria-label'  => $this->strings['author'],
			'placeholder' => $this->strings['author'],
		);
		if ( $this->require ) {
			$attrs['aria-required'] = 'true';
			$attrs['required']      = 'required';
			$attrs['aria-label']    = $this->strings['author_req'];
			$attrs['placeholder']   = $this->strings['author_req'];
		}
		return $attrs;
	}

	protected function email_attrs() {
		$attrs = array(
			'id'    => 'email',
			'name'  => 'email',
			'type'  => 'email',
			'value' => '',
			'size'  => $this->input_width,
			'maxlength'     => $this->max_email,
			'aria-label'    => $this->strings['email'],
			'placeholder'   => $this->strings['email'],
		);
		if ( $this->require ) {
			$attrs['aria-required'] = 'true';
			$attrs['required']      = 'required';
			$attrs['aria-label']    = $this->strings['email_req'];
			$attrs['placeholder']   = $this->strings['email_req'];
		}
		return $attrs;
	}

	protected function url_attrs() {
		return array(
			'id'    => 'url',
			'name'  => 'url',
			'type'  => 'url',
			'value' => '',
			'size'  => $this->input_width,
			'maxlength'   => $this->max_url,
			'aria-label'  => $this->strings['url'],
			'placeholder' => $this->strings['url'],
		);
	}

	protected function comment_attrs() {
		return array(
			'id'   => 'comment',
			'name' => 'comment',
			'cols' => $this->field_cols,
			'rows' => $this->field_rows,
			'aria-required' => 'true',
			'required'      => 'required',
			'aria-label'    => $this->strings['comment'],
			'placeholder'   => $this->strings['comment'],
		);
	}


}
