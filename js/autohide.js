
//  https://medium.com/@mariusc23/hide-header-on-scroll-down-show-on-scroll-up-67bbaae9a78c
//  http://stackoverflow.com/questions/24765185/hide-fixed-header-on-scroll-down-show-on-scroll-up-and-hover
//  http://www.jqueryscript.net/other/Smooth-Auto-Hide-Header-Navigation-with-jQuery-CSS3.html
//  http://ideasandpixels.com/wp_enqueue_script-inline-script-to-load-after-jquery

var autohide = { did:   false, // did a scroll occur?
                 last:  0,     // last scroll position
                 delta: 5,     // action threshold
                 bar:   jQuery('#fluid-header').outerHeight() // height of header
                 top:   (jQuery('#wpadminbar')) ? jQuery('#wpadminbar').outerHeight() : 0;
               }

//jQuery(document).ready(function() {
//  jQuery('#fluid-header').next().css({"padding-top":autohide.bar});
//}

jQuery(window).scroll(function(event){
  autohide.did = true;
});

setInterval(function() {
  if (autohide.did) {
    hasScrolled();
    autohide.did = false;
  }
}, 250);

function hasScrolled() {
  var st = Math.max(0,jQuery(this).scrollTop() - autohide.top);
console.log('st: '+st);
  if(Math.abs(autohide.last - st) <= delta) return;
console.log('last: '+autohide.last+'   bar: '+autohide.bar);
  if (st > autohide.last && st > autohide.bar){
console.log('scroll down');
    jQuery('#fluid-header').css({top:(-autohide.bar)+'px'}); // .hide('slow') // .addClass('nav-hide')
      .hover(function() {
        jQuery("#fluid-header").css({top:autohide.top+'px'}); // .show('slow'); // .removeClass('nav-hide');
      });
  } else {
console.log('scroll up');
    if(st + jQuery(window).height() < jQuery(document).height()) {
      jQuery('#fluid-header').css({top:autohide.top+'px'}); // .show('slow'); // .removeClass('nav-hide');
    }
  }
  autohide.last = st;
}
