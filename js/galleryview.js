
jQuery(document).ready(function() {
	tcc_gallery.gallery = jQuery('.tcc-galleryview');
	jQuery('.tcc-galleryview').galleryView();

	function resizeTheGallery() {
		var totalhorizontalmargin = 10;
		var totalverticalmargin = 15;
		var new_frame_width  = jQuery('#'+tcc_gallery.div_id).width()/900*150;//just some random factor...
		var new_frame_height = jQuery('#'+tcc_gallery.div_id).width()/900*80;
		var new_panel_width  = jQuery('#'+tcc_gallery.div_id).width()-totalhorizontalmargin;
		var new_panel_height = jQuery('#'+tcc_gallery.div_id).height()-new_frame_height-totalverticalmargin;
		tcc_gallery.gallery.resizeGalleryView(new_panel_width, new_panel_height, new_frame_width, new_frame_height);
	}

	jQuery(window).resize(resizeTheGallery);
	resizeTheGallery();

});
