<?php

if ( ! defined ( 'ABSPATH' ) ) {
	die ( 'No script kiddies please!' );
}

function wprie_print_media_templates() {
	?>
	<script>
	jQuery(document).ready(function() {
		wprieExtendMediaLightboxTemplate(
			'<?php echo wprie_get_edit_image_anchor( '{{ data.id }}', 'thumbnail', 'display:block;text-decoration:none;' ); ?>',
			'<?php echo wprie_get_edit_image_anchor( '{{ data.id }}', 'thumbnail', 'text-decoration:none;' ); ?>',
			'<?php echo wprie_get_edit_image_anchor( '{{ data.id }}', 'thumbnail', '', 'button' ); ?>',
			'<?php echo wprie_get_edit_image_anchor( '{{ data.attachment.id }}', 'thumbnail', '', 'button' ); ?>'
		);
	});
	</script>
	<?php
}

add_action( 'print_media_templates', 'wprie_print_media_templates' );