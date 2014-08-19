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

function wprie_edit_thumbnails_page() {
	global $wprie_image_id;
	global $wprie_image_size;
	$wprie_image_id = $_GET ['post'];
	$wprie_image_size = $_GET ['size'];
	if (current_user_can ( 'edit_post', $wprie_image_id )) {
		wp_enqueue_style( 'wprie-cropper-css', WPRIE_URL . 'js/cropper/cropper.min.css' );
		wp_enqueue_script( 'wprie-cropper-js', WPRIE_URL . 'js/cropper/cropper.min.js', array( 'jquery' ), false, true );
		include (WPRIE_PATH . 'inc/html/edit-image-size.php');
	} else {
		die ();
	}
}
function wprie_register_edit_thumbnails_page() {
	add_submenu_page ( null, 'Edit Thumbnails Page', 'Edit Thumbnails Page', 'manage_options', WPRIE_EDIT_IMAGE_ACTION, 'wprie_edit_thumbnails_page' );
}

if ( is_admin () ) {
	wp_enqueue_style( 'wprie-admin-css', WPRIE_URL . 'css/wprie-admin.css' );
	wp_enqueue_script( 'wprie-admin-js', WPRIE_URL . 'js/wprie-admin.js', array( 'jquery' ), false, true );
	add_action( 'wp_ajax_wprie_get_images', 'wprie_get_images' );
	$wprie_post_id = $_GET['post'];
	if ( ! empty( $wprie_post_id ) ) {
		?>
		<script>
		var wprie_post_id = <?php echo $wprie_post_id; ?>;
		</script>
		<?php
	}
	add_action ( 'admin_menu', 'wprie_register_edit_thumbnails_page' );
}
