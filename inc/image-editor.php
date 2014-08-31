<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

function wprie_crop_image() {
	$img_path = _load_image_to_edit_path( $_POST['post'] );
	$img_editor = wp_get_image_editor( $img_path );
	if ( is_wp_error( $img_editor ) ) {
		// TODO error handling
		return false;
	}
	$cropped_image_sizes = wprie_get_image_sizes( $_POST['size'] );
	$img_editor->crop( $_POST['x'], $_POST['y'], $_POST['width'], $_POST['height'] );
	$img_editor->resize( $cropped_image_sizes['width'], $cropped_image_sizes['height'] );
	$img_path_parts = pathinfo($img_path);
	$attachment_metadata = wp_get_attachment_metadata( $_POST['post'] );
	if ( empty( $attachment_metadata['sizes'][$_POST['size']] ) || empty( $attachment_metadata['sizes'][$_POST['size']]['file'] ) ) {
		$cropped_image_filename = wprie_get_cropped_image_filename( $img_path_parts['filename'], $cropped_image_sizes['width'], $cropped_image_sizes['height'], $img_path_parts['extension'] );
		$attachment_metadata['sizes'][$_POST['size']] = array(
			'file' => $cropped_image_filename,
			'width' => $cropped_image_sizes['width'],
			'height' => $cropped_image_sizes['height'],
			'mime-type' => $attachment_metadata['sizes']['thumbnail']['mime-type']
		);
	} else {
		$cropped_image_filename = $attachment_metadata['sizes'][$_POST['size']]['file'];
	}
	$img_editor->save( $img_path_parts['dirname'] . '/' . $cropped_image_filename );
	$attachment_metadata['sizes'][$_POST['size']]['width'] = $cropped_image_sizes['width'];
	$attachment_metadata['sizes'][$_POST['size']]['height'] = $cropped_image_sizes['height'];
	//TODO a custom meta here
	wp_update_attachment_metadata( $_POST['post'], $attachment_metadata );
	echo $cropped_image_filename;
	die();
}

function wprie_edit_thumbnails_page() {
	global $wprie_image_id;
	global $wprie_image_size;
	$wprie_image_id = $_GET ['post'];
	$wprie_image_size = $_GET ['size'];
	if (current_user_can ( 'edit_post', $wprie_image_id )) {
		include (WPRIE_PATH . 'inc/html/edit-image-size.php');
	} else {
		die ();
	}
}

add_action( 'wp_ajax_wprie_edit_thumbnails_page', 'wprie_edit_thumbnails_page' );
add_action( 'wp_ajax_wprie_crop_image', 'wprie_crop_image' );