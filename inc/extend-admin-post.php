<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

function wprie_admin_post_thumbnail_html( $content, $id ) {
	if ( ! has_post_thumbnail( $id ) ) {
		return $content;
	}
	$edit_crops_content = '<p>' . wprie_get_edit_image_anchor( get_post_thumbnail_id( $id ) ) . '</p>';
	return $content . $edit_crops_content;
}

add_filter( 'admin_post_thumbnail_html', 'wprie_admin_post_thumbnail_html', 10, 2 );
