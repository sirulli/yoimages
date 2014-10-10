<?php

/**
 * Plugin Name: WP Responsive Images Enhanced
 * Plugin URI: https://github.com/fagia/wp-responsive-images-enhanced
 * Description: Adds support for showing and managing responsive images.
 * Version: 0.0.1
 * Author: Matteo Cajani
 * Author URI: http://fagia.martjanplanet.com
 * License: GPL2
**/

/**
 * Copyright 2014 Matteo Cajani (email : matteo.cajani@gmail.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
**/

if (! defined ( 'ABSPATH' )) {
	die ( 'No script kiddies please!' );
}

if ( is_admin() ) {
	
	/* Defaults */
	define ( 'WPRIE_DEFAULT_CROP_ENABLED', TRUE );
	define ( 'WPRIE_DEFAULT_CROP_QUALITIES', serialize( array( 100, 80, 60 ) ) );
	define ( 'WPRIE_DEFAULT_ALT_CHANGE_IMAGE_TITLE', TRUE );
	define ( 'WPRIE_DEFAULT_ALT_CHANGE_IMAGE_ALT', TRUE );
	define ( 'WPRIE_DEFAULT_ALT_CHANGE_IMAGE_FILENAME', TRUE );
	define ( 'WPRIE_TITLE_EXPRESSION', __( '[title]', WPRIE_DOMAIN ) );
	define ( 'WPRIE_POST_TYPE_EXPRESSION', __( '[type]', WPRIE_DOMAIN ) );
	define ( 'WPRIE_SITE_NAME_EXPRESSION', __( '[site_name]', WPRIE_DOMAIN ) );
	define ( 'WPRIE_TAGS_EXPRESSION', __( '[tags]', WPRIE_DOMAIN ) );
	define ( 'WPRIE_CATEGORIES_EXPRESSION', __( '[categories]', WPRIE_DOMAIN ) );
	define ( 'WPRIE_DEFAULT_ALT_IMAGE_TITLE_EXPRESSION', WPRIE_TITLE_EXPRESSION );
	define ( 'WPRIE_DEFAULT_ALT_IMAGE_ALT_EXPRESSION', WPRIE_TITLE_EXPRESSION );
	define ( 'WPRIE_DEFAULT_ALT_IMAGE_FILENAME_EXPRESSION', WPRIE_TITLE_EXPRESSION );
	
	$wprie_settings = get_option( 'wprie_settings' );
	
	define ( 'WPRIE_CROP_ENABLED', $wprie_settings && isset( $wprie_settings['cropping_is_active'] ) ? $wprie_settings['cropping_is_active'] : WPRIE_DEFAULT_CROP_ENABLED );
	
	define ( 'WPRIE_ALT_CHANGE_IMAGE_TITLE', $wprie_settings && isset( $wprie_settings['alt_change_image_title'] ) ? $wprie_settings['alt_change_image_title'] : WPRIE_DEFAULT_ALT_CHANGE_IMAGE_TITLE );
	define ( 'WPRIE_ALT_CHANGE_IMAGE_ALT', $wprie_settings && isset( $wprie_settings['alt_change_image_alt'] ) ? $wprie_settings['alt_change_image_alt'] : WPRIE_DEFAULT_ALT_CHANGE_IMAGE_ALT );
	define ( 'WPRIE_ALT_CHANGE_IMAGE_FILENAME', $wprie_settings && isset( $wprie_settings['alt_change_image_filename'] ) ? $wprie_settings['alt_change_image_filename'] : WPRIE_DEFAULT_ALT_CHANGE_IMAGE_FILENAME );
	define ( 'WPRIE_ALT_ENABLED', WPRIE_ALT_CHANGE_IMAGE_TITLE || WPRIE_ALT_CHANGE_IMAGE_ALT || WPRIE_ALT_CHANGE_IMAGE_FILENAME );
	
	define ( 'WPRIE_ALT_IMAGE_TITLE_EXPRESSION', $wprie_settings && isset( $wprie_settings['alt_image_title_expression'] ) ? $wprie_settings['alt_image_title_expression'] : WPRIE_DEFAULT_ALT_IMAGE_TITLE_EXPRESSION );
	define ( 'WPRIE_ALT_IMAGE_ALT_EXPRESSION', $wprie_settings && isset( $wprie_settings['alt_image_alt_expression'] ) ? $wprie_settings['alt_image_alt_expression'] : WPRIE_DEFAULT_ALT_IMAGE_ALT_EXPRESSION );
	define ( 'WPRIE_ALT_IMAGE_FILENAME_EXPRESSION', $wprie_settings && isset( $wprie_settings['alt_image_filename_expression'] ) ? $wprie_settings['alt_image_filename_expression'] : WPRIE_DEFAULT_ALT_IMAGE_FILENAME_EXPRESSION );
	
	define ( 'WPRIE_PATH', dirname ( __FILE__ ) . '/' );
	define ( 'WPRIE_URL', plugins_url ( basename ( dirname ( __FILE__ ) ) ) . '/' );
	define ( 'WPRIE_EDIT_IMAGE_ACTION', 'wprie-edit-thumbnails' );
	define ( 'WPRIE_DOMAIN', 'wprie' );

	require_once (WPRIE_PATH . 'inc/utils.php');
	require_once (WPRIE_PATH . 'inc/settings.php');
	
	if ( WPRIE_CROP_ENABLED ) {
		require_once (WPRIE_PATH . 'inc/image-editor.php');
		require_once (WPRIE_PATH . 'inc/extend-admin-media.php');
		require_once (WPRIE_PATH . 'inc/extend-admin-media-lightbox.php');
		require_once (WPRIE_PATH . 'inc/extend-admin-post.php');
		require_once (WPRIE_PATH . 'inc/extend-admin-options-media.php');
	}

	if ( WPRIE_ALT_ENABLED ) {
		require_once (WPRIE_PATH . 'inc/alt/commons.php');
		require_once (WPRIE_PATH . 'inc/alt/extend-attachment-uploading.php');
		require_once (WPRIE_PATH . 'inc/alt/extend-post-saving.php');
	}
	
}

