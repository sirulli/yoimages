<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

function wprie_get_images() {
	global $wprie_image_id;
	$wprie_image_id = $_POST['post'];
	include (WPRIE_PATH . 'inc/html/image-sizes.php');
	die();
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
	// TODO fix this $cropped_image_filename updating filename and sizes too
	$cropped_image_filename = $img_path_parts['filename'] . '-' . $cropped_image_sizes['width'] . 'x' . $cropped_image_sizes['height'] . '.' . $img_path_parts['extension'];
	$img_editor->save( $img_path_parts['dirname'] . '/' . $cropped_image_filename );
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

if ( is_admin () ) {
	wp_enqueue_style( 'wprie-admin-css', WPRIE_URL . 'css/wprie-admin.css' );
	wp_enqueue_style( 'wprie-cropper-css', WPRIE_URL . 'js/cropper/cropper.min.css' );
	wp_enqueue_script( 'wprie-cropper-js', WPRIE_URL . 'js/cropper/cropper.min.js', array( 'jquery' ), false, true );
	wp_enqueue_script( 'wprie-admin-js', WPRIE_URL . 'js/wprie-admin.js', array( 'wprie-cropper-js' ), false, true );
	add_action( 'wp_ajax_wprie_get_images', 'wprie_get_images' );
	add_action( 'wp_ajax_wprie_edit_thumbnails_page', 'wprie_edit_thumbnails_page' );
	add_action( 'wp_ajax_wprie_crop_image', 'wprie_crop_image' );
	$wprie_post_id = $_GET['post'];
	if ( ! empty( $wprie_post_id ) ) {
		//TODO check if actually needed this
		?>
		<script>
		var wprie_post_id = <?php echo $wprie_post_id; ?>;
		</script>
		<?php
	}
}
