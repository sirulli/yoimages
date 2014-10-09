<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

function wprie_alt_explode_expression( $expression, $attachment, $parent ) {
	$result = $expression;
	if ( strpos( $result, '[title]' ) !== FALSE ) {
		$result = str_replace( '[title]', $parent->post_title, $result );
	}
	if ( empty( $result ) ) {
		return $parent->post_title;
	} else {
		return $result;
	}
}

function wprie_alt_get_image_seo_title( $attachment, $parent ) {
	$base_title = wprie_alt_explode_expression( WPRIE_ALT_IMAGE_TITLE_EXPRESSION, $attachment, $parent );
	$title = $base_title;
	$count = 1;
	$other = get_page_by_title( $title, 'OBJECT', 'attachment' );
	while ( $other ) {
		$title = $base_title . ' ' . $count;
		$other = get_page_by_title( $title, 'OBJECT', 'attachment' );
		$count++;
	}
	return $title;
}

function wprie_alt_get_image_seo_alt( $attachment, $parent ) {
	$base_alt = wprie_alt_explode_expression( WPRIE_ALT_IMAGE_ALT_EXPRESSION, $attachment, $parent );
	$alt = $base_alt;
	$count = 1;
	$args = array(
		'post_type' => 'attachment',
		'post_status' => 'any',
		'posts_per_page' => 1,
		'meta_query' => array(
			array(
				'key' => '_wp_attachment_image_alt',
				'value' => $alt
			)
		)
	);
	$query = new WP_Query( $args );
	while ( $query->post_count > 0 ) {
		$alt = $base_alt . ' ' . $count;
		$args['meta_query'][0]['value'] = $alt;
		$query = new WP_Query( $args );
		$count++;
	}	
	return $alt;
}

function wprie_alt_get_image_seo_filename( $attachment, $parent ) {
	$filename = wprie_alt_explode_expression( WPRIE_ALT_IMAGE_FILENAME_EXPRESSION, $attachment, $parent );
	return $filename;
}
