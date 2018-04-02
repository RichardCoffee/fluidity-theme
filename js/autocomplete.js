
jQuery(document).ready(function ($){
	$(".searchform-input").autocomplete({
		delay: 0,
		minLength: 0,
		source: function(req, response){
			$.getJSON(FluidAutocomplete.url+'?callback=?&action='+FluidAutocomplete.action, req, response);
		},
		select: function(event, ui) {
			window.location.href=ui.item.link;
		},
	});
});
