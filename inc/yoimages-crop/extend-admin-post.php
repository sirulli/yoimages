<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

function yoimg_admin_post_thumbnail_html( $content, $id ) {
	if ( ! has_post_thumbnail( $id ) ) {
		return $content;
	}
	$image_id = get_post_thumbnail_id( $id );
	if ( ! current_user_can( 'edit_post', $image_id ) ) {
		return $content;
	}
	$edit_crops_content = '<p>' . yoimg_get_edit_image_anchor( $image_id ) . '</p>';
	return $content . $edit_crops_content;
}

add_filter( 'admin_post_thumbnail_html', 'yoimg_admin_post_thumbnail_html', 10, 2 );
