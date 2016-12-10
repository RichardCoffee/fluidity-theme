
jQuery(document).ready(function ($){
	$("#tcc-searchform").autocomplete({
		delay: 0,
		minLength: 0,
		source: function(req, response){
			$.getJSON(TccAutocomplete.url+'?callback=?&action='+TccAutocomplete.action, req, response);
		},
		select: function(event, ui) {
			window.location.href=ui.item.link;
		},
	});
});
