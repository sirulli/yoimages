<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

function wprie_crop_image() {
	$req_post = esc_html( $_POST['post'] );
	if ( current_user_can( 'edit_post', $req_post ) ) {
		$req_size = esc_html( $_POST['size'] );
		$req_width = esc_html( $_POST['width'] );
		$req_height = esc_html( $_POST['height'] );
		$req_x = esc_html( $_POST['x'] );
		$req_y = esc_html( $_POST['y'] );
		$req_quality = esc_html( $_POST['quality'] );
		$img_path = _load_image_to_edit_path( $req_post );
		$img_editor = wp_get_image_editor( $img_path );
		if ( is_wp_error( $img_editor ) ) {
			// TODO error handling
			return false;
		}
		$cropped_image_sizes = wprie_get_image_sizes( $req_size );
		$img_editor->crop( $req_x, $req_y, $req_width, $req_height, $cropped_image_sizes['width'], $cropped_image_sizes['height'], false );
		$img_editor->set_quality( $req_quality );
		$img_path_parts = pathinfo($img_path);
		$attachment_metadata = wp_get_attachment_metadata( $req_post );
		if ( empty( $attachment_metadata['sizes'][$req_size] ) || empty( $attachment_metadata['sizes'][$req_size]['file'] ) ) {
			$cropped_image_filename = wprie_get_cropped_image_filename( $img_path_parts['filename'], $cropped_image_sizes['width'], $cropped_image_sizes['height'], $img_path_parts['extension'] );
			$attachment_metadata['sizes'][$req_size] = array(
				'file' => $cropped_image_filename,
				'width' => $cropped_image_sizes['width'],
				'height' => $cropped_image_sizes['height'],
				'mime-type' => $attachment_metadata['sizes']['thumbnail']['mime-type']
			);
		} else {
			$cropped_image_filename = $attachment_metadata['sizes'][$req_size]['file'];
		}
		$img_editor->save( $img_path_parts['dirname'] . '/' . $cropped_image_filename );
		$attachment_metadata['sizes'][$req_size]['width'] = $cropped_image_sizes['width'];
		$attachment_metadata['sizes'][$req_size]['height'] = $cropped_image_sizes['height'];
		if ( empty( $attachment_metadata['wprie_attachment_metadata']['crop'] ) ) {
			$attachment_metadata['wprie_attachment_metadata']['crop'] = array();
		}
		$attachment_metadata['wprie_attachment_metadata']['crop'][$req_size] = array(
				'x' => $req_x,
				'y' => $req_y,
				'width' => $req_width,
				'height' => $req_height
		);
		wp_update_attachment_metadata( $req_post, $attachment_metadata );
		echo $cropped_image_filename;
	}
	die();
}

function wprie_edit_thumbnails_page() {
	global $wprie_image_id;
	global $wprie_image_size;
	$wprie_image_id = esc_html( $_GET ['post'] );
	$wprie_image_size = esc_html( $_GET ['size'] );
	if (current_user_can ( 'edit_post', $wprie_image_id )) {
		include (WPRIE_PATH . 'inc/html/edit-image-size.php');
	} else {
		die ();
	}
}

add_action( 'wp_ajax_wprie_edit_thumbnails_page', 'wprie_edit_thumbnails_page' );
add_action( 'wp_ajax_wprie_crop_image', 'wprie_crop_image' );