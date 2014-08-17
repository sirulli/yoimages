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

if ( is_admin () ) {
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
}

?>