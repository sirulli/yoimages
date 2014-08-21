//TODO better js
function wprieCropImage() {
	var data = jQuery('#wprie-cropper').cropper('getData');
	data['action'] = 'wprie_crop_image';
	data['post'] = wprie_image_id;
	data['size'] = wprie_image_size;
	jQuery.post(ajaxurl, data, function(response) {
		jQuery('body').append(response);
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

	if (typeof wprie_cropper_aspect_ratio !== 'undefined') {
		$('#wprie-cropper').cropper({
			aspectRatio : wprie_cropper_aspect_ratio,
			minWidth : wprie_cropper_min_width,
			minHeight : wprie_cropper_min_height,
			modal : true
		});
	}

});