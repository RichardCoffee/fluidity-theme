// js/admin-form.js

jQuery(document).ready(function() {
//	showhideAdminElements('.tcc-loca','.tcc-wp_posi','dashboard');
//	showhideAdminElements( '.social-option-active',  '.social-option-icon',     'yes');
	showhideAdminElements( '.agent-role-active',     '.agent-role-setting',     'agents');
	showhideAdminElements( '.fluid-sidebar-active',  '.fluid-sidebar-setting',  'no');
	showhideAdminElements( '.no-sidebar-active',     '.mobile-sidebar-setting',  null, 'none');
	showhideAdminElements( '.privacy-blog-active',   '.privacy-blog-option',    'yes');
	showhideAdminElements( '.privacy-multi-active',  '.privacy-multi-option',   'filter');
	showhideAdminElements( '.privacy-plugin-active', '.privacy-plugin-filter',  'filter');
	showhideAdminElements( '.privacy-theme-active',  '.privacy-theme-filter',   'filter');

	if ( tcc_admin_options.showhide ) {
		jQuery.each( tcc_admin_options.showhide, function( counter, item ) {
console.log(item);
			targetableElements( item );
		});
	}

	showhideElements( jQuery( '.showhide' ) );
	jQuery( '.form-colorpicker'  ).wpColorPicker();
	jQuery( '.form-image'        ).click( function( e ) { imageUploader( this, e ); });
	jQuery( '.form-image-delete' ).click( function( e ) { imageDelete( this ); });
});

function imageDelete( el ) {
	var ans = confirm( 'Remove this image?' ); // FIXME: localize this
	if ( ans ) {
		var iuField = jQuery( el.parentNode ).data( 'field' );
		var iuInput = document.getElementById( iuField + '_input' );
		var iuImage = document.getElementById( iuField + '_img' );
		iuInput.value = '';
		iuImage.src   = '';
		jQuery( iuImage ).addClass( 'hidden' );
	}
}

function imageUploader( el, e ) {
	e.preventDefault();
	var iuTitle  = jQuery( el.parentNode ).data( 'title' );
	var iuButton = jQuery( el.parentNode ).data( 'button' );
	var iuField  = jQuery( el.parentNode ).data( 'field' );
	var custom_uploader = wp.media({
		title: iuTitle,
		button: { text: iuButton, },
		multiple: false
	});
	custom_uploader.on( 'select', function() {
		var attachment = custom_uploader.state().get( 'selection' ).first().toJSON();
console.log(attachment);
		if ( iuField ) {
			var iuInput = document.getElementById( iuField + '_input' );
			var iuImage = document.getElementById( iuField + '_img' );
			iuInput.value = attachment.url;
			iuImage.src   = attachment.url;
			jQuery( el.parentNode ).children( '.form-image-container' ).removeClass( 'hidden' );
			jQuery( el.parentNode ).children( '.form-image-delete' ).removeClass( 'hidden' );
		}
	});
	custom_uploader.open();
}

function showhideElements( els ) {
	jQuery( els ).each( function( el ) {
		var target = jQuery( el ).attr( 'data-item' );
		var show   = jQuery( el ).attr( 'data-show' );
		showhideAdminElements( el, target, show, null );
	});
}

function showhidePosi( el, target, show, hide ) {
	if ( el ) {
		var eldiv = el.parentNode.parentNode.parentNode;
//		var eldiv = document.querySelector( selector );
		if ( eldiv ) {
			showhideAdminElements( eldiv, target, show, hide );
		}
	}
}

function targetableElements( item ) {
	showhideAdminElements( item.origin, item.target, item.show, item.hide );
}

function showhideAdminElements( origin, target, show, hide ) {
	if ( origin && target ) {
		var radio = jQuery( origin ).find( 'input:radio:checked' );
		if ( radio ) {
			var state = jQuery( radio ).val();
			if ( show ) {
				if ( state === show ) {
					jQuery( target ).parent().parent().show( 2000 ); //removeClass('hidden');
				} else {
					jQuery( target ).parent().parent().hide( 2000 ); //addClass('hidden');
				}
			} else if ( hide ) {
				if ( state === hide ) {
					jQuery( target ).parent().parent().hide( 2000 ); //addClass('hidden');
				} else {
					jQuery( target ).parent().parent().show( 2000 ); //removeClass('hidden');
				}
			}
		}
	}
}

// Browser compatibility function taken from http://stackoverflow.com/questions/6548748/portability-of-nextelementsibling-nextsibling
// the jquery .next() function is not reliable under certain circumstances - ie: when the DOM element has been dynamically added
function nextElementSibling( el ) {
  if ( el.nextElementSibling ) return el.nextElementSibling;
  do { el = el.nextSibling } while ( el && el.nodeType !== 1 );
  return el;
}
