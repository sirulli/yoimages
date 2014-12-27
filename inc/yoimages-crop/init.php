<?php
if (! defined ( 'ABSPATH' )) {
	die ( 'No script kiddies please!' );
}

if (is_admin ()) {
	
	define ( 'YOIMG_CROP_PATH', dirname ( __FILE__ ) );
	
	define ( 'YOIMG_DEFAULT_CROP_ENABLED', TRUE );
	define ( 'YOIMG_DEFAULT_CROP_QUALITIES', serialize ( array (
			100,
			80,
			60 
	) ) );
	$yoimg_crop_settings = get_option ( 'yoimg_crop_settings' );
	define ( 'YOIMG_CROP_ENABLED', $yoimg_crop_settings && isset ( $yoimg_crop_settings ['cropping_is_active'] ) ? $yoimg_crop_settings ['cropping_is_active'] : YOIMG_DEFAULT_CROP_ENABLED );

	define ( 'YOIMG_EDIT_IMAGE_ACTION', 'yoimg-edit-thumbnails' );
	
	if (YOIMG_CROP_ENABLED) {
		define ( 'YOIMG_CROP_URL', plugins_url ( plugin_basename ( YOIMG_CROP_PATH ) ) );
		require_once (YOIMG_CROP_PATH . '/utils.php');
		require_once (YOIMG_CROP_PATH . '/image-editor.php');
		require_once (YOIMG_CROP_PATH . '/extend-admin-media.php');
		require_once (YOIMG_CROP_PATH . '/extend-admin-media-lightbox.php');
		require_once (YOIMG_CROP_PATH . '/extend-admin-post.php');
		require_once (YOIMG_CROP_PATH . '/extend-admin-options-media.php');
	}
}
function yoimg_crop_load_styles_and_scripts($hook) {
	if (YOIMG_CROP_ENABLED) {
		if ($hook == 'post.php') {
			wp_enqueue_media ();
		} else if ($hook == 'upload.php') {
			// issue http://stackoverflow.com/questions/25884434/wordpress-wp-enqueue-media-causes-javascript-error-from-wp-admin-upload-phpmo
			$mode = get_user_option ( 'media_library_mode', get_current_user_id () ) ? get_user_option ( 'media_library_mode', get_current_user_id () ) : 'grid';
			$modes = array (
					'grid',
					'list' 
			);
			if (isset ( $_GET ['mode'] ) && in_array ( $_GET ['mode'], $modes )) {
				$mode = $_GET ['mode'];
				update_user_option ( get_current_user_id (), 'media_library_mode', $mode );
			}
			if ('list' === $mode) {
				wp_dequeue_script ( 'media' );
				wp_enqueue_media ();
			}
		} else {
			wp_enqueue_style ( 'media-views' );
		}
		wp_enqueue_style ( 'yoimg-cropping-css', YOIMG_CROP_URL . '/css/yoimg-cropping.css' );
		wp_enqueue_style ( 'yoimg-cropper-css', YOIMG_CROP_URL . '/js/cropper/cropper.min.css' );
		wp_enqueue_script ( 'yoimg-cropper-js', YOIMG_CROP_URL . '/js/cropper/cropper.min.js', array (
				'jquery' 
		), false, true );
		wp_enqueue_script ( 'yoimg-cropping-js', YOIMG_CROP_URL . '/js/yoimg-cropping.js', array (
				'yoimg-cropper-js' 
		), false, true );
	}
}
add_action ( 'admin_enqueue_scripts', 'yoimg_crop_load_styles_and_scripts' );
