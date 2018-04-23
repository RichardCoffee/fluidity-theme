// https://florianbrinkmann.com/en/3783/conditional-displaying-and-hiding-of-customizer-controls-via-javascript/
;(function () {
	/**
	 * Run function when customizer is ready.
	 */
	wp.customize.bind('ready', function () {

		wp.customize.control('sidebar_position', function (control) {
			/**
			 * Run function on setting change of control.
			 */
			control.setting.bind(function (value) {
				switch (value) {
					/**
					 * The select was switched to the hide option.
					 */
					case 'none':
						/**
						 * Deactivate the conditional control.
						 */
						wp.customize.control('sidebar_fluidity').deactivate();
control.active.set( false );
control.active.validate = function() {
	return false; // Prevent preview from updating state.
};
						break;
					/**
					 * The select was switched to »show«.
					 */
//					case 'show':
					default:
						/**
						 * Activate the conditional control.
						 */
						wp.customize.control('sidebar_fluidity').activate();
						break;
				}
			});
		});
	});
})();
/*
// http://justintadlock.com/archives/2015/05/26/multiple-checkbox-customizer-control
jQuery( document ).ready( function() {

    /* === Checkbox Multiple Control === */
/*
    jQuery( '.customize-control-checkbox-multiple input[type="checkbox"]' ).on(
        'change',
        function() {

            checkbox_values = jQuery( this ).parents( '.customize-control' ).find( 'input[type="checkbox"]:checked' ).map(
                function() {
                    return this.value;
                }
            ).get().join( ',' );

            jQuery( this ).parents( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );
        }
    );

} ); // jQuery( document ).ready
*/
