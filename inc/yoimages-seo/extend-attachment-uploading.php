<?php

if (! defined ( 'ABSPATH' )) {
	die ( 'No script kiddies please!' );
}

function yoimg_attachment_added_to_post_or_page( $attachment_id ) {
	if ( wp_attachment_is_image( $attachment_id ) ) {
		global $wpdb;
		$attachment = get_post( $attachment_id );
		if ( $attachment ) {
			$post_parent_id = $attachment->post_parent;
			if ( $post_parent_id > 0 ) {
				$post_parent = get_post( $post_parent_id );
				if ( $post_parent && ! empty ( $post_parent->post_title ) ) {
					
					$attachment_path = get_attached_file( $attachment_id );
					$attachment_path_info = pathinfo( $attachment_path );
					
					if ( YOIMG_IMGSEO_CHANGE_IMAGE_TITLE ) {
						$attachment->post_title = yoimg_imgseo_get_image_title( $attachment, $post_parent );
						update_post_meta( $attachment_id, 'yoimg_imgseo_title_updated', 'TRUE' );
					}
					
					if ( YOIMG_IMGSEO_CHANGE_IMAGE_ALT ) {
						update_post_meta( $attachment_id, '_wp_attachment_image_alt', yoimg_imgseo_get_image_alt( $attachment, $post_parent ) );
						update_post_meta( $attachment_id, 'yoimg_imgseo_alt_updated', 'TRUE' );
					}
					
					if ( YOIMG_IMGSEO_CHANGE_IMAGE_FILENAME ) {
						$post_parent_slug = sanitize_title( yoimg_imgseo_get_image_filename( $attachment, $post_parent ) );
						$attachment->post_name = wp_unique_post_slug( $post_parent_slug, $attachment_id, $attachment->post_status, $attachment->post_type, $post_parent_id );
						$attachment_new_path = $attachment_path_info['dirname'] . '/' . $attachment->post_name . '.' . $attachment_path_info['extension'];
						$count = 0;
						$base_post_name = $attachment->post_name;
						while ( file_exists( $attachment_new_path ) ) {
							$count = $count + 1;
							$attachment_new_path = $attachment_path_info['dirname'] . '/' . $base_post_name . $count . '.' . $attachment_path_info['extension'];
							$attachment->post_name = $base_post_name . $count;
						}
						rename( $attachment_path, $attachment_new_path );
						update_attached_file( $attachment_id, $attachment_new_path );
						update_post_meta( $attachment_id, 'yoimg_tmp_attachment_metadata', wp_generate_attachment_metadata( $attachment_id, $attachment_new_path ) );
					}

					wp_update_post( $attachment );
				}
			}
		}
	}
}
function yoimg_attachment_added( $attachment_id ) {
	yoimg_attachment_added_to_post_or_page( $attachment_id );
}
add_action('add_attachment', 'yoimg_attachment_added');

function yoimg_update_attachment_metadata( $attachment_metadata, $attachment_id ) {
	$yoimg_tmp_attachment_metadata = get_post_meta( $attachment_id, 'yoimg_tmp_attachment_metadata' );
	if ( YOIMG_IMGSEO_CHANGE_IMAGE_FILENAME && wp_attachment_is_image( $attachment_id ) && ! empty( $yoimg_tmp_attachment_metadata ) ) {
		global $wpdb;
		$wp_attachment_image_src = wp_get_attachment_image_src( $attachment_id );
		$wpdb->update( $wpdb->posts, array( 'guid' => $wp_attachment_image_src[0] ), array( 'ID' => $attachment_id ) );
		$attachment_metadata = $yoimg_tmp_attachment_metadata[0];
		delete_post_meta( $attachment_id, 'yoimg_tmp_attachment_metadata' );
	}
	return $attachment_metadata;
}
add_filter( 'wp_update_attachment_metadata', 'yoimg_update_attachment_metadata', 10, 2 );
