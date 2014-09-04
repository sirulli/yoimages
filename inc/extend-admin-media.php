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
add_action( 'wp_ajax_wprie_get_images', 'wprie_get_images' );

function wprie_media_row_actions( $actions, $post, $detached ) {
	if ( wp_attachment_is_image( $post->ID ) ) {
		$actions['wprie_crop'] = wprie_get_edit_image_anchor( $post->ID );
	}
	return $actions;
}
add_filter( 'media_row_actions', 'wprie_media_row_actions', 10, 3);

$wprie_post_id = $_GET['post'];
if ( ! empty( $wprie_post_id ) ) {
	//TODO check if actually needed this
	?>
	<script>
	var wprie_post_id = <?php echo $wprie_post_id; ?>;
	</script>
	<?php
}
