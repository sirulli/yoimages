<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

$attachment_metadata = wp_get_attachment_metadata( $wprie_image_id );
$cropped_image_sizes = wprie_get_image_sizes( $wprie_image_size );
$full_image_attributes = wp_get_attachment_image_src( $wprie_image_id, 'full' );
?>	
<script>
	var wprie_image_id = <?php echo $wprie_image_id; ?>;
	var wprie_image_size = '<?php echo $wprie_image_size; ?>';
	var wprie_cropper_min_width = <?php echo $cropped_image_sizes['width']; ?>;
	var wprie_cropper_min_height = <?php echo $cropped_image_sizes['height']; ?>;
	var wprie_cropper_aspect_ratio = <?php echo $cropped_image_sizes['width']; ?> / <?php echo $cropped_image_sizes['height']; ?>;
	<?php
	if ( ! empty( $attachment_metadata['wprie_attachment_metadata']['crop'][$wprie_image_size] ) ) {
	?>
		var wprie_prev_crop_x = <?php echo $attachment_metadata['wprie_attachment_metadata']['crop'][$wprie_image_size]['x']; ?>;
		var wprie_prev_crop_y = <?php echo $attachment_metadata['wprie_attachment_metadata']['crop'][$wprie_image_size]['y']; ?>;
		var wprie_prev_crop_width = <?php echo $attachment_metadata['wprie_attachment_metadata']['crop'][$wprie_image_size]['width']; ?>;
		var wprie_prev_crop_height = <?php echo $attachment_metadata['wprie_attachment_metadata']['crop'][$wprie_image_size]['height']; ?>;
	<?php
	}
	?>
</script>
<?php if ( $_GET['partial'] != '1' ) { ?>
<div id="wprie-cropper-wrapper">
	<div class="media-modal wp-core-ui">
		<a title="<?php _e( 'Close', WPRIE_DOMAIN ); ?>" href="javascript:wprieCancelCropImage();" class="media-modal-close">
			<span class="media-modal-icon"></span>
		</a>
		<div class="media-modal-content">
<?php } ?>
	    	<div class="media-frame wp-core-ui">	
				<div class="media-frame-title"><h1><?php _e( 'Edit cropped formats from full image', WPRIE_DOMAIN ); ?> (<?php echo $full_image_attributes[1]; ?>x<?php echo $full_image_attributes[2]; ?>)</h1></div>
				<div class="media-frame-router">
					<div class="media-router">
						<?php
						$sizes = wprie_get_image_sizes ();
						foreach ( $sizes as $size_key => $size_value ) {
							if ( $size_value['crop'] == 1 ) {
								$is_current_size = $size_key === $wprie_image_size;
								$anchor_class = $is_current_size ? 'active' : '';
								$anchor_href = wprie_get_edit_image_url( $wprie_image_id, $size_key ) . '&partial=1';
						?>
								<a href="<?php echo $anchor_href; ?>" class="media-menu-item wprie-thickbox wprie-thickbox-partial <?php echo $anchor_class; ?>"><?php echo $size_key; ?></a>
						<?php
							}
						}
						?>
					</div>
				</div>
				<div class="media-frame-content">
					<div class="attachments-browser">
						<div class="attachments">
							<div style="max-width: <?php echo $full_image_attributes[1]; ?>px;max-height: <?php echo $full_image_attributes[2]; ?>px;">
								<img id="wprie-cropper" src="<?php echo $full_image_attributes[0] . '?' . mt_rand( 1000, 9999 ); ?>" style="max-width: 100%;" />
							</div>
						</div>
						<div class="media-sidebar">
							<div class="attachment-details">
								<?php
								$this_crop_exists = ! empty( $attachment_metadata['sizes'][$wprie_image_size]['file'] );
								if ( $this_crop_exists ) {
								?>
									<h3><?php _e( 'Current', WPRIE_DOMAIN ); ?> <?php echo $wprie_image_size; ?> (<?php echo $attachment_metadata['sizes'][$wprie_image_size]['width']; ?>x<?php echo $attachment_metadata['sizes'][$wprie_image_size]['height']; ?>)</h3>
								<?php
								} else {
								?>
									<h3><?php _e( 'Current', WPRIE_DOMAIN ); ?> <?php echo $wprie_image_size; ?></h3>
								<?php	
								}
								$image_attributes = wp_get_attachment_image_src( $wprie_image_id, $wprie_image_size );
								if ( $this_crop_exists ) {
								?>
									<img src="<?php echo $image_attributes[0] . '?' . mt_rand( 1000, 9999 ); ?>" style="max-width: 100%;" />
								<?php
								} else {
									$img_url_parts = parse_url( $image_attributes[0] );
									$img_path_parts = pathinfo( $img_url_parts['path'] );
									$expected_url = $img_path_parts['dirname'] . '/' . wprie_get_cropped_image_filename( $img_path_parts['filename'], $cropped_image_sizes['width'], $cropped_image_sizes['height'], $img_path_parts['extension'] );
									?>
									<div class="wprie-not-existing-crop">
										<img src="<?php echo $expected_url; ?>" style="max-width: 100%;" />
										<p><?php _e( 'Crop not generated yet', WPRIE_DOMAIN ); ?></p>
									</div>
								<?php } ?>
								<h3 id="wprie-cropper-preview-title"><?php _e( 'Crop preview', WPRIE_DOMAIN ); ?></h3>
								<div id="wprie-cropper-preview"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="media-frame-toolbar">
	        		<div class="media-toolbar">
						<div class="media-toolbar-primary">
							<div class="wprie-cropper-quality-wrapper">
								<label for="wprie-cropper-quality"><?php _e( 'Crop quality', WPRIE_DOMAIN ); ?>:</label>
								<select name="quality" id="wprie-cropper-quality">
									<option value="100">100%</option>
									<option value="50">50%</option>
									<option value="10">10%</option>
								</select>
							</div>
							<a href="javascript:wprieCropImage();" class="button media-button button-primary button-large media-button-select"><?php _e( 'Crop', WPRIE_DOMAIN ); ?> <?php echo $wprie_image_size; ?></a>
						</div>
					</div>
				</div>	
			</div>
<?php if ( $_GET['partial'] != '1' ) { ?>
		</div>
	</div>
	<div id="wprie-cropper-bckgr" class="media-modal-backdrop"></div>
</div>
<?php } ?>
<script>wprieInitCropImage();</script>
<?php
exit();
