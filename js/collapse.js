// js/collapse.js

var collapse = {
	scroll: false,
	fixed:  false
};

jQuery( document ).ready(function() {
	assignCollapse();
	collapse.scroll = jQuery('.scroll-auto')[0];
});

function assignCollapse( elID ) { // Attach toggle event to collapsible areas
	var el = elID || document.body;
	var sm = jQuery('.collapse-auto')[0];
	jQuery( el ).find('.panel-heading').each( function() {
		var precol = sm || jQuery( this ).attr('data-collapse');
		if ( precol ) {
			jQuery( this ).siblings().hide();
		}
		this.onclick = function() {
			clickCollapse( this );
		}
	} );
}

function clickCollapse( el ) {
	var icon = jQuery( el ).find('.panel-sign');
	if ( jQuery( el ).next().is(':hidden') ) {
		if ( icon ) {
			jQuery( icon ).removeClass( col_icons.plus ).addClass( col_icons.minus );
		}
		jQuery( el ).next().show('slow');
		if ( collapse.scroll || jQuery( el ).find('.scroll-this')[0] ) {
			var vert = 0;
			if ( collapse.fixed ) {
				vert -= jQuery('.header-fixed').height();
			} // fixed header
			scrollToElement( el, null, vert );
		}
	} else {
		if ( icon ) {
			jQuery( icon ).removeClass( col_icons.minus ).addClass( col_icons.plus );
		}
		jQuery( el ).next().hide('slow');
	}
}
