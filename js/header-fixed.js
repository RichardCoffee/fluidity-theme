
jQuery(document).ready(function (){
	var divHeight = jQuery('.header-fixed').height();
	jQuery('main').css('margin-top', divHeight+'px');
	if (typeof collapse != 'undefined') { collapse.fixed = true; } // collapse declared in js/collapse.js
});
