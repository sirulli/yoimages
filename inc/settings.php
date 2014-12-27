<?php
if (! defined ( 'ABSPATH' )) {
	die ( 'No script kiddies please!' );
}

function yoimg_default_supported_expressions( $supported_expressions ) {
	if ( ! $supported_expressions ) {
		$supported_expressions = array();
	}
	array_push( $supported_expressions, YOIMG_TITLE_EXPRESSION, YOIMG_POST_TYPE_EXPRESSION, YOIMG_SITE_NAME_EXPRESSION, YOIMG_TAGS_EXPRESSION, YOIMG_CATEGORIES_EXPRESSION );
	return $supported_expressions;
}
add_filter( 'yoimg_supported_expressions', 'yoimg_default_supported_expressions', 10, 1 );

class WprieSettingsPage {
	
	private $crop_options;
	private $seo_options;
	
	public function __construct() {
		add_action ( 'admin_menu', array ( $this, 'add_plugin_page_menu_item' ) );
		add_action ( 'admin_init', array ( $this, 'init_admin_page' ) );
	}
	
	public function add_plugin_page_menu_item() {
		add_options_page( __( 'YoImages settings', YOIMG_DOMAIN ), 'YoImages', 'manage_options', 'yoimg-settings', array( $this, 'create_admin_page' ) );
	}
	
