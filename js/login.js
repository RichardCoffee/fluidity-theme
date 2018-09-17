
jQuery( document ).ready(function() {
	jQuery( '.show-hide-password' ).click( function() {
		var inp = jQuery( this ).siblings( 'input[name="pwd"]' );
console.log(inp);
		var now = jQuery( inp ).prop( 'type' );
		if ( now === 'password' ) {
			jQuery( inp ).prop( 'type', 'text' );
		} else if ( now === 'text' ) {
			jQuery( inp ).prop( 'type', 'password' );
		}
	});
}
