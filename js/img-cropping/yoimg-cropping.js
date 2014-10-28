var yoimgMediaUploader;

function yoimgAddEditImageAnchors() {
	setInterval(function() {
		if (jQuery('#media-items .edit-attachment').length) {
			jQuery('#media-items .edit-attachment').each(function(i, k) {
				try {
					var currEl = jQuery(this);
					var mRegexp = /\?post=([0-9]+)/;
					var match = mRegexp.exec(currEl.attr('href'));
					if (!currEl.parent().find('.yoimg').length && currEl.parent().find('.pinkynail').attr('src').match(/upload/g)) {
						var data = {
							'action' : 'yoimg_get_edit_image_anchor',
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
	}, 1000);
}

function yoimgExtendMediaLightboxTemplate(anchor1, anchor2, anchor3, anchor4) {
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

function yoimgInitCropImage() {
	if (typeof yoimg_cropper_aspect_ratio !== 'undefined') {
		function adaptCropPreviewWidth() {
			var width = Math.min(jQuery('#yoimg-cropper-preview-title').width(), yoimg_cropper_min_width);
			jQuery('#yoimg-cropper-preview').css({
				'height' : (width / yoimg_cropper_aspect_ratio) + 'px',
				'width' : width + 'px'
			});
		}
		jQuery(window).resize(adaptCropPreviewWidth);
		adaptCropPreviewWidth();
		var cropperData;
		if (typeof yoimg_prev_crop_x !== 'undefined') {
			cropperData = {
				x : yoimg_prev_crop_x,
				y : yoimg_prev_crop_y,
				width : yoimg_prev_crop_width,
				height : yoimg_prev_crop_height
			};
		} else {
			cropperData = {};
		}
		jQuery('#yoimg-cropper-container').css({
			'max-width' : jQuery('#yoimg-cropper-wrapper .attachments').width() + 'px',
			'max-height' : jQuery('#yoimg-cropper-wrapper .attachments').height() + 'px'
		});
		jQuery('#yoimg-cropper').cropper({
			aspectRatio : yoimg_cropper_aspect_ratio,
			minWidth : yoimg_cropper_min_width,
			minHeight : yoimg_cropper_min_height,
			modal : true,
			data : cropperData,
			preview : '#yoimg-cropper-preview'
		});

		if (wp.media) {
			jQuery('#yoimg-replace-img-btn').show().click(function() {
				if (yoimgMediaUploader) {
					// TODO find "the backbone way" solution for dynamic title
					jQuery('#yoimg-replace-media-uploader .media-frame-title h1').text(jQuery(this).attr('title'));
					yoimgMediaUploader.open();
					return;
				}
				var el = jQuery(this);
				yoimgMediaUploader = wp.media({
					id : 'yoimg-replace-media-uploader',
					title : el.attr('title'),
					multiple : false,
					button : {
						text : el.attr('data-button-text')
					},
					library : {
						type : 'image'
					}
				});
				yoimgMediaUploader.on('select', function() {
					attachment = yoimgMediaUploader.state().get('selection').first().toJSON();
					var data = {
						'action' : 'yoimg_replace_image_for_size',
						'image' : yoimg_image_id,
						'size' : yoimg_image_size,
						'replacement' : attachment.id
					};
					jQuery.post(ajaxurl, data, function(response) {
						yoimgCropImage();
						jQuery('#yoimg-cropper-wrapper .yoimg-thickbox-partial.active').click();
					});

				});
				yoimgMediaUploader.open();
				jQuery('#yoimg-replace-media-uploader').parents('.media-modal.wp-core-ui').css('z-index', '17000002');
			});
		}
		jQuery('#yoimg-restore-img-btn').click(function() {
			var data = {
				'action' : 'yoimg_restore_original_image_for_size',
				'image' : yoimg_image_id,
				'size' : yoimg_image_size
			};
			jQuery.post(ajaxurl, data, function(response) {
				yoimgCropImage();
				jQuery('#yoimg-cropper-wrapper .yoimg-thickbox-partial.active').click();
			});
		});

	}
}

function yoimgCancelCropImage() {
	jQuery('#yoimg-cropper-wrapper').remove();
}

function yoimgCropImage() {
	jQuery('#yoimg-cropper-wrapper .media-toolbar-primary .spinner').css('display', 'inline-block');
	var data = jQuery('#yoimg-cropper').cropper('getData');
	data['action'] = 'yoimg_crop_image';
	data['post'] = yoimg_image_id;
	data['size'] = yoimg_image_size;
	data['quality'] = jQuery('#yoimg-cropper-quality').val();
	jQuery.post(ajaxurl, data, function(response) {
		jQuery('img[src*=\'' + response.filename + '\']').each(function() {
			var img = jQuery(this);
			var imgSrc = img.attr('src');
			imgSrc = imgSrc + (imgSrc.indexOf('?') > -1 ? '&' : '?') + '_r=' + Math.floor((Math.random() * 100) + 1);
			img.attr('src', imgSrc);
			if (img.parents('.yoimg-not-existing-crop').length) {
				img.parents('.yoimg-not-existing-crop').removeClass('yoimg-not-existing-crop').find('.message.error').hide();
			}
		});
		if (response.smaller) {
			jQuery('.message.yoimg-crop-smaller').show();
		} else {
			jQuery('.message.yoimg-crop-smaller').hide();
		}
		jQuery('#yoimg-cropper-wrapper .media-toolbar-primary .spinner').css('display', 'none');
	});

}

function yoimgGetUrlVars() {
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
		var currPostId = yoimgGetUrlVars()['post'];
		var editImageBtn = $('#imgedit-open-btn-' + currPostId);
		if (editImageBtn.length) {
			var data = {
				'action' : 'yoimg_get_edit_image_anchor',
				'post' : currPostId,
				'classes' : 'button'
			};
			jQuery.post(ajaxurl, data, function(response) {
				editImageBtn.after(response);
			});
		}
	}

	yoimgAddEditImageAnchors();

	$(document).on('click', 'a.yoimg-thickbox', function(e) {
		e.preventDefault();
		var currEl = $(this);
		$.get(currEl.attr('href'), function(data) {
			if (currEl.hasClass('yoimg-thickbox-partial')) {
				$('#yoimg-cropper-wrapper .media-modal-content').empty().append(data);
			} else {
				$('body').append(data);
			}
		});
		return false;
	});
	$(document).on('click', '#yoimg-cropper-bckgr', function(e) {
		e.preventDefault();
		yoimgCancelCropImage();
		return false;
	});
	$(document).on('keydown', function(e) {
		if (e.keyCode === 27) {
			yoimgCancelCropImage();
			return false;
		}
	});

	if ($('input#large_size_h').length) {
		var data = {
			'action' : 'yoimg_get_custom_sizes_table_rows'
		};
		$.post(ajaxurl, data, function(response) {
			$('input#large_size_h').parents('table.form-table').after(response);
		});
	}

});
