<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

function yoimg_get_cropped_image_filename( $filename, $width, $height, $extension ) {
	return $filename . '-' . $width . 'x' . $height . '.' . $extension;
}

function yoimg_get_edit_image_url( $id, $size ) {
	return admin_url( 'admin-ajax.php' ) . '?action=yoimg_edit_thumbnails_page&post=' . $id . '&size=' . $size;
}

function yoimg_get_edit_image_anchor( $id, $size = 'thumbnail', $styles = '', $classes = '' ) {
	add_thickbox();
	$edit_crops_url = yoimg_get_edit_image_url( $id, $size );
	return '<a class="yoimg-thickbox yoimg ' . $classes . '" style="' . $styles . '" href="' . $edit_crops_url . '" title="' . __( 'Edit crop formats', YOIMG_DOMAIN ) . '">' . __( 'Edit crop formats', YOIMG_DOMAIN ) . '</a>';
}

function yoimg_get_edit_image_anchor_ajax() {
	$classes = empty( $_POST['classes'] ) ? 'edit-attachment' : esc_html( $_POST['classes'] );
	echo yoimg_get_edit_image_anchor( esc_html( $_POST['post'] ), 'thumbnail', 'margin-right:10px;', $classes );
	die();
}

add_action( 'wp_ajax_yoimg_get_edit_image_anchor', 'yoimg_get_edit_image_anchor_ajax' );

function yoimg_get_image_sizes( $size = '' ) {
	global $_wp_additional_image_sizes;
	$sizes = array ();
	$get_intermediate_image_sizes = get_intermediate_image_sizes ();
	foreach ( $get_intermediate_image_sizes as $_size ) {
		if (in_array ( $_size, array (
				'thumbnail',
				'medium',
				'large' 
		) )) {
			$sizes [$_size] ['width'] = get_option ( $_size . '_size_w' );
			$sizes [$_size] ['height'] = get_option ( $_size . '_size_h' );
			$sizes [$_size] ['crop'] = ( bool ) get_option ( $_size . '_crop' );
		} elseif (isset ( $_wp_additional_image_sizes [$_size] )) {
			$sizes [$_size] = array (
					'width' => $_wp_additional_image_sizes [$_size] ['width'],
					'height' => $_wp_additional_image_sizes [$_size] ['height'],
					'crop' => $_wp_additional_image_sizes [$_size] ['crop'] 
			);
		}
	}
	if ($size) {
		if (isset ( $sizes [$size] )) {
			return $sizes [$size];
		} else {
			return false;
		}
	}
	return $sizes;
}
