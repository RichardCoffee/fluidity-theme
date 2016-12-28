jQuery(window).scroll(function() {
if (jQuery(this).scrollTop() >210){  
    jQuery('header, header .logo>img').addClass('reduce');
  }
  else{
    jQuery('header, header .logo>img').removeClass("reduce");
  }
});
