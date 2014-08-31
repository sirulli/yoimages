//TODO better js

function wprieAddEditImageAnchors() {
	var wprieAddEditImageAnchorsInterval = setInterval(function() {
		if (jQuery('#media-items .edit-attachment').length) {
			jQuery('#media-items .edit-attachment').each(function(i, k) {
				try {
					var currEl = jQuery(this);
					var mRegexp = /\?post=([0-9]+)/;
					var match = mRegexp.exec(currEl.attr('href'));
					if (!currEl.parent().find('.wprie').length && currEl.parent().find('.pinkynail').attr('src').match(/upload/g)) {
						var data = {
							'action' : 'wprie_get_edit_image_anchor',
							'post' : match[1]
						};
						jQuery.post(ajaxurl, data, function(response) {
							currEl.after(response);
						});
					}
				} catch (e) {
					console.log(e);
				}
			});
		}
	}, 500);
}

function wprieExtendMediaLightboxTemplate(editImageAnchor) {
	var attachmentDetailsTmpl = jQuery('#tmpl-attachment-details').text();
	attachmentDetailsTmpl = attachmentDetailsTmpl.replace(/(<a class="edit-attachment"[^>]+[^<]+<\/a>)/, '\n$1' + editImageAnchor);
	jQuery('#tmpl-attachment-details').text(attachmentDetailsTmpl);
}

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
		jQuery('img[src*=\'' + response + '\']').each(function() {
			var img = jQuery(this);
			var imgSrc = img.attr('src');
			imgSrc = imgSrc + (imgSrc.indexOf('?') > -1 ? '&' : '?') + '_r=' + Math.floor((Math.random() * 100) + 1);
			img.attr('src', imgSrc);
			if (img.parents('.wprie-not-existing-crop').length) {
				img.parents('.wprie-not-existing-crop').removeClass('wprie-not-existing-crop').find('p').hide();
			}
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

	wprieAddEditImageAnchors();

});