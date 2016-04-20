
//  https://medium.com/@mariusc23/hide-header-on-scroll-down-show-on-scroll-up-67bbaae9a78c
//  http://stackoverflow.com/questions/24765185/hide-fixed-header-on-scroll-down-show-on-scroll-up-and-hover

var autohide = { did:   false, // did a scroll occur?
                 last:  0,     // last scroll position
                 delta: 5,     // action threshold
                 bar:   jQuery('#fluid-header').outerHeight() // height of header
               }

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
  var st = jQuery(this).scrollTop();
  // Make sure they scroll more than delta
  if(Math.abs(autohide.last - st) <= delta) return;
  // If they scrolled down and are past the navbar, add class .nav-up.
  // This is necessary so you never see what is "behind" the navbar.
  if (st > autohide.last && st > autohide.bar){
    // Scroll Down
    jQuery('#fluid-header').hide('slow') // .addClass('nav-hide')
      .hover(function() { // css:  #fluid-header { border-bottom: 1em solid transparent; }
        jQuery("#fluid-header").show('slow'); // .removeClass('nav-hide');
      });
  } else {
    // Scroll Up
    if(st + jQuery(window).height() < jQuery(document).height()) {
      jQuery('#fluid-header').show('slow');  // .removeClass('nav-hide');
    }
  }
  autohide.last = st;
}
