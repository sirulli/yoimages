<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

function wprie_alt_get_image_seo_title( $attachment, $parent ) {
	// TODO set value compared to other existing post parent attachments
	$title = $parent->post_title;
	return $title;
}

function wprie_alt_get_image_seo_alt( $attachment, $parent ) {
	// TODO set value compared to other existing post parent attachments
	$alt = $parent->post_title;
	return $alt;
}