<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

$sizes = wprie_get_image_sizes ();
?>
<h3 class="title"><?php _e( 'Current theme defined sizes', WPRIE_DOMAIN ); ?></h3>
<?php
if ( count($sizes ) > 3 ) {
?>

<p><?php _e( 'The sizes listed below are the crop and resize formats defined by ', WPRIE_DOMAIN ); ?> <?php echo get_current_theme(); ?></p>

<table class="form-table">
<tbody>

<?php
foreach ( $sizes as $size_key => $size_value ) {
	if (! in_array ( $size_key, array (
		'thumbnail',
		'medium',
		'large'
	) ) ) {
?>

<tr class="wprie-size-row">
	<th scope="row"><?php echo $size_key; ?></th>
	<td>
		<?php if ( $size_value['crop'] == 1 ) { ?>
			<label class="wprie-first"><?php _e( 'Width', WPRIE_DOMAIN ); ?>: <?php echo $size_value['width']; ?></label>
			<label><?php _e( 'Height', WPRIE_DOMAIN ); ?>: <?php echo $size_value['height']; ?></label>
		<?php } else { ?>
			<label class="wprie-first"><?php _e( 'Max Width', WPRIE_DOMAIN ); ?>: <?php echo $size_value['width']; ?></label>
			<label><?php _e( 'Max Height', WPRIE_DOMAIN ); ?>: <?php echo $size_value['height']; ?></label>
		<?php } ?>
	</td>
</tr>

<tr class="wprie-size-type-row">
	<th scope="row"><?php _e( 'Type:', WPRIE_DOMAIN ); ?></th>
	<td>
		<?php if ( $size_value['crop'] == 1 ) { ?>
			<label><?php _e( 'Hard cropped', WPRIE_DOMAIN ); ?>
				<a class="dashicons dashicons-editor-help wprie-hard-crop-help" data-code="f223" href="javascript:;"></a>
			</label>
		<?php } else { ?>
			<label><?php _e( 'Resized', WPRIE_DOMAIN ); ?>
				<a class="dashicons dashicons-editor-help wprie-resize-help" data-code="f223" href="javascript:;"></a>
			</label>
		<?php } ?>
	</td>
</tr>

<?php
	}
}

?>
</tbody>
</table>
<script>
jQuery(document).ready(function(){
	jQuery('.wprie-hard-crop-help').pointer({
		content: '<h3><?php _e( 'Hard cropped image', WPRIE_DOMAIN ); ?></h3> <p><?php _e( 'this image format contains only a part of the original image, it has fixed width and height so that the image ratio is fixed too, e.g. thumbnail in listings are cropped', WPRIE_DOMAIN ); ?></p>',
		position: {
			edge: 'left',
			align: 'center'
		}
	});
	jQuery('.wprie-resize-help').pointer({
		content: '<h3><?php _e( 'Resized image', WPRIE_DOMAIN ); ?></h3> <p><?php _e( 'this image format is not cropped but instead it is resized to fit the maximum available space, either in width or height, therefore this image ratio is not fixed', WPRIE_DOMAIN ); ?></p>',
		position: {
			edge: 'left',
			align: 'center'
		}
	});
	jQuery('.wprie-hard-crop-help, .wprie-resize-help').click(function(){
		jQuery(this).pointer('open');
	});
});
</script>
<br />
<?php
} else {
?>
<p><?php echo get_current_theme(); ?> <?php _e( 'doesn\'t define any additional image size using the standard Wordpress functions (<a href="http://codex.wordpress.org/Function_Reference/add_image_size" target="_blank">add_image_size</a> and <a href="http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size" target="_blank">set_post_thumbnail_size</a>)', WPRIE_DOMAIN ); ?></p>
<br />
<?php
}
