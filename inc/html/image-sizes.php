<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

$attachment_metadata = wp_get_attachment_metadata( $wprie_image_id );
$sizes = wprie_get_image_sizes ();
foreach ( $sizes as $size_key => $size_value ) {
	if ( $size_value['crop'] == 1 ) {
		$this_crop_exists = ! empty( $attachment_metadata['sizes'][$size_key]['file'] );
		?>
		<div>
			<h3><?php echo $size_key; ?></h3>
			<?php
			$image_attributes = wp_get_attachment_image_src( $wprie_image_id, $size_key );
			?>
			<?php if ( $this_crop_exists ) { ?>
				<img src="<?php echo $image_attributes[0] . '?' . mt_rand( 1000, 9999 ); ?>" style="max-width: 100%;" />
			<?php } else {
				$img_url_parts = parse_url( $image_attributes[0] );
				$img_path_parts = pathinfo( $img_url_parts['path'] );
				$expected_url = $img_path_parts['dirname'] . '/' . wprie_get_cropped_image_filename( $img_path_parts['filename'], $size_value['width'], $size_value['height'], $img_path_parts['extension'] );
			?>
				<div class="wprie-not-existing-crop">
					<img src="<?php echo $expected_url; ?>" style="max-width: 100%;" />
					<p><?php _e( 'Crop not generated yet, generate it manually with the link below', WPRIE_DOMAIN ); ?></p>
				</div>
			<?php } ?>
			<p>
				<ul>
					<li>expected width: <?php echo $size_value['width']; ?></li>
					<li>expected height: <?php echo $size_value['height']; ?></li>
					<?php if ( $this_crop_exists ) { ?>
						<li>actual width: <?php echo $attachment_metadata['sizes'][$size_key]['width']; ?></li>
						<li>actual height: <?php echo $attachment_metadata['sizes'][$size_key]['height']; ?></li>
					<?php } ?>
				</ul>
				<?php echo wprie_get_edit_image_anchor( $wprie_image_id, $size_key ); ?>
			</p>
		</div>
	<?php
	}
}

// echo '<pre>';
// print_r( wp_get_attachment_metadata( $wprie_image_id ) );
// echo '</pre>';
