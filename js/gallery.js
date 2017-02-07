
jQuery( document ).ready( function() {
	jQuery( '#add-' + tcc_gallery.div_id ).click( function( e ) { liMediaUploader( this, e ); });
	jQuery( '.delete-image' ).click( function( e )    { deleteImage( this, e ); });
});

function deleteImage( el, e ) {
	e.preventDefault();
	var ans = confirm( tcc_gallery.confirm );
	if ( ans ) {
		var list  = document.getElementById( tcc_gallery.div_id );
console.log(list);
		var field = appendElement( list, 'input', null, 'delete_image[]', null, 'hidden' );
console.log(field);
		var imgid = jQuery( el.parentNode ).find( tcc_gallery.img_css ).attr( 'data-id' );
console.log(imgid);
		field.value = imgid;
console.log(field.value);
		jQuery( el.parentNode ).addClass( 'hidden' );
	}
}

function liMediaUploader( el, e ) {
	e.preventDefault();
	var custom_uploader = wp.media({
		title: tcc_gallery.title,
		library: { type: 'image' },
		button:  { text: tcc_gallery.button },
		multiple: true,
		});
	custom_uploader.on( 'select', function() {
		var images = custom_uploader.state().get( 'selection' );
		images.each( function( upload ) {
			var image = upload.toJSON();
			var list_div = document.getElementById( tcc_gallery.div_id );
			var new_list = appendElement( list_div, 'div', null, null, tcc_gallery.div_img );
			var new_span = appendElement( new_list, 'span', null, null, tcc_gallery.icon );
			jQuery( new_span ).click( function( e ) { deleteImage( this, e ); });
			var new_img  = appendElement( new_list, 'img', null, null, tcc_gallery.img_css );
			new_img.src  = image.sizes.thumbnail.url;
			new_img.setAttribute( 'data-id', image.id );
			var new_inp  = appendElement( new_list, 'input', null, tcc_gallery.field , null, 'hidden' );
			new_inp.value = image.id;
		});
	});
	custom_uploader.open();
}

function showEditField( el ) {
	var field = el.id.split('_')[1];
	if ( el.value === 'add-new-' + field ) {
		jQuery( '#meta_add_' + field ).parent().removeClass( 'hideme' );
		jQuery( '#add_' + field ).focus();
	} else {
		jQuery( '#meta_add_' + field ).parent().addClass( 'hideme' );
	}
}
