<?php

if (! defined ( 'ABSPATH' )) {
	die ( 'No script kiddies please!' );
}

function yoimg_imgseo_save_post( $post_id ) {
	$parent = get_post( $post_id );
	$ids = array();
	$ids = apply_filters( 'yoimg_seo_images_to_update', $ids, $post_id );
	$ids = array_unique( $ids ); 
	foreach( $ids as $id ) {
		if ( wp_attachment_is_image( $id ) ) {
			$attachment = get_post( $id );
			if ( YOIMG_IMGSEO_CHANGE_IMAGE_TITLE && get_post_meta( $id, 'yoimg_imgseo_title_updated', TRUE ) !== 'TRUE' ) {
				$attachment->post_title = yoimg_imgseo_get_image_title( $attachment, $parent );
				wp_update_post( $attachment );
				update_post_meta( $id, 'yoimg_imgseo_title_updated', 'TRUE' );
			}
			if ( YOIMG_IMGSEO_CHANGE_IMAGE_ALT && get_post_meta( $id, 'yoimg_imgseo_alt_updated', TRUE ) !== 'TRUE' ) {
				update_post_meta( $id, '_wp_attachment_image_alt', yoimg_imgseo_get_image_alt( $attachment, $parent ) );
				update_post_meta( $id, 'yoimg_imgseo_alt_updated', 'TRUE' );
			}
		}
	}
}
add_action( 'save_post', 'yoimg_imgseo_save_post' );

function yoimg_imgseo_add_attachments( $ids, $post_id ) {
	$attachments = get_posts(
			array(
					'post_type' => 'attachment',
					'posts_per_page' => -1,
					'post_parent' => $post_id
			)
	);
	foreach ( $attachments as $attachment ) {
		array_push( $ids, $attachment->ID );
	}
	return $ids;
}
add_filter('yoimg_seo_images_to_update', 'yoimg_imgseo_add_attachments', 10, 2);

function yoimg_imgseo_add_featured_image( $ids, $post_id ) {
	$post_thumbnail_id = get_post_thumbnail_id( $post_id );
	array_push( $ids, $post_thumbnail_id );
	return $ids;
}
add_filter('yoimg_seo_images_to_update', 'yoimg_imgseo_add_featured_image', 10, 2);
