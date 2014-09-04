<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

function wprie_get_custom_sizes_table_rows() {
	include (WPRIE_PATH . 'inc/html/custom-sizes-table-rows.php');
	die();
}
add_action( 'wp_ajax_wprie_get_custom_sizes_table_rows', 'wprie_get_custom_sizes_table_rows' );
