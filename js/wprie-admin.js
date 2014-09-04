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

function wprieExtendMediaLightboxTemplate(anchor1, anchor2, anchor3, anchor4) {
	var attachmentDetailsTmpl = jQuery('#tmpl-attachment-details').text();
	attachmentDetailsTmpl = attachmentDetailsTmpl.replace(/(<a class="edit-attachment"[^>]+[^<]+<\/a>)/, '\n$1' + anchor1);
	jQuery('#tmpl-attachment-details').text(attachmentDetailsTmpl);
	var attachmentDetailsTmplTwoColumn = jQuery('#tmpl-attachment-details-two-column').text();
	attachmentDetailsTmplTwoColumn = attachmentDetailsTmplTwoColumn.replace(/(<a class="view-attachment"[^>]+[^<]+<\/a>[^<]+)<a/, '\n$1' + anchor2 + ' | <a');
	attachmentDetailsTmplTwoColumn = attachmentDetailsTmplTwoColumn.replace(/(<a class="button edit-attachment"[^>]+[^<]+<\/a>)/, '\n$1' + anchor3);
	jQuery('#tmpl-attachment-details-two-column').text(attachmentDetailsTmplTwoColumn);
	var imageDetailsTmpl = jQuery('#tmpl-image-details').text();
	imageDetailsTmpl = imageDetailsTmpl.replace(/(<input type="button" class="replace-attachment button")/, anchor4 + '\n$1');
	jQuery('#tmpl-image-details').text(imageDetailsTmpl);
}

function wprieInitCropImage() {
	if (typeof wprie_cropper_aspect_ratio !== 'undefined') {
		function adaptCropPreviewWidth() {
			var width = Math.min(jQuery('#wprie-cropper-preview-title').width(), wprie_cropper_min_width);
			jQuery('#wprie-cropper-preview').css({
				'height' : (width / wprie_cropper_aspect_ratio) + 'px',
				'width' : width + 'px'
			});
		}
		jQuery(window).resize(adaptCropPreviewWidth);
		adaptCropPreviewWidth();
		jQuery('#wprie-cropper').cropper({
			aspectRatio : wprie_cropper_aspect_ratio,
			minWidth : wprie_cropper_min_width,
			minHeight : wprie_cropper_min_height,
			modal : true,
			preview : '#wprie-cropper-preview'
		});
	}
}

function wprieCancelCropImage() {
	jQuery('#wprie-cropper-wrapper').remove();
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
	});

}

jQuery(document).ready(function($) {

	if (typeof wprie_post_id !== 'undefined') {
		var editImageBtn = $('#imgedit-open-btn-' + wprie_post_id);
		if (editImageBtn.length) {
			var data = {
				'action' : 'wprie_get_edit_image_anchor',
				'post' : wprie_post_id,
				'classes' : 'button'
			};
			jQuery.post(ajaxurl, data, function(response) {
				editImageBtn.after(response);
			});
		}
	}

	wprieAddEditImageAnchors();

	$(document).on('click', 'a.wprie-thickbox', function(e) {
		e.preventDefault();
		var currEl = $(this)
		$.get(currEl.attr('href'), function(data) {
			if (currEl.hasClass('wprie-thickbox-partial')) {
				$('#wprie-cropper-wrapper .media-modal-content').empty().append(data);
			} else {
				$('body').append(data);
			}
		});
		return false;
	});
	$(document).on('click', function(e) {
		if ($(e.target).attr('id') === 'wprie-cropper-bckgr') {
			wprieCancelCropImage();
		}
	});
	$(document).on('keydown', function(e) {
		if (e.keyCode === 27) {
			wprieCancelCropImage();
			return false;
		}
	});

	if ($('input#large_size_h').length) {
		var data = {
			'action' : 'wprie_get_custom_sizes_table_rows'
		};
		$.post(ajaxurl, data, function(response) {
			$('input#large_size_h').parents('table.form-table').append(response);
		});
	}

});
