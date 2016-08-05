// js/basic-form.js

jQuery(document).ready(function() {
  showhidePosi();
  showhideSocialIcons();
  jQuery('.form-colorpicker' ).wpColorPicker();
  jQuery('.form-image'       ).click(function(e) { imageUploader(this,e); });
  jQuery('.form-image-delete').click(function(e) { imageDelete(this); });
});

function imageDelete(el) {
  var ans = confirm('Remove this image?');
  if (ans) {
    var iuField = jQuery(el.parentNode).data('field');
    var iuInput = document.getElementById(iuField+'_input');
    var iuImage = document.getElementById(iuField+'_img');
    iuInput.value = '';
    iuImage.src   = '';
    jQuery(el).addClass('hidden');
  }
}

function imageUploader(el,e) {
  e.preventDefault();
  var iuTitle  = jQuery(el.parentNode).data('title');
  var iuButton = jQuery(el.parentNode).data('button');
  var iuField  = jQuery(el.parentNode).data('field');
  var custom_uploader = wp.media({
    title: iuTitle,
    button: { text: iuButton, },
    multiple: false
  });
  custom_uploader.on('select', function() {
    var attachment = custom_uploader.state().get('selection').first().toJSON();
console.log(attachment);
    if (iuField) {
      var iuInput = document.getElementById(iuField+'_input');
      var iuImage = document.getElementById(iuField+'_img');
      iuInput.value = attachment.url;
      iuImage.src   = attachment.url;
      jQuery(el.parentNode).children('.form-image-container').removeClass('hidden');
      jQuery(el.parentNode).children('.form-image-delete').removeClass('hidden');
    }
  });
  custom_uploader.open();
}

function showhidePosi() {
  if (jQuery('.tcc-wp_posi')) {
    if (jQuery('.tcc-loca input:radio:checked').val()=='dashboard') {
      jQuery('.tcc-wp_posi').parent().parent().removeClass('hidden');
    } else {
      jQuery('.tcc-wp_posi').parent().parent().addClass('hidden');
    }
  }
}

function showhideSocialIcons() {
  if (jQuery('.social-option-icon')) {
    if (jQuery('.social-option-active input:radio:checked').val()=='yes') {
      jQuery('.social-option-icon').parent().parent().removeClass('hidden');
    } else {
      jQuery('.social-option-icon').parent().parent().addClass('hidden');
    }
  }
}

// Browser compatibility function taken from http://stackoverflow.com/questions/6548748/portability-of-nextelementsibling-nextsibling
// the jquery .next() function is not reliable under certain circumstances - ie: when the DOM element has been dynamically added
function nextElementSibling(el) {
  if (el.nextElementSibling) return el.nextElementSibling;
  do { el = el.nextSibling } while (el && el.nodeType !== 1);
  return el;
}
