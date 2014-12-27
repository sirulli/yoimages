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
function yoimg_register_module($module_path, $has_settings = false) {
	global $yoimg_modules;
	if (! isset ( $yoimg_modules )) {
		$yoimg_modules = array ();
	}
	$module_id = basename ( $module_path );
	$yoimg_modules [$module_id] = array (
			'has-settings' => $has_settings 
	);
}