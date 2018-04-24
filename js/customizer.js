// https://florianbrinkmann.com/en/3783/conditional-displaying-and-hiding-of-customizer-controls-via-javascript/
;(function () {
	/**
	 * Run function when customizer is ready.
	 */
	wp.customize.bind('ready', function () {

		// wp-content/themes/twentyseventeen/assets/js/customize-controls.js
		wp.customize( 'sidebar_position', function( setting ) {
			wp.customize.control( 'sidebar_fluidity', function( control ) {
console.log('setting: '+setting.get());
				var visibility = function() {
					if ( 'none' === setting.get() ) {
						control.container.slideUp( 180 );
					} else {
						control.container.slideDown( 180 );
					}
				};
				visibility();
				setting.bind( visibility );
			} );
		} );
/*
		wp.customize.control('sidebar_position', function (control) {
			/**
			 * Run function on setting change of control.
			 * /
			control.setting.bind(function (value) {
				switch (value) {
					/**
					 * The select was switched to the hide option.
					 * /
					case 'none':
						/**
						 * Deactivate the conditional control.
						 * /
						wp.customize.control('sidebar_fluidity').deactivate();
wp.customize.control('sidebar_fluidity').active.set( false );
wp.customize.control('sidebar_fluidity').active.validate = function() {
	return false; // Prevent preview from updating state.
};
						break;
					/**
					 * The select was switched to »show«.
					 * /
//					case 'show':
					default:
						/**
						 * Activate the conditional control.
						 * /
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
