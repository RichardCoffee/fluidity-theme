$(window).scroll(function() {
	if ($(this).scrollTop() >310){
		$('header').addClass("scrolleffects");
		$('#logo').addClass("scrolleffects");
	} else{
		$('header').removeClass("scrolleffects");
		$('#logo').removeClass("scrolleffects");
	}
});

/* http://blog.bassta.bg/2013/05/smooth-page-scrolling-with-tweenmax/ */

$(function(){

	var $window = $(window);		//Window object

	var scrollTime = 0.5;			//Scroll time
	var scrollDistance = 170;		//Distance. Use smaller value for shorter scroll and greater value for longer scroll

	$window.on("mousewheel DOMMouseScroll", function(event){

		event.preventDefault();

		var delta = event.originalEvent.wheelDelta/120 || -event.originalEvent.detail/3;
		var scrollTop = $window.scrollTop();
		var finalScroll = scrollTop - parseInt(delta*scrollDistance);

		TweenMax.to($window, scrollTime, {
			scrollTo : { y: finalScroll, autoKill:true },
				ease: Power1.easeOut,	//For more easing functions see http://api.greensock.com/js/com/greensock/easing/package-detail.html
				autoKill: true,
				overwrite: 5
			});

	});

});

// Y axis scroll speed
var velocity = 0.2;
function update(){

    var pos = $(window).scrollTop();

    $('.w-scroll').each(function() {
        var $element = $(this);
        // subtract some from the height b/c of the padding
        var height = $element.height()-0;
        $(this).css('backgroundPosition', '50% ' + Math.round((height - pos) * velocity) + 'px');
    });

};
$(window).bind('scroll', update);
