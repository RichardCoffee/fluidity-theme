
jQuery( document ).ready(function() {
	jQuery( '.show-hide-password' ).click( function() {
		let inp = jQuery( this ).siblings( 'input[name="pwd"]' );
console.log(inp);
		let now = jQuery( inp ).prop( 'type' );
		if ( now === 'password' ) {
			jQuery( inp ).prop( 'type', 'text' );
			jQuery( this ).children( '.fa-ban' ).removeClass( 'hidden' );
		} else if ( now === 'text' ) {
			jQuery( inp ).prop( 'type', 'password' );
			jQuery( this ).children( '.fa-ban' ).addClass( 'hidden' );
		}
	});
});
