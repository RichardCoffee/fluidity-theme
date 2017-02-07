
jQuery(document).ready(function() {
  jQuery('#addListingImage').click(function(e) { liMediaUploader(this,e); });
  jQuery('.delete-image').click(function(e)    { deleteImage(this,e); });
});

function deleteImage(el,e) {
  e.preventDefault();
  var ans = confirm('Remove this image?');
  if (ans) {
    var list  = document.getElementById('listing-images');
console.log(list);
    var field = appendElement(list,'input',null,'delete_image[]',null,'hidden');
console.log(field);
    var imgid = jQuery(el.parentNode).find('.attachment-post-thumbnail').attr('data-id');
console.log(imgid);
    field.value = imgid;
console.log(field.value);
    jQuery(el.parentNode).addClass('hidden');
  }
}

function liMediaUploader(el,e) {
  e.preventDefault();
  var custom_uploader = wp.media({
    title: 'Assign/Upload Listing Image',
    library: { type: 'image' },
    button: { text: 'Assign Image' },
    multiple: true,
  });
  custom_uploader.on('select', function() {
    var images = custom_uploader.state().get('selection');
    images.each(function(upload) {
      var image = upload.toJSON();

      var list_div = document.getElementById('listing-images');
      var new_list = appendElement(list_div,'div',null,null,'col span_1_of_6 meta-image');

      var new_span = appendElement(new_list,'span',null,null,'dashicons dashicons-trash delete-image');
      jQuery(new_span).click(function(e) { deleteImage(this,e); });

      var new_img  = appendElement(new_list,'img',null,null,'attachment-post-thumbnail');
      new_img.src  = image.sizes.thumbnail.url;
      new_img.setAttribute('data-id',image.id);

      var new_inp  = appendElement(new_list,'input',null,'prop_images[]',null,'hidden');
      new_inp.value = image.id;
    });
  });
  custom_uploader.open();
}

function showEditField(el) {
  var field = el.id.split('_')[1];
  if (el.value=='add-new-'+field) {
    jQuery('#meta_add_'+field).parent().removeClass('hideme');
    jQuery('#add_'+field).focus();
  } else {
    jQuery('#meta_add_'+field).parent().addClass('hideme');
  }
}
