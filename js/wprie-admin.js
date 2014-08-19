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
		$("#wprie-cropper").cropper({
			aspectRatio : wprie_cropper_aspect_ratio,
			minWidth : wprie_cropper_min_width,
			minHeight : wprie_cropper_min_height,
			modal: true,
			done : function(data) {
				// Crop image with the data
			}
		});
	}

});