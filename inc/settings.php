<?php
if (! defined ( 'ABSPATH' )) {
	die ( 'No script kiddies please!' );
}
class WprieSettingsPage {
	public function __construct() {
		add_action ( 'admin_menu', array ( $this, 'add_plugin_page' ) );
		//add_action ( 'admin_init', array ( $this, 'page_init' ) );
	}
	public function add_plugin_page() {
		add_options_page( 'YoImages ' . __( 'settings', WPRIE_DOMAIN ), 'YoImages', 'manage_options', 'wprie-settings', array( $this, 'create_admin_page' ) );
	}
	public function create_admin_page() {
		if ( !current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>Wheee</h2>
		</div>
		<?php
	}
}

new WprieSettingsPage ();
