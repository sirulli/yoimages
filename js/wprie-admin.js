//TODO better js

function wprieInitCropImage() {
	if (typeof wprie_cropper_aspect_ratio !== 'undefined') {
		jQuery('#wprie-cropper').cropper({
			aspectRatio : wprie_cropper_aspect_ratio,
			minWidth : wprie_cropper_min_width,
			minHeight : wprie_cropper_min_height,
			modal : true
		});
	}
}

function wprieCancelCropImage() {
	tb_remove();
}

function wprieCropImage() {
	var data = jQuery('#wprie-cropper').cropper('getData');
	data['action'] = 'wprie_crop_image';
	data['post'] = wprie_image_id;
	data['size'] = wprie_image_size;
	jQuery.post(ajaxurl, data, function(response) {
		// TODO handle errors
		jQuery('img[src*=\'' + response + '\']').each(function(){
			var img = jQuery(this);
			var imgSrc = img.attr('src');
			imgSrc = imgSrc + (imgSrc.indexOf('?') > -1 ? '&' : '?') + '_r=' + Math.floor((Math.random() * 100) + 1);
			img.attr('src', imgSrc);
		});
		tb_remove();
	});

}

jQuery(document).ready(function($) {

	if (typeof wprie_post_id !== 'undefined') {
		var editImageBtn = $('#imgedit-open-btn-' + wprie_post_id);
		if (editImageBtn.length) {
			var data = {
				'action' : 'wprie_get_images',
				'post' : wprie_post_id
			};
			$.post(ajaxurl, data, function(response) {
				$('.wp_attachment_details.edit-form-section').after(response);
			});
		}
	}

});