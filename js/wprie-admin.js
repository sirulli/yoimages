//TODO better js

var wprieMediaUploader;

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
	}, 600);
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
		var cropperData;
		if (typeof wprie_prev_crop_x !== 'undefined') {
			cropperData = {
				x : wprie_prev_crop_x,
				y : wprie_prev_crop_y,
				width : wprie_prev_crop_width,
				height : wprie_prev_crop_height
			};
		} else {
			cropperData = {};
		}
		jQuery('#wprie-cropper-container').css({
			'max-width' : jQuery('#wprie-cropper-wrapper .attachments').width() + 'px',
			'max-height' : jQuery('#wprie-cropper-wrapper .attachments').height() + 'px'
		});
		jQuery('#wprie-cropper').cropper({
			aspectRatio : wprie_cropper_aspect_ratio,
			minWidth : wprie_cropper_min_width,
			minHeight : wprie_cropper_min_height,
			modal : true,
			data : cropperData,
			preview : '#wprie-cropper-preview'
		});

		if (wp.media) { // TODO fix issue:
			jQuery('#wprie-replace-img-btn').show().click(function() {
				if (wprieMediaUploader) {
					// TODO find "the backbone way" solution for dynamic title
					jQuery('#wprie-replace-media-uploader .media-frame-title h1').text(jQuery(this).attr('title'));
					wprieMediaUploader.open();
					return;
				}
				var el = jQuery(this);
				wprieMediaUploader = wp.media({
					id : 'wprie-replace-media-uploader',
					title : el.attr('title'),
					multiple : false,
					button : {
						text : el.attr('data-button-text')
					},
					library : {
						type : 'image'
					}
				});
				wprieMediaUploader.on('select', function() {
					attachment = wprieMediaUploader.state().get('selection').first().toJSON();
					var data = {
						'action' : 'wprie_replace_image_for_size',
						'image' : wprie_image_id,
						'size' : wprie_image_size,
						'replacement' : attachment.id
					};
					jQuery.post(ajaxurl, data, function(response) {
						jQuery('#wprie-cropper-wrapper .wprie-thickbox-partial.active').click();
					});

				});
				wprieMediaUploader.open();
				jQuery('#wprie-replace-media-uploader').parents('.media-modal.wp-core-ui').css('z-index', '17000002');
			});
		}
		jQuery('#wprie-restore-img-btn').click(function() {
			var data = {
				'action' : 'wprie_restore_original_image_for_size',
				'image' : wprie_image_id,
				'size' : wprie_image_size
			};
			jQuery.post(ajaxurl, data, function(response) {
				jQuery('#wprie-cropper-wrapper .wprie-thickbox-partial.active').click();
			});
		});

	}
}

function wprieCancelCropImage() {
	jQuery('#wprie-cropper-wrapper').remove();
}

function wprieCropImage() {
	jQuery('#wprie-cropper-wrapper .media-toolbar-primary .spinner').css('display', 'inline-block');
	var data = jQuery('#wprie-cropper').cropper('getData');
	data['action'] = 'wprie_crop_image';
	data['post'] = wprie_image_id;
	data['size'] = wprie_image_size;
	data['quality'] = jQuery('#wprie-cropper-quality').val();
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
		jQuery('#wprie-cropper-wrapper .media-toolbar-primary .spinner').css('display', 'none');
	});

}

function wprieGetUrlVars() {
	var vars = [], hash;
	var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	for (var i = 0; i < hashes.length; i++) {
		hash = hashes[i].split('=');
		vars.push(hash[0]);
		vars[hash[0]] = hash[1];
	}
	return vars;
}

jQuery(document).ready(function($) {

	if ($('body.post-type-attachment').length) {
		var currPostId = wprieGetUrlVars()['post'];
		var editImageBtn = $('#imgedit-open-btn-' + currPostId);
		if (editImageBtn.length) {
			var data = {
				'action' : 'wprie_get_edit_image_anchor',
				'post' : currPostId,
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
		var currEl = $(this);
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
			$('input#large_size_h').parents('table.form-table').after(response);
		});
	}

});
