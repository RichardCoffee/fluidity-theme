;(function () {
	/**
	 * Run function when customizer is ready.
	 */
	wp.customize.bind('ready', function () {
		
		wp.customize.control('slug_select_control', function (control) {
			/**
			 * Run function on setting change of control.
			 */
			control.setting.bind(function (value) {
				switch (value) {
					/**
					 * The select was switched to the hide option.
					 */
					case 'hide':
						/**
						 * Deactivate the conditional control.
						 */
						wp.customize.control('slug_conditional_control').deactivate();
						break;
					/**
					 * The select was switched to »show«.
					 */
					case 'show':
						/**
						 * Activate the conditional control.
						 */
						wp.customize.control('slug_conditional_control').activate();
						break;
				}
			});
		});
	});
})();
