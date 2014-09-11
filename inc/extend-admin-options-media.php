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
