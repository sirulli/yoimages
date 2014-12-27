<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

function yoimg_imgseo_explode_expression( $expression, $attachment, $parent ) {
	$result = $expression;
	$result = apply_filters( 'yoimg_seo_expressions', $result, $attachment, $parent );
	if ( empty( $result ) ) {
		return $parent->post_title;
	} else {
		return $result;
	}
}

function yoimg_seo_expression_title( $result, $attachment, $parent ) {
	if ( strpos( $result, YOIMG_TITLE_EXPRESSION ) !== FALSE ) {
		$result = str_replace( YOIMG_TITLE_EXPRESSION, $parent->post_title, $result );
	}
	return $result;
}
add_filter('yoimg_seo_expressions', 'yoimg_seo_expression_title', 10, 3);

function yoimg_seo_expression_post_type( $result, $attachment, $parent ) {
if ( strpos( $result, YOIMG_POST_TYPE_EXPRESSION ) !== FALSE ) {
		$result = str_replace( YOIMG_POST_TYPE_EXPRESSION, $parent->post_type, $result );
	}
	return $result;
}
add_filter('yoimg_seo_expressions', 'yoimg_seo_expression_post_type', 10, 3);

function yoimg_seo_expression_site_name( $result, $attachment, $parent ) {
	if ( strpos( $result, YOIMG_SITE_NAME_EXPRESSION ) !== FALSE ) {
		$result = str_replace( YOIMG_SITE_NAME_EXPRESSION, get_bloginfo( 'name' ), $result );
	}
	return $result;
}
add_filter('yoimg_seo_expressions', 'yoimg_seo_expression_site_name', 10, 3);

function yoimg_seo_expression_tags( $result, $attachment, $parent ) {
	if ( strpos( $result, YOIMG_TAGS_EXPRESSION ) !== FALSE ) {
		$tags_str = '';
		$posttags = get_the_tags( $parent->ID );
		if ( $posttags ) {
			foreach( $posttags as $tag ) {
				$tags_str = $tags_str . $tag->name . ' ';
			}
			$tags_str = trim( $tags_str );
		}
		$result = str_replace( YOIMG_TAGS_EXPRESSION, $tags_str, $result );
	}
	return $result;
}
add_filter('yoimg_seo_expressions', 'yoimg_seo_expression_tags', 10, 3);

function yoimg_seo_expression_categories( $result, $attachment, $parent ) {
	if ( strpos( $result, YOIMG_CATEGORIES_EXPRESSION ) !== FALSE ) {
		$cats_str = '';
		$cats = get_the_category( $parent->ID );
		if ( $cats ) {
			foreach( $cats as $cat ) {
				$cats_str = $cats_str . $cat->cat_name . ' ';
			}
			$cats_str = trim( $cats_str );
		}
		$result = str_replace( YOIMG_CATEGORIES_EXPRESSION, $cats_str, $result );
	}
	return $result;
}
add_filter('yoimg_seo_expressions', 'yoimg_seo_expression_categories', 10, 3);

function yoimg_imgseo_get_image_title( $attachment, $parent ) {
	$base_title = yoimg_imgseo_explode_expression( YOIMG_IMGSEO_IMAGE_TITLE_EXPRESSION, $attachment, $parent );
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

function yoimg_imgseo_get_image_alt( $attachment, $parent ) {
	$base_alt = yoimg_imgseo_explode_expression( YOIMG_IMGSEO_IMAGE_ALT_EXPRESSION, $attachment, $parent );
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

function yoimg_imgseo_get_image_filename( $attachment, $parent ) {
	$filename = yoimg_imgseo_explode_expression( YOIMG_IMGSEO_IMAGE_FILENAME_EXPRESSION, $attachment, $parent );
	return $filename;
}
