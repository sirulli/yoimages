<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

$cropped_image_sizes = wprie_get_image_sizes( $wprie_image_size );
$full_image_attributes = wp_get_attachment_image_src( $wprie_image_id, 'full' );
?>
<div id="wprie-cropper-wrapper">
	<script>
	var wprie_back_url = '<?php echo admin_url ( 'post.php?post=' . $wprie_image_id . '&action=edit' ); ?>';
	var wprie_image_id = <?php echo $wprie_image_id; ?>;
	var wprie_image_size = '<?php echo $wprie_image_size; ?>';
	var wprie_cropper_min_width = <?php echo $cropped_image_sizes['width']; ?>;
	var wprie_cropper_min_height = <?php echo $cropped_image_sizes['height']; ?>;
	var wprie_cropper_aspect_ratio = <?php echo $cropped_image_sizes['width']; ?> / <?php echo $cropped_image_sizes['height']; ?>;
	</script>
	<div class="wprie-buttons">
		<input type="button" value="Cancel" class="button" onclick="javascript:wprieCancelCropImage();" />
		<input type="button" value="Save" class="button button-primary" onclick="javascript:wprieCropImage();" />
	</div>
	<div style="max-width: <?php echo $full_image_attributes[1]; ?>px;max-height: <?php echo $full_image_attributes[2]; ?>px;">
		<img id="wprie-cropper" src="<?php echo $full_image_attributes[0] . '?' . mt_rand( 1000, 9999 ); ?>" style="max-width: 100%;" />
	</div>
</div>
<script>wprieInitCropImage();</script>
<?php
exit();
