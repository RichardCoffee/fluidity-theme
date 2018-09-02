<?php

class TCC_Widget_Address extends TCC_Widget_Widget {

	private $address;

	public function __construct() {
		$this->title = esc_html__( 'Address', 'tcc-fluid' );
		$this->desc  = esc_html__( 'Fluidity address widget', 'tcc-fluid' );
		$this->slug  = 'tcc_address';
		$this->address = array(
			'street' => __( 'Street Address', 'tcc-fluid' ),
			'local'  => __( 'City', 'tcc-fluid' ),
			'region' => __( 'State', 'tcc-fluid' ),
			'code'   => __( 'Zipcode', 'tcc-fluid' ),
			'phone'  => __( 'Contact Number', 'tcc-fluid' ),
			'email'  => __( 'Email Address', 'tcc-fluid' ),
		);
		parent::__construct();
	}

	public function inner_widget( $args, $instance ) { ?>
		<div class="widget-address" <?php microdata()->Organization(); ?>>
			<h2 itemprop="name"><?php
				bloginfo('name'); ?>
			</h2>
			<address <?php microdata()->PostalAddress(); ?>><?php
				if ( ! empty( $instance['street'] ) ) {
					echo wp_kses( microdata()->street( $instance['street'] ), fluid()->kses() );
				} ?>
				<span class="comma-after" itemprop="addressLocality">
					<?php echo esc_html( $instance['local'] ); ?>
				</span>&nbsp;
				<span itemprop="addressRegion"><?php
					echo esc_html( $instance['region'] );
					if ( ! empty( $instance['code'] ) ) { ?>
						</span>&nbsp;
						<span itemprop="postalCode"><?php
							echo esc_html( $instance['code'] );
					} ?>
				</span>
				<br>
			</address><?php
			if ( ! empty( $instance['phone'] ) ) {
				$attrs = array(
					'href' => 'phone:' . preg_replace( "/[^0-9]/", "", $instance['phone'] )
				);
				$text = sprintf(
					esc_html_x( 'Office: %s', 'phone number', 'tcc-fluid' ),
					microdata()->telephone( fluid_format_phone_number( $instance['phone'] ) )
				);
				fluid()->element( 'a', $attrs, $text, true ); ?>
				<br><?php
			}
			if ( ! empty( $instance['email'] ) ) {
				printf(
					esc_html_x( 'Email: %s', 'email address', 'tcc-fluid' ),
					microdata()->email_format( $instance['email'] )
				); ?>
				<br><?php
			}
			if (!empty($instance['map']) && ($instance['map']==='on')) {
				$add = urlencode($instance['street'].', '.$instance['local'].', '.$instance['region'].' '.$instance['code']); ?>
				<div>
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3248.1007959100875!2d-79.1893191848468!3d35.50178578023649!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89aca610f70e7563%3A0xbc2f0b4f4c8e88a6!2s<?php echo esc_attr( $add ); ?>!5e0!3m2!1sen!2sus!4v1481581752243" width="100%" height="auto" frameborder="0" style="border:0" allowfullscreen></iframe>
				</div><?php
			} ?>
		</div><?php
	}

	public function form( $instance ) {
		if ( empty( $instance['email'] ) ) {
			$instance['email'] = get_option( 'admin_email' );
		}
		parent::form( $instance ); ?>
		<p><?php
			esc_html_e( 'Caution:  We advise that you think very carefully about putting your address out on the internet.', 'tcc-fluid' ); ?>
		</p><?php
		foreach( $this->address as $slug => $text ) {
			$this->form_field( $instance, $slug, $text );
		}
		$this->form_checkbox( $instance, 'map', __( 'Display Google map', 'tcc-fluid' ) );
	}

	public function update( $new, $old ) {
		$instance = parent::update( $new, $old );
		foreach( $this->address as $slug => $text ) {
			$instance[ $slug ] = ( ! empty( $new[ $slug ] ) ) ? wp_strip_all_tags( $new[ $slug ] ) : '';
		}
		$instance['map'] = ( ! empty( $new['map'] ) ) ? $new['map'] : 'off';
		return $instance;
	}

}
