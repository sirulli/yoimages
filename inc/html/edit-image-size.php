<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

$cropped_image_sizes = wprie_get_image_sizes( $wprie_image_size );
$full_image_attributes = wp_get_attachment_image_src( $wprie_image_id, 'full' );
?>	
<script>
	var wprie_image_id = <?php echo $wprie_image_id; ?>;
	var wprie_image_size = '<?php echo $wprie_image_size; ?>';
	var wprie_cropper_min_width = <?php echo $cropped_image_sizes['width']; ?>;
	var wprie_cropper_min_height = <?php echo $cropped_image_sizes['height']; ?>;
	var wprie_cropper_aspect_ratio = <?php echo $cropped_image_sizes['width']; ?> / <?php echo $cropped_image_sizes['height']; ?>;
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
				<div class="media-frame-title"><h1><?php _e( 'Edit cropped formats', WPRIE_DOMAIN ); ?></h1></div>
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
					<div style="max-width: <?php echo $full_image_attributes[1]; ?>px;max-height: <?php echo $full_image_attributes[2]; ?>px;">
						<img id="wprie-cropper" src="<?php echo $full_image_attributes[0] . '?' . mt_rand( 1000, 9999 ); ?>" style="max-width: 100%;" />
					</div>
				</div>
				<div class="media-frame-toolbar">
	        		<div class="media-toolbar">
						<div class="media-toolbar-primary">
							<a href="javascript:wprieCancelCropImage();" class="button media-button button-large media-button-select"><?php _e( 'Cancel', WPRIE_DOMAIN ); ?></a>
							<a href="javascript:wprieCropImage();" class="button media-button button-primary button-large media-button-select"><?php _e( 'Save', WPRIE_DOMAIN ); ?></a>
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
