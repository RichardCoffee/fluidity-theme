jQuery(window).scroll(function() {
	if (jQuery(this).scrollTop() > 1){  
		jQuery('header').addClass("reduce");
		jQuery('header .logo').addClass("reduce");
	} else{
		jQuery('header').removeClass("reduce");
		jQuery('header .logo').removeClass("reduce");
	}
});
