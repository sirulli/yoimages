jQuery(document).ready(function($) {
	if (typeof wprie_post_id !== 'undefined') {
		var editImageBtn = $('#imgedit-open-btn-' + wprie_post_id);
		if (editImageBtn.length) {
			var data = {
				'action' : 'wprie_get_images',
				'post' : wprie_post_id
			};
			$.post(ajaxurl, data, function(response) {
				$('#media-head-' + wprie_post_id).after(response);
			});
		}
	}
});