<?php

class TCC_Role_Agent {

	private $fields = array();

	use TCC_Trait_Singleton;

	protected function __construct() {
		if ( is_admin() ) {
			add_filter( 'user_contactmethods',      array( $this, 'user_contactmethods' ) );
			add_action( 'personal_options',         array( $this, 'personal_options' ), 9 );
			add_action( 'personal_options_update',  array( $this, 'save_agent_information' ) );
			add_action( 'edit_user_profile_update', array( $this, 'save_agent_information' ) );
		}
		if ( tcc_estate( 'register' ) === 'agents' ) {
			#add_filter( 'edit_profile_url',       array( $this, 'edit_profile_url' ), 10, 3 );
			add_filter( 'tcc_login_username',     array( $this, 'login_prefix' ) );
			add_filter( 'tcc_login_password',     array( $this, 'login_prefix' ) );
			add_filter( 'tcc_login_widget_title', array( $this, 'login_prefix' ) );
		}
		add_filter( 'author_rewrite_rules', array( $this, 'agent_rewrite_rules' ) );
		add_filter( 'query_vars',           array( $this, 'query_vars' ) );
		add_filter( 'template_include',     array( $this, 'template_include' ) );
		$this->fields = $this->get_field_titles();
	}

	public function login_prefix( $input ) {
		$title  = _x( 'Agent', 'noun - user role, prefixed to login placeholder string', 'tcc-fluid' );
		$format = _x( '%1$s %2$s', '1 - noun serving as an adjective, 2 - primary noun', 'tcc-fluid' );
		return sprintf( $format, $title, $input );
	}


  /**  Agent field info  **/

  private function get_field_titles() {
    return array('job_title'      => __('Job Title', 'tcc-fluid'),
                 'education'      => __('Education One', 'tcc-fluid'),
                 'edu_two'        => __('Education Two', 'tcc-fluid'),
                 'edu_three'      => __('Education Three', 'tcc-fluid'),
                 #  TODO:  allow for variable number
                 'certifications' => __('Certifications / Affiliations', 'tcc-fluid'),
                 'certi_two'      => __('Certs / Affiliations Two', 'tcc-fluid'),
                 'certi_three'    => __('Certs / Affiliations Three', 'tcc-fluid'),
                 #  TODO:  allow for variable number
                 'languages'      => __('Language One', 'tcc-fluid'),
                 'lang_two'       => __('Language Two', 'tcc-fluid'),
                 'lang_three'     => __('Language Three', 'tcc-fluid'),
                 'telephone'      => __('Telephone','tcc-fluid'),
                 'facebook'       => __('Facebook username', 'tcc-fluid'),
                 'twitter'        => __('Twitter handle',  'tcc-fluid'),
                 'linkedin'       => __('LinkedIN Profile', 'tcc-fluid'),
                 'website_image'  => __('Website Image', 'tcc-fluid'));
  }


  /**  Agent template  **/

  public function agent_rewrite_rules($current) {
    $rules = array(array('regex'    => 'agent/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$',
                         'redirect' => 'index.php?author_name=$matches[1]&agent=true&feed=$matches[2]'),
                   array('regex'    => 'agent/([^/]+)/(feed|rdf|rss|rss2|atom)/?$',
                         'redirect' => 'index.php?author_name=$matches[1]&agent=true&feed=$matches[2]'),
                   array('regex'    => 'agent/([^/]+)/embed/?$',
                         'redirect' => 'index.php?author_name=$matches[1]&embed=true&agent=true'),
                   array('regex'    => 'agent/([^/]+)/page/?([0-9]{1,})/?$',
                         'redirect' => 'index.php?author_name=$matches[1]&paged=$matches[2]&agent=true'),
                   array('regex'    => 'agent/([^/]+)/?$',
                         'redirect' => 'index.php?author_name=$matches[1]&agent=true'));
    foreach($rules as $rule) {
      $current[$rule['regex']] = $rule['redirect'];
    }
    return $current;
  }

  public function query_vars($vars) {
    $vars[] = 'agent';
    return $vars;
  }

  public function template_include($template) {
    $agent = get_query_var('agent', null);
    $name  = get_query_var('author_name', null);
    #tcc_log_entry("agent: $agent  name: $name");
    if($agent && $name) {
      $template = get_template_directory().'/author.php';
    }
    #tcc_log_entry("template: $template");
    return $template;
}


  /**  Agent Profile functions  **/

	public function user_contactmethods( $profile_fields, $user = null ) {
		if ( $user && in_array( 'agent', $user->roles ) ) {
			$fields = array( 'telephone', 'facebook', 'twitter', 'linkedin' );
			foreach( $fields as $field ) {
				if ( ! isset( $profile_fields[ $field ] ) ) {
					$profile_fields[ $field ] = $this->fields[ $field ];
				}
			}
		}
		return $profile_fields;
	}

  public function personal_options($user) {
    if (in_array('agent',$user->roles)) {
      $this->agent_image($user);
      $fields = array('job_title','education','edu_two','edu_three','certifications','certi_two','certi_three','languages','lang_two','lang_three');
      foreach($fields as $field) {
        $array = get_user_meta($user->ID,$field);
        $value = (empty($array)) ? '' : $array[0]; ?>
        <table class="form-table">
          <tr>
            <th>
              <label for="<?php echo $field; ?>"><?php echo $this->fields[$field]; ?></label>
            </th>
            <td>
              <input type="text" class="regular-text" name="<?php echo $field; ?>" value="<?php echo $value; ?>" />
            </td>
          </tr>
        </table><?php
      }
    }
  }

  private function agent_image($user) {
    #tcc_log_entry('profile user',$user,"user ID: {$user->ID}",get_user_meta($user->ID));
    if (in_array('agent',$user->roles)) {
      $image = get_user_meta($user->ID,'website_image');
      $url   = ($image) ? $image[0] : ''; ?>
      <table class="form-table">
        <tr>
          <th>
            <label for="website_image"><?php echo $this->fields['website_image']; ?></label>
          </th>
          <td>
            <div data-title='<?php _e('Assign/Upload Image','tcc-fluid'); ?>' data-button='<?php _e('Assign Image','tcc-fluid'); ?>'>
              <button class='tcc-image'><?php _e('Assign Image','tcc-fluid'); ?></button>
              <input type='hidden' name='website_image' value='<?php echo esc_url($url); ?>' />
              <div>
                <img class='tcc-image-size' src='<?php echo esc_url($url); ?>'>
              </div>
            </div>
          </td>
        </tr>
      </table><?php //*/
    }
  }

  public function save_agent_information($user_id) {
    foreach($this->fields as $field=>$title) {
#error_log("field: $field");
      if (isset($_POST[$field])) {
#error_log($_POST[$field]);
        update_user_meta($user_id,$field,sanitize_text_field($_POST[$field])); } } /*
    if (isset($_POST['website_image'])) {
      update_user_meta($user_id,'website_image',sanitize_text_field($_POST['website_image'])); } //*/
  }


}
