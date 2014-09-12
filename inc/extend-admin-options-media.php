<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

function wprie_get_custom_sizes_table_rows() {
	if ( current_user_can( 'manage_options' ) ) {
		include (WPRIE_PATH . 'inc/html/custom-sizes-table-rows.php');
	}
	die();
}
add_action( 'wp_ajax_wprie_get_custom_sizes_table_rows', 'wprie_get_custom_sizes_table_rows' );

function wprie_extend_admin_options_media() {
	wp_enqueue_style( 'wp-pointer' );
	wp_enqueue_script( 'wp-pointer' );
	//TODO enqueue here custom wprie script too
}
add_action( 'load-options-media.php', 'wprie_extend_admin_options_media' );
