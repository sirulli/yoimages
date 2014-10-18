<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

function wprie_media_row_actions( $actions, $post, $detached ) {
	if ( wp_attachment_is_image( $post->ID ) && current_user_can( 'edit_post', $post->ID ) ) {
		$actions['wprie_crop'] = wprie_get_edit_image_anchor( $post->ID );
	}
	return $actions;
}
add_filter( 'media_row_actions', 'wprie_media_row_actions', 10, 3);
