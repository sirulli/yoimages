<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

$attachment_metadata = wp_get_attachment_metadata( $yoimg_image_id );
$cropped_image_sizes = yoimg_get_image_sizes( $yoimg_image_size );
$replacement = $attachment_metadata['yoimg_attachment_metadata']['crop'][$yoimg_image_size]['replacement'];
$has_replacement = ! empty ( $replacement ) && get_post( $replacement );
if ( $has_replacement ) {
	$full_image_attributes = wp_get_attachment_image_src( $replacement, 'full' );
} else {
	$full_image_attributes = wp_get_attachment_image_src( $yoimg_image_id, 'full' );
}
?>	
<script>
	var yoimg_image_id = <?php echo $yoimg_image_id; ?>;
	var yoimg_image_size = '<?php echo $yoimg_image_size; ?>';
	var yoimg_cropper_min_width = <?php echo $cropped_image_sizes['width']; ?>;
	var yoimg_cropper_min_height = <?php echo $cropped_image_sizes['height']; ?>;
	var yoimg_cropper_aspect_ratio = <?php echo $cropped_image_sizes['width']; ?> / <?php echo $cropped_image_sizes['height']; ?>;
	<?php
	$crop_x = $attachment_metadata['yoimg_attachment_metadata']['crop'][$yoimg_image_size]['x'];
	if ( is_numeric( $crop_x ) && $crop_x >= 0 ) {
	?>
		var yoimg_prev_crop_x = <?php echo $crop_x; ?>;
		var yoimg_prev_crop_y = <?php echo $attachment_metadata['yoimg_attachment_metadata']['crop'][$yoimg_image_size]['y']; ?>;
		var yoimg_prev_crop_width = <?php echo $attachment_metadata['yoimg_attachment_metadata']['crop'][$yoimg_image_size]['width']; ?>;
		var yoimg_prev_crop_height = <?php echo $attachment_metadata['yoimg_attachment_metadata']['crop'][$yoimg_image_size]['height']; ?>;
	<?php
	}
	?>
