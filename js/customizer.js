// https://florianbrinkmann.com/en/3783/conditional-displaying-and-hiding-of-customizer-controls-via-javascript/
;(function () {

	var api = wp.customize;

	/**
	 * Run function when customizer is ready.
	 */
	api.bind('ready', function () {

		for ( var key in fluid_customize ) {
console.log(key);
			// wp-content/themes/twentyseventeen/assets/js/customize-controls.js
			api( key, function( setting ) {
				for ( var i in fluid_customize[ key ].control ) {
console.log(fluid_customize[ key ].control[ i ]);
					api.control( fluid_customize[ key ].control[ i ], function( control ) {

// https://wordpress.stackexchange.com/questions/268173/get-a-default-value-of-the-customizer-setting-using-wp-customize-api-js
console.log( "control: ", control );
console.log( "setting: ", setting );

						var visibility = function() {
//							var index = fluid_customize['respond'][ setting.id ];

							if ( fluid_customize[ key ].hide === setting.get() ) {
console.log('hide '+fluid_customize[ key ].control[ i ]+' / '+setting.get());
								control.container.slideUp( 180 );
							} else {
console.log('show '+fluid_customize[ key ].control[ i ]+' / '+setting.get());
								control.container.slideDown( 180 );
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
