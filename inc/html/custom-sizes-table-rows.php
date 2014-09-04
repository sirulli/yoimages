<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

?>

<tr>
	<th scope="row"><?php _e( 'Theme defined sizes', WPRIE_DOMAIN ); ?></th>
	<td></td>
</tr>

<?php
$sizes = wprie_get_image_sizes ();
foreach ( $sizes as $size_key => $size_value ) {
	if (! in_array ( $size_key, array (
		'thumbnail',
		'medium',
		'large'
	) ) ) {
?>

<tr>
	<th scope="row"><?php echo $size_key; ?></th>
	<td>
		<fieldset>
			<legend class="screen-reader-text"><span><?php echo $size_key; ?></span></legend>
			<?php if ( $size_value['crop'] == 1 ) { ?>
				<label><?php _e( 'Width', WPRIE_DOMAIN ); ?>: <?php echo $size_value['width']; ?></label>
				<br />
				<label><?php _e( 'Height', WPRIE_DOMAIN ); ?>: <?php echo $size_value['height']; ?></label>
				<br />
				<label><?php _e( 'Hard cropped', WPRIE_DOMAIN ); ?></label>
			<?php } else { ?>
				<label><?php _e( 'Max Width', WPRIE_DOMAIN ); ?>: <?php echo $size_value['width']; ?></label>
				<br />
				<label><?php _e( 'Max Height', WPRIE_DOMAIN ); ?>: <?php echo $size_value['height']; ?></label>
			<?php } ?>
		</fieldset>
	</td>
</tr>

<?php
	}
}