</script>
<?php if ( ( ! isset( $_GET['partial'] ) ) || $_GET['partial'] != '1' ) { ?>
<div id="yoimg-cropper-wrapper">
	<div class="media-modal wp-core-ui">
		<a title="<?php _e( 'Close', YOIMG_DOMAIN ); ?>" href="javascript:yoimgCancelCropImage();" class="media-modal-close">
			<span class="media-modal-icon"></span>
		</a>
		<div class="media-modal-content">
<?php } ?>
	    	<div class="media-frame wp-core-ui">	
				<div class="media-frame-title"><h1><?php _e( 'Edit cropped formats from full image', YOIMG_DOMAIN ); ?> (<?php echo $full_image_attributes[1]; ?>x<?php echo $full_image_attributes[2]; ?>)</h1></div>
				<div class="media-frame-router">
					<div class="media-router">
						<?php
						$sizes = yoimg_get_image_sizes ();
						foreach ( $sizes as $size_key => $size_value ) {
							if ( $size_value['crop'] == 1 ) {
								$is_current_size = $size_key === $yoimg_image_size;
								if ( $is_current_size ) {
									$is_full_image_too_small = $full_image_attributes[1] < $size_value['width'] && $full_image_attributes[2] < $size_value['height'];
									$curr_size_width = $size_value['width'];
									$curr_size_height = $size_value['height'];
								}
								$anchor_class = $is_current_size ? 'active' : '';
								$anchor_href = yoimg_get_edit_image_url( $yoimg_image_id, $size_key ) . '&partial=1';
						?>
								<a href="<?php echo $anchor_href; ?>" class="media-menu-item yoimg-thickbox yoimg-thickbox-partial <?php echo $anchor_class; ?>"><?php echo $size_key; ?></a>
						<?php
							}
						}
						?>
					</div>
				</div>
				<div class="media-frame-content">
					<div class="attachments-browser">
						<div class="attachments">
							<div id="yoimg-cropper-container" style="max-width: <?php echo $full_image_attributes[1]; ?>px;max-height: <?php echo $full_image_attributes[2]; ?>px;">
								<img id="yoimg-cropper" src="<?php echo $full_image_attributes[0] . '?' . mt_rand( 1000, 9999 ); ?>" style="max-width: 100%;" />
								<div id="yoimg-replace-restore-wrapper">
									<div id="yoimg-replace-img-btn" style="display:none;" title="<?php _e( 'Replace image source for', YOIMG_DOMAIN ); ?> <?php echo $yoimg_image_size; ?>" class="button button-primary button-large"><?php _e( 'Replace', YOIMG_DOMAIN ); ?></div>
									<?php if ( $has_replacement ) {?>
										<div id="yoimg-restore-img-btn" title="<?php _e( 'Restore original image source for', YOIMG_DOMAIN ); ?> <?php echo $yoimg_image_size; ?>" class="button button-large"><?php _e( 'Restore', YOIMG_DOMAIN ); ?></div>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class="media-sidebar">
							<div class="attachment-details">
								<?php
								$this_crop_exists = ! empty( $attachment_metadata['sizes'][$yoimg_image_size]['file'] );
								if ( $this_crop_exists ) {
								?>
									<h3><?php _e( 'Current', YOIMG_DOMAIN ); ?> <?php echo $yoimg_image_size; ?> (<?php echo $attachment_metadata['sizes'][$yoimg_image_size]['width']; ?>x<?php echo $attachment_metadata['sizes'][$yoimg_image_size]['height']; ?>)</h3>
								<?php
								} else {
								?>
									<h3><?php _e( 'Current', YOIMG_DOMAIN ); ?> <?php echo $yoimg_image_size; ?> (<?php echo $curr_size_width; ?>x<?php echo $curr_size_height; ?>)</h3>
								<?php	
								}
								$image_attributes = wp_get_attachment_image_src( $yoimg_image_id, $yoimg_image_size );
								if ( $this_crop_exists ) {
								?>
									<img src="<?php echo $image_attributes[0] . '?' . mt_rand( 1000, 9999 ); ?>" style="max-width: 100%;" />
									<?php
									$is_crop_smaller = $attachment_metadata['sizes'][$yoimg_image_size]['width'] < $curr_size_width || $attachment_metadata['sizes'][$yoimg_image_size]['height'] < $curr_size_height;
									?>
									<div class="message error yoimg-crop-smaller" style="display:<?php echo $is_crop_smaller ? 'block' : 'none'; ?>;">
										<p><?php printf ( __( 'This crop is smaller (%1$sx%2$s) than expected (%3$sx%4$s), you may replace the original image for this crop format using the replace button here below and then cropping it', YOIMG_DOMAIN ), $attachment_metadata['sizes'][$yoimg_image_size]['width'], $attachment_metadata['sizes'][$yoimg_image_size]['height'], $curr_size_width, $curr_size_height ); ?></p>
									</div>
								<?php
								} else {
									$img_url_parts = parse_url( $image_attributes[0] );
									$img_path_parts = pathinfo( $img_url_parts['path'] );
									$expected_crop_width = min( $cropped_image_sizes['width'], $full_image_attributes[1] );
									$expected_crop_height = min( $cropped_image_sizes['height'], $full_image_attributes[2] );
									$expected_url = $img_path_parts['dirname'] . '/' . yoimg_get_cropped_image_filename( $img_path_parts['filename'], $expected_crop_width, $expected_crop_height, $img_path_parts['extension'] );
									?>
									<div class="yoimg-not-existing-crop">
										<img src="<?php echo $expected_url; ?>" style="max-width: 100%;" />
										<div class="message error">
											<?php
											if ( $is_full_image_too_small ) {
											?>
												<p><?php _e( 'Crop cannot be generated because original image is too small, you may replace the original image for this crop format using the replace button here below', YOIMG_DOMAIN ); ?></p>
											<?php
											} else {
											?>
												<p><?php _e( 'Crop not generated yet, use the crop button here below to generate it', YOIMG_DOMAIN ); ?></p>
											<?php
											}
											?>
										</div>
									</div>
								<?php } ?>
								<h3 id="yoimg-cropper-preview-title"><?php _e( 'Crop preview', YOIMG_DOMAIN ); ?></h3>
								<div id="yoimg-cropper-preview"></div>
								<div class="yoimg-cropper-quality-wrapper">
									<label for="yoimg-cropper-quality"><?php _e( 'Crop quality', YOIMG_DOMAIN ); ?>:</label>
									<select name="quality" id="yoimg-cropper-quality">
									
										<?php
										$yoimg_settings = get_option( 'yoimg_settings' );
										$crop_qualities = $yoimg_settings && isset( $yoimg_settings['crop_qualities'] ) ? $yoimg_settings['crop_qualities'] : unserialize( YOIMG_DEFAULT_CROP_QUALITIES );
										foreach ($crop_qualities AS $index => $value) {
										?>
											<option value="<?php echo $value; ?>"><?php echo $value; ?>%</option>
										<?php
										}
										?>
									</select>
								</div>
								<div class="yoimg-crop-now-wrapper">
									<a href="<?php echo $is_full_image_too_small ? 'javascript:;' : 'javascript:yoimgCropImage();';?>"
											class="button media-button button-primary button-large media-button-select <?php echo $is_full_image_too_small ? 'disabled' : '';?>">
										<?php _e( 'Crop', YOIMG_DOMAIN ); ?> <?php echo $yoimg_image_size; ?>
									</a>
									<span class="spinner"></span>
								</div>
							</div>
						</div>
					</div>
				</div>	
			</div>
<?php if ( ( ! isset( $_GET['partial'] ) ) || $_GET['partial'] != '1' ) { ?>
		</div>
	</div>
	<div id="yoimg-cropper-bckgr" class="media-modal-backdrop"></div>
</div>
<?php } ?>
<script>yoimgInitCropImage();</script>
<?php
exit();
