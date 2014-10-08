<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

function wprie_alt_get_image_seo_title( $attachment, $parent ) {
	$base_title = $parent->post_title;
	$title = $base_title;
	$count = 1;
	// TODO set value compared to other existing post parent attachments
	return $title;
}

function wprie_alt_get_image_seo_alt( $attachment, $parent ) {
	$base_alt = $parent->post_title;
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