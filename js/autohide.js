
//  https://medium.com/@mariusc23/hide-header-on-scroll-down-show-on-scroll-up-67bbaae9a78c
//  http://stackoverflow.com/questions/24765185/hide-fixed-header-on-scroll-down-show-on-scroll-up-and-hover
//  http://www.jqueryscript.net/other/Smooth-Auto-Hide-Header-Navigation-with-jQuery-CSS3.html
//  http://ideasandpixels.com/wp_enqueue_script-inline-script-to-load-after-jquery
//  http://stackoverflow.com/questions/18604022/slide-header-up-if-you-scroll-down-and-vice-versa

const autohide = { bar:   jQuery('.header-hide').outerHeight() // height of header
                 delta: 5,     // action threshold
                 did:   false, // did a scroll occur?
                 doc:   jQuery(document).height(),
                 last:  0,     // last scroll position
                 top:   (jQuery('#wpadminbar')) ? jQuery('#wpadminbar').outerHeight() : 0,
                 win:   jQuery(window).height()
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
console.log(autohide);
  let curr = Math.max(0,(jQuery(this).scrollTop() - autohide.top));
  let diff = curr - autohide.last;
console.log('st: '+curr);
  if(Math.abs(autohide.last - curr) <= autohide.delta) return;
console.log('last: '+autohide.last+'   bar: '+autohide.bar);
  if (curr > autohide.last && curr > autohide.bar){
console.log('scroll down');
    jQuery('.header-hide').css({top:(-autohide.bar)+'px'}); // .hide('slow') // .addClass('nav-hide')
      .hover(function() {
        jQuery(".header-hide").css({top:autohide.top+'px'}); // .show('slow'); // .removeClass('nav-hide');
      });
  } else {
console.log('scroll up');
    if((curr + autohide.win) < autohide.doc) {
      jQuery('.header-hide').css({top:autohide.top+'px'}); // .show('slow'); // .removeClass('nav-hide');
    }
  }
  autohide.last = st;
}

//// normalize treshold range
//autohide.delta = Math.max(0,(((autohide.delta+diff)>headerHeight) ? headerHeight : (autohide.delta+diff)));
//header.css('top', (-autohide.delta)+'px');
//autohide.last = curr;
