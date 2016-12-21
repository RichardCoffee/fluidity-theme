$(window).scroll(function() {
if ($(this).scrollTop() > 1){  
    $('header').addClass("sticky");
	$('#logo').addClass("sticky");
  }
  else{
    $('header').removeClass("sticky");
	$('#logo').removeClass("sticky");
  }
});