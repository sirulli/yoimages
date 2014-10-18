<?php

/**
 * Plugin Name: WP Responsive Images Enhanced
 * Plugin URI: https://github.com/fagia/wp-responsive-images-enhanced
 * Description: TODO Adds support for showing and managing responsive images.
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
	
	define ( 'YOIMG_DOMAIN', 'yoimg' );
	
	/* Defaults */
	define ( 'YOIMG_DEFAULT_CROP_ENABLED', TRUE );
	define ( 'YOIMG_DEFAULT_CROP_QUALITIES', serialize( array( 100, 80, 60 ) ) );
	define ( 'YOIMG_DEFAULT_IMGSEO_CHANGE_IMAGE_TITLE', TRUE );
	define ( 'YOIMG_DEFAULT_IMGSEO_CHANGE_IMAGE_ALT', TRUE );
	define ( 'YOIMG_DEFAULT_IMGSEO_CHANGE_IMAGE_FILENAME', TRUE );
	define ( 'YOIMG_TITLE_EXPRESSION', __( '[title]', YOIMG_DOMAIN ) );
	define ( 'YOIMG_POST_TYPE_EXPRESSION', __( '[type]', YOIMG_DOMAIN ) );
	define ( 'YOIMG_SITE_NAME_EXPRESSION', __( '[site_name]', YOIMG_DOMAIN ) );
	define ( 'YOIMG_TAGS_EXPRESSION', __( '[tags]', YOIMG_DOMAIN ) );
	define ( 'YOIMG_CATEGORIES_EXPRESSION', __( '[categories]', YOIMG_DOMAIN ) );
	define ( 'YOIMG_DEFAULT_IMGSEO_IMAGE_TITLE_EXPRESSION', YOIMG_TITLE_EXPRESSION );
	define ( 'YOIMG_DEFAULT_IMGSEO_IMAGE_ALT_EXPRESSION', YOIMG_TITLE_EXPRESSION );
	define ( 'YOIMG_DEFAULT_IMGSEO_IMAGE_FILENAME_EXPRESSION', YOIMG_TITLE_EXPRESSION );
	
	$yoimg_settings = get_option( 'yoimg_settings' );
	
	define ( 'YOIMG_CROP_ENABLED', $yoimg_settings && isset( $yoimg_settings['cropping_is_active'] ) ? $yoimg_settings['cropping_is_active'] : YOIMG_DEFAULT_CROP_ENABLED );
	
	define ( 'YOIMG_IMGSEO_CHANGE_IMAGE_TITLE', $yoimg_settings && isset( $yoimg_settings['imgseo_change_image_title'] ) ? $yoimg_settings['imgseo_change_image_title'] : YOIMG_DEFAULT_IMGSEO_CHANGE_IMAGE_TITLE );
	define ( 'YOIMG_IMGSEO_CHANGE_IMAGE_ALT', $yoimg_settings && isset( $yoimg_settings['imgseo_change_image_alt'] ) ? $yoimg_settings['imgseo_change_image_alt'] : YOIMG_DEFAULT_IMGSEO_CHANGE_IMAGE_ALT );
	define ( 'YOIMG_IMGSEO_CHANGE_IMAGE_FILENAME', $yoimg_settings && isset( $yoimg_settings['imgseo_change_image_filename'] ) ? $yoimg_settings['imgseo_change_image_filename'] : YOIMG_DEFAULT_IMGSEO_CHANGE_IMAGE_FILENAME );
	define ( 'YOIMG_IMGSEO_ENABLED', YOIMG_IMGSEO_CHANGE_IMAGE_TITLE || YOIMG_IMGSEO_CHANGE_IMAGE_ALT || YOIMG_IMGSEO_CHANGE_IMAGE_FILENAME );
	
	define ( 'YOIMG_IMGSEO_IMAGE_TITLE_EXPRESSION', $yoimg_settings && isset( $yoimg_settings['imgseo_image_title_expression'] ) ? $yoimg_settings['imgseo_image_title_expression'] : YOIMG_DEFAULT_IMGSEO_IMAGE_TITLE_EXPRESSION );
	define ( 'YOIMG_IMGSEO_IMAGE_ALT_EXPRESSION', $yoimg_settings && isset( $yoimg_settings['imgseo_image_alt_expression'] ) ? $yoimg_settings['imgseo_image_alt_expression'] : YOIMG_DEFAULT_IMGSEO_IMAGE_ALT_EXPRESSION );
	define ( 'YOIMG_IMGSEO_IMAGE_FILENAME_EXPRESSION', $yoimg_settings && isset( $yoimg_settings['imgseo_image_filename_expression'] ) ? $yoimg_settings['imgseo_image_filename_expression'] : YOIMG_DEFAULT_IMGSEO_IMAGE_FILENAME_EXPRESSION );
	
	define ( 'YOIMG_PATH', dirname ( __FILE__ ) . '/' );
	define ( 'YOIMG_URL', plugins_url ( basename ( dirname ( __FILE__ ) ) ) . '/' );
	define ( 'YOIMG_EDIT_IMAGE_ACTION', 'yoimg-edit-thumbnails' );

	require_once (YOIMG_PATH . 'inc/utils.php');
	require_once (YOIMG_PATH . 'inc/settings.php');
	
	if ( YOIMG_CROP_ENABLED ) {
		require_once (YOIMG_PATH . 'inc/img-cropping/image-editor.php');
		require_once (YOIMG_PATH . 'inc/img-cropping/extend-admin-media.php');
		require_once (YOIMG_PATH . 'inc/img-cropping/extend-admin-media-lightbox.php');
		require_once (YOIMG_PATH . 'inc/img-cropping/extend-admin-post.php');
		require_once (YOIMG_PATH . 'inc/img-cropping/extend-admin-options-media.php');
	}

	if ( YOIMG_IMGSEO_ENABLED ) {
		require_once (YOIMG_PATH . 'inc/img-seo/commons.php');
		require_once (YOIMG_PATH . 'inc/img-seo/extend-attachment-uploading.php');
		require_once (YOIMG_PATH . 'inc/img-seo/extend-post-saving.php');
	}
	
}

function yoimg_admin_load_styles_and_scripts( $hook ) {
	if ( YOIMG_CROP_ENABLED ) {
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
		wp_enqueue_style( 'yoimg-admin-css', YOIMG_URL . 'css/yoimg-admin.css' );
		wp_enqueue_style( 'yoimg-cropper-css', YOIMG_URL . 'js/cropper/cropper.min.css' );
		wp_enqueue_script( 'yoimg-cropper-js', YOIMG_URL . 'js/cropper/cropper.min.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'yoimg-admin-js', YOIMG_URL . 'js/yoimg-admin.js', array( 'yoimg-cropper-js' ), false, true );
	}
	if ( isset( $_GET['page'] ) && $_GET['page'] === 'yoimg-settings' ) {
		wp_enqueue_script( 'yoimg-settings-js', YOIMG_URL . 'js/yoimg-settings.js', array( 'jquery' ), false, true );
	}
}
add_action( 'admin_enqueue_scripts', 'yoimg_admin_load_styles_and_scripts' );