	public function create_admin_page() {
		if ( !current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		$this->crop_options = get_option( 'yoimg_crop_settings' );
		$this->seo_options = get_option( 'yoimg_seo_settings' );
		?>
		<div class="wrap" id="yoimg-settings-wrapper">
			<h2><?php _e( 'YoImages settings', YOIMG_DOMAIN ); ?></h2>
			<?php
			if( isset( $_GET[ 'tab' ] ) ) {
				$active_tab = $_GET[ 'tab' ];
			} else {
				$active_tab = 'yoimg-crop-settings';
			}
			?>
			<h2 class="nav-tab-wrapper">
				<a href="?page=yoimg-settings&tab=yoimg-crop-settings" class="nav-tab <?php echo $active_tab == 'yoimg-crop-settings' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Crop settings', YOIMG_DOMAIN ); ?></a>
				<a href="?page=yoimg-settings&tab=yoimg-seo-settings" class="nav-tab <?php echo $active_tab == 'yoimg-seo-settings' ? 'nav-tab-active' : ''; ?>"><?php  _e( 'SEO for images', YOIMG_DOMAIN ); ?></a>
			</h2>
			<form method="post" action="options.php">
			<?php
				settings_fields( $active_tab . '-group' );
				do_settings_sections( $active_tab );
				submit_button(); 
			?>
			</form>
		</div>
		<?php
	}

	public function init_admin_page() {
		register_setting( 'yoimg-crop-settings-group', 'yoimg_crop_settings', array( $this, 'sanitize_crop' ) );
		register_setting( 'yoimg-seo-settings-group', 'yoimg_seo_settings', array( $this, 'sanitize_seo' ) );
		
		add_settings_section( 'yoimg_crop_options_section', __( 'Crop settings', YOIMG_DOMAIN ), array( $this, 'print_crop_options_section_info' ), 'yoimg-crop-settings' );
		add_settings_field( 'cropping_is_active', __( 'Enable', YOIMG_DOMAIN ), array( $this, 'cropping_is_active_callback' ), 'yoimg-crop-settings', 'yoimg_crop_options_section' );
		add_settings_field( 'crop_qualities', __( 'Crop qualities', YOIMG_DOMAIN), array( $this, 'crop_qualities_callback' ), 'yoimg-crop-settings', 'yoimg_crop_options_section' );
		
		add_settings_section( 'yoimg_imgseo_options_section', __( 'SEO for images', YOIMG_DOMAIN ), array( $this, 'print_imgseo_options_section_info' ), 'yoimg-seo-settings' );
		add_settings_field( 'imgseo_change_image_title', __( 'Change image title', YOIMG_DOMAIN ), array( $this, 'imgseo_change_image_title_callback' ), 'yoimg-seo-settings', 'yoimg_imgseo_options_section' );
		add_settings_field( 'imgseo_image_title_expression', __( 'Image title expression', YOIMG_DOMAIN), array( $this, 'imgseo_image_title_expression_callback' ), 'yoimg-seo-settings', 'yoimg_imgseo_options_section' );
		add_settings_field( 'imgseo_change_image_alt', __( 'Change image alt attribute', YOIMG_DOMAIN ), array( $this, 'imgseo_change_image_alt_callback' ), 'yoimg-seo-settings', 'yoimg_imgseo_options_section' );
		add_settings_field( 'imgseo_image_alt_expression', __( 'Image alt expression', YOIMG_DOMAIN), array( $this, 'imgseo_image_alt_expression_callback' ), 'yoimg-seo-settings', 'yoimg_imgseo_options_section' );
		add_settings_field( 'imgseo_change_image_filename', __( 'Change image file name', YOIMG_DOMAIN ), array( $this, 'imgseo_change_image_filename_callback' ), 'yoimg-seo-settings', 'yoimg_imgseo_options_section' );
		add_settings_field( 'imgseo_image_filename_expression', __( 'Image file name expression', YOIMG_DOMAIN), array( $this, 'imgseo_image_filename_expression_callback' ), 'yoimg-seo-settings', 'yoimg_imgseo_options_section' );
		
	}
	
	public function print_crop_options_section_info() {
		print __('Enter your cropping settings here below', YOIMG_DOMAIN );
	}

	public function print_imgseo_options_section_info() {
		print __('Enter your images SEO settings here below', YOIMG_DOMAIN );
		$supported_expressions = array();
		printf( '<p>' .
			__( 'Supported expressions:', YOIMG_DOMAIN ) . ' ' . implode( ', ', apply_filters( 'yoimg_supported_expressions', $supported_expressions ) )
			. '</p>'
		);
	}
	
	public function cropping_is_active_callback() {
		printf(
			'<input type="checkbox" id="cropping_is_active" name="yoimg_crop_settings[cropping_is_active]" value="TRUE" %s />
			<p class="description">' . __( 'If checked cropping is active', YOIMG_DOMAIN ) . '</p>',
			$this->crop_options['cropping_is_active'] ? 'checked="checked"' : ( YOIMG_DEFAULT_CROP_ENABLED && ! isset( $this->crop_options['cropping_is_active'] ) ? 'checked="checked"' : '' )
		);
	}

	public function crop_qualities_callback() {
		printf(
			'<input type="text" id="crop_qualities" name="yoimg_crop_settings[crop_qualities]" value="%s" class="cropping_is_active-dep" />
			<p class="description">' . __( 'Comma separated list of crop quality values (100 best to 50 medium)', YOIMG_DOMAIN ) . '</p>',
			! empty( $this->crop_options['crop_qualities'] ) ? esc_attr( implode( ',', $this->crop_options['crop_qualities'] ) ) : implode( ',', unserialize( YOIMG_DEFAULT_CROP_QUALITIES ) )
		);
	}

	public function imgseo_change_image_title_callback() {
		printf(
			'<input type="checkbox" id="imgseo_change_image_title" name="yoimg_seo_settings[imgseo_change_image_title]" value="TRUE" %s />
			<p class="description">' . __( 'If checked title will be replaced with the expression here below', YOIMG_DOMAIN ) . '</p>',
			$this->seo_options['imgseo_change_image_title'] ? 'checked="checked"' : ( YOIMG_DEFAULT_IMGSEO_CHANGE_IMAGE_TITLE && ! isset( $this->seo_options['imgseo_change_image_title'] ) ? 'checked="checked"' : '' )
		);
	}

	public function imgseo_image_title_expression_callback() {
		printf(
		'<input type="text" id="imgseo_image_title_expression" name="yoimg_seo_settings[imgseo_image_title_expression]" value="%s" class="imgseo_change_image_title-dep" />
			<p class="description">' . __( 'expression used to replace the title, accepted values are:', YOIMG_DOMAIN ) . ' ' . implode( ', ', apply_filters( 'yoimg_supported_expressions', $supported_expressions ) ) . '</p>',
				! empty( $this->seo_options['imgseo_image_title_expression'] ) ? esc_attr( $this->seo_options['imgseo_image_title_expression'] ) : YOIMG_IMGSEO_IMAGE_TITLE_EXPRESSION
		);
	}

	public function imgseo_change_image_alt_callback() {
		printf(
			'<input type="checkbox" id="imgseo_change_image_alt" name="yoimg_seo_settings[imgseo_change_image_alt]" value="TRUE" %s />
			<p class="description">' . __( 'If checked alt will be replaced with the expression here below', YOIMG_DOMAIN ) . '</p>',
			$this->seo_options['imgseo_change_image_alt'] ? 'checked="checked"' : ( YOIMG_DEFAULT_IMGSEO_CHANGE_IMAGE_ALT && ! isset( $this->seo_options['imgseo_change_image_alt'] ) ? 'checked="checked"' : '' )
		);
	}

	public function imgseo_image_alt_expression_callback() {
		printf(
		'<input type="text" id="imgseo_image_alt_expression" name="yoimg_seo_settings[imgseo_image_alt_expression]" value="%s" class="imgseo_change_image_alt-dep" />
			<p class="description">' . __( 'expression used to replace the alt, accepted values are:', YOIMG_DOMAIN ) . ' ' . implode( ', ', apply_filters( 'yoimg_supported_expressions', $supported_expressions ) ) . '</p>',
				! empty( $this->seo_options['imgseo_image_alt_expression'] ) ? esc_attr( $this->seo_options['imgseo_image_alt_expression'] ) : YOIMG_IMGSEO_IMAGE_ALT_EXPRESSION
		);
	}

	public function imgseo_change_image_filename_callback() {
		printf(
			'<input type="checkbox" id="imgseo_change_image_filename" name="yoimg_seo_settings[imgseo_change_image_filename]" value="TRUE" %s />
			<p class="description">' . __( 'If checked the filename will be replaced with the expression here below', YOIMG_DOMAIN ) . '</p>',
			$this->seo_options['imgseo_change_image_filename'] ? 'checked="checked"' : ( YOIMG_DEFAULT_IMGSEO_CHANGE_IMAGE_FILENAME && ! isset( $this->seo_options['imgseo_change_image_filename'] ) ? 'checked="checked"' : '' )
		);
	}

	public function imgseo_image_filename_expression_callback() {
		printf(
		'<input type="text" id="imgseo_image_filename_expression" name="yoimg_seo_settings[imgseo_image_filename_expression]" value="%s" class="imgseo_change_image_filename-dep" />
			<p class="description">' . __( 'expression used to replace the filename, accepted values are:', YOIMG_DOMAIN ) . ' ' . implode( ', ', apply_filters( 'yoimg_supported_expressions', $supported_expressions ) ) . '</p>',
				! empty( $this->seo_options['imgseo_image_filename_expression'] ) ? esc_attr( $this->seo_options['imgseo_image_filename_expression'] ) : YOIMG_IMGSEO_IMAGE_FILENAME_EXPRESSION
		);
	}

	public function sanitize_crop( $input ) {
		$new_input = array();
		if( $input['cropping_is_active'] === 'TRUE' || $input['cropping_is_active'] === TRUE ) {
			$new_input['cropping_is_active'] = TRUE;
		} else {
			$new_input['cropping_is_active'] = FALSE;
		}
		if( isset( $input['crop_qualities'] ) ) {
			if ( is_array( $input['crop_qualities'] ) ) {
				$crop_qualities = $input['crop_qualities'];
			} else {
				$crop_qualities = explode( ',', $input['crop_qualities'] );
			}
			$crop_qualities_count = 0;
			foreach ($crop_qualities AS $index => $value) {
				$crop_quality_value = ( int ) $value;
				if ( $crop_quality_value > 0 && $crop_quality_value <= 100 ) {
					$crop_qualities_arr[$crop_qualities_count] = $crop_quality_value;
					$crop_qualities_count++;
				}
			}
			if( empty( $crop_qualities_arr ) ) {
				add_settings_error( 'yoimg_crop_options_group', 'crop_qualities', __( 'Crop qualities value is not valid, using default:', YOIMG_DOMAIN ) . ' ' . implode( ',', unserialize( YOIMG_DEFAULT_CROP_QUALITIES ) ), 'error' );
				$new_input['crop_qualities'] = unserialize( YOIMG_DEFAULT_CROP_QUALITIES );
			} else {
				$crop_qualities_arr = array_unique( $crop_qualities_arr );
				rsort( $crop_qualities_arr );
				$new_input['crop_qualities'] = $crop_qualities_arr;
			}
		} else {
			$new_input['crop_qualities'] = unserialize( YOIMG_DEFAULT_CROP_QUALITIES );
		}
		return $new_input;
	}

	public function sanitize_seo( $input ) {
		$new_input = array();
		if( $input['imgseo_change_image_title'] === 'TRUE' || $input['imgseo_change_image_title'] === TRUE ) {
			$new_input['imgseo_change_image_title'] = TRUE;
		} else {
			$new_input['imgseo_change_image_title'] = FALSE;
		}
		if( $input['imgseo_change_image_alt'] === 'TRUE' || $input['imgseo_change_image_alt'] === TRUE ) {
			$new_input['imgseo_change_image_alt'] = TRUE;
		} else {
			$new_input['imgseo_change_image_alt'] = FALSE;
		}
		if( $input['imgseo_change_image_filename'] === 'TRUE' || $input['imgseo_change_image_filename'] === TRUE ) {
			$new_input['imgseo_change_image_filename'] = TRUE;
		} else {
			$new_input['imgseo_change_image_filename'] = FALSE;
		}
		if( isset( $input['imgseo_image_title_expression'] ) && ! empty( $input['imgseo_image_title_expression'] ) ) {
			$new_input['imgseo_image_title_expression'] = sanitize_text_field( $input['imgseo_image_title_expression'] );
		} else {
			add_settings_error( 'yoimg_crop_options_group', 'imgseo_image_title_expression', __( 'title expression is not valid, using default:', YOIMG_DOMAIN ) . ' ' . YOIMG_DEFAULT_IMGSEO_IMAGE_TITLE_EXPRESSION, 'error' );
			$new_input['imgseo_image_title_expression'] = YOIMG_DEFAULT_IMGSEO_IMAGE_TITLE_EXPRESSION;
		}
		if( isset( $input['imgseo_image_alt_expression'] ) && ! empty( $input['imgseo_image_alt_expression'] ) ) {
			$new_input['imgseo_image_alt_expression'] = sanitize_text_field( $input['imgseo_image_alt_expression'] );
		} else {
			add_settings_error( 'yoimg_crop_options_group', 'imgseo_image_alt_expression', __( 'alt expression is not valid, using default:', YOIMG_DOMAIN ) . ' ' . YOIMG_DEFAULT_IMGSEO_IMAGE_ALT_EXPRESSION, 'error' );
			$new_input['imgseo_image_alt_expression'] = YOIMG_DEFAULT_IMGSEO_IMAGE_ALT_EXPRESSION;
		}
		if( isset( $input['imgseo_image_filename_expression'] ) && ! empty( $input['imgseo_image_filename_expression'] ) ) {
			$new_input['imgseo_image_filename_expression'] = sanitize_text_field( $input['imgseo_image_filename_expression'] );
		} else {
			add_settings_error( 'yoimg_crop_options_group', 'imgseo_image_filename_expression', __( 'filename expression is not valid, using default:', YOIMG_DOMAIN ) . ' ' . YOIMG_DEFAULT_IMGSEO_IMAGE_FILENAME_EXPRESSION, 'error' );
			$new_input['imgseo_image_filename_expression'] = YOIMG_DEFAULT_IMGSEO_IMAGE_FILENAME_EXPRESSION;
		}
		return $new_input;
	}

}

new WprieSettingsPage ();
