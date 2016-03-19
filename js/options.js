
jQuery(document).ready(function() {
  showhideSocialIcons();
}

function showhideSocialIcons() {
  if (jQuery('.social-option-icon')) {
    if (jQuery(".social-option-active input:radio:checked").val()=='yes') {
      jQuery('.social-option-icon').parent().parent().removeClass('hidden');
    } else {
      jQuery('.social-option-icon').parent().parent().addClass('hidden');
    }
  }
}
