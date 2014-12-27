<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

function yoimg_media_row_actions( $actions, $post, $detached ) {
	if ( wp_attachment_is_image( $post->ID ) && current_user_can( 'edit_post', $post->ID ) ) {
		$actions['yoimg_crop'] = yoimg_get_edit_image_anchor( $post->ID );
	}
	return $actions;
}
add_filter( 'media_row_actions', 'yoimg_media_row_actions', 10, 3);
