<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

function wprie_admin_post_thumbnail_html( $content, $id ) {
	if ( ! has_post_thumbnail( $id ) ) {
		return $content;
	}
	add_thickbox();
	$edit_crops_url = wprie_get_edit_image_url( get_post_thumbnail_id( $id ), 'thumbnail' );
	$edit_crops_content = '<p><a class="thickbox" href="' . $edit_crops_url . '" title="' . __( 'Edit cropped formats', 'wprie' ) . '">' . __( 'Edit cropped formats', 'wprie' ) . '</a></p>';
	return $content . $edit_crops_content;
}

add_filter( 'admin_post_thumbnail_html', 'wprie_admin_post_thumbnail_html', 10, 2 );
