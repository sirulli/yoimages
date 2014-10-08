<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

function wprie_alt_get_image_seo_title( $proposed_title ) {
	// TODO set value compared to other existing post parent attachments
	return $proposed_title;
}

function wprie_alt_get_image_seo_alt( $proposed_alt ) {
	// TODO set value compared to other existing post parent attachments
	return $proposed_alt;
}