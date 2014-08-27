<?php
$sizes = wprie_get_image_sizes ();
foreach ( $sizes as $size_key => $size_value ) {
	if ( $size_value['crop'] ) {
	?>
	<div>
		<h3>
		<?php echo $size_key; ?>
		</h3>
		<?php
		$image_attributes = wp_get_attachment_image_src( $wprie_image_id, $size_key );
		?>
		<img src="<?php echo $image_attributes[0] . '?' . mt_rand( 1000, 9999 ); ?>" style="max-width: 100%;" />
		<p>
			<ul>
				<li>width: <?php echo $size_value['width']; ?></li>
				<li>height: <?php echo $size_value['height']; ?></li>
			</ul>
			<?php
			if ( $size_value['crop'] ) {
			?>
				<a href="<?php echo wprie_get_edit_image_url( $wprie_image_id, $size_key ); ?>">edit</a>
			<?php
			}
			?>
		</p>
	</div>
	<?php
	}
}
