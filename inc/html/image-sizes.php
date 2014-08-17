<?php
$sizes = wprie_get_image_sizes ();
foreach ( $sizes as $size_key => $size_value ) {
	?>
	<div>
		<h3>
		<?php echo $size_key; ?>
		</h3>
		<?php
		echo wp_get_attachment_image ( $wprie_image_id, $size_key );
		?>
		<p>
			<ul>
				<li>width: <?php echo $size_value['width']; ?></li>
				<li>height: <?php echo $size_value['height']; ?></li>
				<li>crop: <?php echo $size_value['crop'] ? 'true' : 'false'; ?></li>
			</ul>
		</p>
	</div>
	<?php
}
?>