function wprie_admin_load_styles_and_scripts( $hook ) {
	if ( WPRIE_CROP_ENABLED ) {
		if ( $hook == 'post.php' ) {
			wp_enqueue_media();
		} else if ( $hook == 'upload.php' ) {
			// issue http://stackoverflow.com/questions/25884434/wordpress-wp-enqueue-media-causes-javascript-error-from-wp-admin-upload-phpmo
			$mode = get_user_option( 'media_library_mode', get_current_user_id() ) ? get_user_option( 'media_library_mode', get_current_user_id() ) : 'grid';
			$modes = array( 'grid', 'list' );
			if ( isset( $_GET['mode'] ) && in_array( $_GET['mode'], $modes ) ) {
				$mode = $_GET['mode'];
				update_user_option( get_current_user_id(), 'media_library_mode', $mode );
			}
			if ( 'list' === $mode ) {
				wp_dequeue_script( 'media' );
				wp_enqueue_media();
			}
		} else {
			wp_enqueue_style( 'media-views' );
		}
		wp_enqueue_style( 'wprie-admin-css', WPRIE_URL . 'css/wprie-admin.css' );
		wp_enqueue_style( 'wprie-cropper-css', WPRIE_URL . 'js/cropper/cropper.min.css' );
		wp_enqueue_script( 'wprie-cropper-js', WPRIE_URL . 'js/cropper/cropper.min.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'wprie-admin-js', WPRIE_URL . 'js/wprie-admin.js', array( 'wprie-cropper-js' ), false, true );
	}
	if ( isset( $_GET['page'] ) && $_GET['page'] === 'wprie-settings' ) {
		wp_enqueue_script( 'wprie-settings-js', WPRIE_URL . 'js/wprie-settings.js', array( 'jquery' ), false, true );
	}
}
add_action( 'admin_enqueue_scripts', 'wprie_admin_load_styles_and_scripts' );
