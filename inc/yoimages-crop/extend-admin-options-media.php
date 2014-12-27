<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

function yoimg_get_custom_sizes_table_rows() {
	if ( current_user_can( 'manage_options' ) ) {
		include (YOIMG_CROP_PATH . '/html/custom-sizes-table-rows.php');
	}
	die();
}
add_action( 'wp_ajax_yoimg_get_custom_sizes_table_rows', 'yoimg_get_custom_sizes_table_rows' );

function yoimg_extend_admin_options_media() {
	wp_enqueue_style( 'wp-pointer' );
	wp_enqueue_script( 'wp-pointer' );
	//TODO enqueue here custom yoimg script too
}
add_action( 'load-options-media.php', 'yoimg_extend_admin_options_media' );
