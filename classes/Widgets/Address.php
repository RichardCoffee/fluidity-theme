<?php

class TCC_Widgets_Address extends TCC_Widgets_Basic {

	private $address;

	function __construct() {
		#$this->title = get_bloginfo('name');
		$this->title = esc_html__('Address','tcc-fluid');
		$this->desc  = esc_html__('Fluidity address widget','tcc-fluid');
		$this->slug  = 'tcc_address';
		$this->address = array('tcc-street' => __('Street Address','tcc-fluid'),
							'tcc-local'  => __('City','tcc-fluid'),
							'tcc-region' => __('State','tcc-fluid'),
							'tcc-code'   => __('Zipcode','tcc-fluid'),
							'tcc-phone'  => __('Contact Number','tcc-fluid'),
						);
		parent::__construct();
	}

	public function inner_widget($args,$instance) { ?>
		<div class="widget-address" <?php self::$micro->Organization(); ?>>
			<h4 itemprop="name"><?php bloginfo('name'); ?></h4>
			<address <?php self::$micro->PostalAddress(); ?>><?php
				if (!empty($instance['tcc-street'])) { ?>
					<span itemprop="streetAddress">
						<?php echo esc_html($instance['tcc-street']); ?>
					</span><br><?php
				} ?>
				<span itemprop="addressLocality">
					<?php echo esc_html($instance['tcc-local']); ?>
				</span> <span itemprop="addressRegion">
					<?php echo esc_html($instance['tcc-region']);
					if (!empty($instance['tcc-code'])) { ?>
						</span>, <span itemprop="postalCode">
						<?php echo esc_html($instance['tcc-code']);
					} ?>
				</span><br><?php
				if (!empty($instance['tcc-phone'])) {
					esc_html_e('Office: ','tcc-fluid'); ?> <span itemprop="telephone">
					<?php echo esc_html($instance['tcc-phone']); ?>
					</span><br><?php
				}
				esc_html_e('Email: ','tcc-fluid'); ?>
				<a href="mailto:<?php echo get_option('admin_email'); ?>">
					<?php bloginfo ('title');?>
				</a>
			</address><?php
			if (!empty($instance['tcc-map']) && ($instance['tcc-map']==='on')) {
				$add = urlencode($instance['tcc-street'].', '.$instance['tcc-local'].', '.$instance['tcc-region'].' '.$instance['tcc-code']); ?>
				<div>
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3248.1007959100875!2d-79.1893191848468!3d35.50178578023649!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89aca610f70e7563%3A0xbc2f0b4f4c8e88a6!2s<?php echo $add; ?>!5e0!3m2!1sen!2sus!4v1481581752243" width="400" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>
				</div><?php
			} ?>
		</div><?php
	}

	public function form($instance) {
		parent::form($instance);
		echo "<p>Caution:  We advise that you think very carefully about putting your address out on the internet.</p>";
		foreach($this->address as $slug=>$text) {
			$this->form_field($instance, $slug, $text);
		}
		$this->form_checkbox($instance,'tcc-map',__('Display Google map','tcc-fluid'));
	}

	public function update($new,$old) {
		$instance = parent::update($new,$old);
		foreach($this->address as $slug=>$text) {
			$instance[$slug] = (!empty($new[$slug])) ? strip_tags($new[$slug]) : '';
		}
		$instance['tcc-map'] = (!empty($new['tcc-map'])) ? $new['tcc-map'] : 'off';
#log_entry('old',$old,'new',$new,'returned',$instance);
		return $instance;
	}

}
