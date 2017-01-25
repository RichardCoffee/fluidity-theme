<?php

if (!class_exists('BuddyPress')) {


	function tcc_buddypress_test_function() {
		log_entry('tcc_buddypress_test_function');
	}
	add_action('bb_head','tcc_buddypress_test_function');



}
