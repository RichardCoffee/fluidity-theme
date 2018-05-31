
(function () {

	var api = wp.customize;

	/**
	 * Run function when customizer is ready.
	 */
	api.bind('ready', function () {

		for ( var target in fluid_customize ) {
			// wp-content/themes/twentyseventeen/assets/js/customize-controls.js
			api( fluid_customize[ target ].control, function( setting ) {
				api.control( target, function( control ) {
					var visibility = function() {
						if ( fluid_customize[ control.id ].action === 'hide' ) {
							if ( fluid_customize[ control.id ].setting === setting.get() ) {
								control.container.slideUp( 180 );
								//       or
								//control.deactivate();
								//       or
								//control.active.set( false );
								//control.active.validate = function() { return false; };
							} else {
								control.container.slideDown( 180 );
								//control.activate();
							}
						}
						if ( fluid_customize[ control.id ].action === 'show' ) {
							if ( fluid_customize[ control.id ].setting === setting.get() ) {
								control.container.slideDown( 180 );
								//control.activate();
							} else {
								control.container.slideUp( 180 );
								//control.deactivate();
							}
						}
					};
					visibility();
					setting.bind( visibility );
				} );
			} );
		}

	} );
} )();

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
