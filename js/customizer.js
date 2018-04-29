// https://florianbrinkmann.com/en/3783/conditional-displaying-and-hiding-of-customizer-controls-via-javascript/
;(function () {

	var api = wp.customize;

	/**
	 * Run function when customizer is ready.
	 */
	api.bind('ready', function () {

		for ( var target in fluid_customize ) {
			console.log(target);
			// wp-content/themes/twentyseventeen/assets/js/customize-controls.js
			api( fluid_customize[ target ].control, function( setting ) {
					console.log(fluid_customize [ target ].control);
					api.control( target, function( control ) {
						console.log( "control: ", control );
						var visibility = function() {
							var index = fluid_customize[ control.id ].control;
							console.log('id: '+control.id,'index: '+index);
							if ( index ) {
								console.log('check: '+fluid_customize[ index ].setting,'setting: '+setting.get());
								if ( fluid_customize[ index ].setting === setting.get() ) {
									control.container.slideUp( 180 );
								} else {
									control.container.slideDown( 180 );
								}
							}
						};
						visibility();
						setting.bind( visibility );
					} );
				}
			} );
		}


	/***   handle postMessage tasks   ***/

	// Site title.
	api( 'blogname', function( value ) {
		value.bind( function( to ) {
			jQuery( 'a.navbar-brand' ).text( to );
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
*/
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
