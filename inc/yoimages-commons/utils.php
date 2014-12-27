<?php
if (! defined ( 'ABSPATH' )) {
	die ( 'No script kiddies please!' );
}
function yoimg_log($message) {
	if (WP_DEBUG === true) {
		if (is_array ( $message ) || is_object ( $message )) {
			error_log ( print_r ( $message, true ) );
		} else {
			error_log ( $message );
		}
	}
}