<?php

class TCC_Theme_Comment {


	protected $field_cols  = '60';   #	WordPress default: 45
	protected $field_rows  = '6';    #	WordPress default: 8
	protected $input_width = '30';   #	WordPress default: 30
	protected $max_author  = '245';  #	WordPress default: 245
	protected $max_email   = '100';  #	WordPress default: 100
	protected $max_url     = '200';  #	WordPress default: 200
	protected $strings;

	use TCC_Trait_Singleton;


	protected function __construct() {
		$this->strings = $this->strings();
	}

	public function comment_form() {
		$args = array(
			'title_reply' => __( 'Got Something To Say:', 'tcc-fluid' ),
			'fields'      => array(
				'author' => '<p class="comment-form-author"><input ' . get_applied_attrs( $this->author_attrs() ) . ' /></p>',
				'email'  => '<p class="comment-form-email"><input ' .  get_applied_attrs( $this->email_attrs() ) . ' /></p>',
				'url'    => '<p class="comment-form-url"><input '  .   get_applied_attrs( $this->url_attrs() ) . ' /></p>',
			),
			'comment_field' => '<p><textarea ' . get_applied_attrs( $this->comment_attrs() ) . '></textarea></p>',
			'comment_notes_before' => '<p class="comment-notes"><span id="email-notes">' . esc_html( $this->strings['before'] ) . '</span></p>',
			'comment_notes_after' => '',
			'class_submit' => 'btn btn-fluidity',
		);
		comment_form( $args );
	}

	protected function strings() {
		return array(
			'before'  => __( 'Your email address will not be published.', 'tcc-fluid' ),
			'comment' => __( 'Let us know what you have to say', 'tcc-fluid' ),
			'author'  => __( 'Your Good Name - Required Field', 'tcc-fluid' ),
			'email'   => __( 'Your Email Please - Required Field', 'tcc-fluid' ),
			'url'     => __( 'Your Website', 'tcc-fluid' ),
		);
	}

	protected function author_attrs() {
		return array(
			'id'            => 'author',
			'name'          => 'author',
			'type'          => 'text',
			'value'         => '',
			'size'          => $this->input_width,
			'maxlength'     => $this->max_author,
			'aria-required' => 'true',
			'aria-label'    => $this->strings['author'],
			'placeholder'   => $this->strings['author'],
		);
	}

	protected function email_attrs() {
		return array(
			'id'    => 'email',
			'name'  => 'email',
			'type'  => 'email',
			'value' => '',
			'size'  => $this->input_width,
			'maxlength'     => $this->max_email,
			'aria-required' => 'true',
			'aria-label'    => $this->strings['email'],
			'placeholder'   => $this->strings['email'],
		);
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
			'aria-label'    => $this->strings['comment'],
			'placeholder'   => $this->strings['comment'],
		);
	}


}
