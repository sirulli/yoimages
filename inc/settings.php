<?php
if (! defined ( 'ABSPATH' )) {
	die ( 'No script kiddies please!' );
}

function yoimg_default_supported_expressions( $supported_expressions ) {
	array_push( $supported_expressions, YOIMG_TITLE_EXPRESSION, YOIMG_POST_TYPE_EXPRESSION, YOIMG_SITE_NAME_EXPRESSION, YOIMG_TAGS_EXPRESSION, YOIMG_CATEGORIES_EXPRESSION );
	return $supported_expressions;
}
add_filter( 'yoimg_supported_expressions', 'yoimg_default_supported_expressions', 10, 1 );

class WprieSettingsPage {
	
	private $options;
	
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
		$this->options = get_option( 'yoimg_settings' );
		?>
		<div class="wrap" id="yoimg-settings-wrapper">
			<h2><?php _e( 'YoImages settings', YOIMG_DOMAIN ); ?></h2>
			<form method="post" action="options.php">
			<?php
				settings_fields( 'yoimg_crop_options_group' );   
				do_settings_sections( 'yoimg-settings' );
				submit_button(); 
			?>
			</form>
		</div>
		<?php
	}

	public function init_admin_page() {
		register_setting( 'yoimg_crop_options_group', 'yoimg_settings', array( $this, 'sanitize' ) );
		
		add_settings_section( 'yoimg_crop_options_section', __( 'Cropping', YOIMG_DOMAIN ), array( $this, 'print_crop_options_section_info' ), 'yoimg-settings' );
		add_settings_field( 'cropping_is_active', __( 'Active', YOIMG_DOMAIN ), array( $this, 'cropping_is_active_callback' ), 'yoimg-settings', 'yoimg_crop_options_section' );
		add_settings_field( 'crop_qualities', __( 'Crop qualities', YOIMG_DOMAIN), array( $this, 'crop_qualities_callback' ), 'yoimg-settings', 'yoimg_crop_options_section' );
		
		add_settings_section( 'yoimg_imgseo_options_section', __( 'SEO for images', YOIMG_DOMAIN ), array( $this, 'print_imgseo_options_section_info' ), 'yoimg-settings' );
		add_settings_field( 'imgseo_change_image_title', __( 'Change image title', YOIMG_DOMAIN ), array( $this, 'imgseo_change_image_title_callback' ), 'yoimg-settings', 'yoimg_imgseo_options_section' );
		add_settings_field( 'imgseo_image_title_expression', __( 'Image title expression', YOIMG_DOMAIN), array( $this, 'imgseo_image_title_expression_callback' ), 'yoimg-settings', 'yoimg_imgseo_options_section' );
		add_settings_field( 'imgseo_change_image_alt', __( 'Change image alt attribute', YOIMG_DOMAIN ), array( $this, 'imgseo_change_image_alt_callback' ), 'yoimg-settings', 'yoimg_imgseo_options_section' );
		add_settings_field( 'imgseo_image_alt_expression', __( 'Image alt expression', YOIMG_DOMAIN), array( $this, 'imgseo_image_alt_expression_callback' ), 'yoimg-settings', 'yoimg_imgseo_options_section' );
		add_settings_field( 'imgseo_change_image_filename', __( 'Change image file name', YOIMG_DOMAIN ), array( $this, 'imgseo_change_image_filename_callback' ), 'yoimg-settings', 'yoimg_imgseo_options_section' );
		add_settings_field( 'imgseo_image_filename_expression', __( 'Image file name expression', YOIMG_DOMAIN), array( $this, 'imgseo_image_filename_expression_callback' ), 'yoimg-settings', 'yoimg_imgseo_options_section' );
		
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
			'<input type="checkbox" id="cropping_is_active" name="yoimg_settings[cropping_is_active]" value="TRUE" %s />
			<p class="description">' . __( 'Lorem ipsum cropping_is_active', YOIMG_DOMAIN ) . '</p>',
			$this->options['cropping_is_active'] ? 'checked="checked"' : ( YOIMG_DEFAULT_CROP_ENABLED && ! isset( $this->options['cropping_is_active'] ) ? 'checked="checked"' : '' )
		);
	}

	public function crop_qualities_callback() {
		printf(
			'<input type="text" id="crop_qualities" name="yoimg_settings[crop_qualities]" value="%s" class="cropping_is_active-dep" />
			<p class="description">' . __( 'Comma separated list of crop quality values', YOIMG_DOMAIN ) . '</p>',
			! empty( $this->options['crop_qualities'] ) ? esc_attr( implode( ',', $this->options['crop_qualities'] ) ) : implode( ',', unserialize( YOIMG_DEFAULT_CROP_QUALITIES ) )
		);
	}

	public function imgseo_change_image_title_callback() {
		printf(
			'<input type="checkbox" id="imgseo_change_image_title" name="yoimg_settings[imgseo_change_image_title]" value="TRUE" %s />
			<p class="description">' . __( 'Lorem ipsum imgseo_change_image_title', YOIMG_DOMAIN ) . '</p>',
			$this->options['imgseo_change_image_title'] ? 'checked="checked"' : ( YOIMG_DEFAULT_IMGSEO_CHANGE_IMAGE_TITLE && ! isset( $this->options['imgseo_change_image_title'] ) ? 'checked="checked"' : '' )
		);
	}

	public function imgseo_image_title_expression_callback() {
		printf(
		'<input type="text" id="imgseo_image_title_expression" name="yoimg_settings[imgseo_image_title_expression]" value="%s" class="imgseo_change_image_title-dep" />
			<p class="description">' . __( 'Lorem ipsum imgseo_image_title_expression', YOIMG_DOMAIN ) . '</p>',
				! empty( $this->options['imgseo_image_title_expression'] ) ? esc_attr( $this->options['imgseo_image_title_expression'] ) : YOIMG_IMGSEO_IMAGE_TITLE_EXPRESSION
		);
	}

	public function imgseo_change_image_alt_callback() {
		printf(
			'<input type="checkbox" id="imgseo_change_image_alt" name="yoimg_settings[imgseo_change_image_alt]" value="TRUE" %s />
			<p class="description">' . __( 'Lorem ipsum imgseo_change_image_alt', YOIMG_DOMAIN ) . '</p>',
			$this->options['imgseo_change_image_alt'] ? 'checked="checked"' : ( YOIMG_DEFAULT_IMGSEO_CHANGE_IMAGE_ALT && ! isset( $this->options['imgseo_change_image_alt'] ) ? 'checked="checked"' : '' )
		);
	}

	public function imgseo_image_alt_expression_callback() {
		printf(
		'<input type="text" id="imgseo_image_alt_expression" name="yoimg_settings[imgseo_image_alt_expression]" value="%s" class="imgseo_change_image_alt-dep" />
			<p class="description">' . __( 'Lorem ipsum imgseo_image_alt_expression', YOIMG_DOMAIN ) . '</p>',
				! empty( $this->options['imgseo_image_alt_expression'] ) ? esc_attr( $this->options['imgseo_image_alt_expression'] ) : YOIMG_IMGSEO_IMAGE_ALT_EXPRESSION
		);
	}

	public function imgseo_change_image_filename_callback() {
		printf(
			'<input type="checkbox" id="imgseo_change_image_filename" name="yoimg_settings[imgseo_change_image_filename]" value="TRUE" %s />
			<p class="description">' . __( 'Lorem ipsum imgseo_change_image_filename', YOIMG_DOMAIN ) . '</p>',
			$this->options['imgseo_change_image_filename'] ? 'checked="checked"' : ( YOIMG_DEFAULT_IMGSEO_CHANGE_IMAGE_FILENAME && ! isset( $this->options['imgseo_change_image_filename'] ) ? 'checked="checked"' : '' )
		);
	}

	public function imgseo_image_filename_expression_callback() {
		printf(
		'<input type="text" id="imgseo_image_filename_expression" name="yoimg_settings[imgseo_image_filename_expression]" value="%s" class="imgseo_change_image_filename-dep" />
			<p class="description">' . __( 'Lorem ipsum imgseo_image_filename_expression', YOIMG_DOMAIN ) . '</p>',
				! empty( $this->options['imgseo_image_filename_expression'] ) ? esc_attr( $this->options['imgseo_image_filename_expression'] ) : YOIMG_IMGSEO_IMAGE_FILENAME_EXPRESSION
		);
	}

	public function sanitize( $input ) {
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
			add_settings_error( 'yoimg_crop_options_group', 'imgseo_image_title_expression', __( 'imgseo_image_title_expression is not valid, using default:', YOIMG_DOMAIN ) . ' ' . YOIMG_DEFAULT_IMGSEO_IMAGE_TITLE_EXPRESSION, 'error' );
			$new_input['imgseo_image_title_expression'] = YOIMG_DEFAULT_IMGSEO_IMAGE_TITLE_EXPRESSION;
		}
		if( isset( $input['imgseo_image_alt_expression'] ) && ! empty( $input['imgseo_image_alt_expression'] ) ) {
			$new_input['imgseo_image_alt_expression'] = sanitize_text_field( $input['imgseo_image_alt_expression'] );
		} else {
			add_settings_error( 'yoimg_crop_options_group', 'imgseo_image_alt_expression', __( 'imgseo_image_alt_expression is not valid, using default:', YOIMG_DOMAIN ) . ' ' . YOIMG_DEFAULT_IMGSEO_IMAGE_ALT_EXPRESSION, 'error' );
			$new_input['imgseo_image_alt_expression'] = YOIMG_DEFAULT_IMGSEO_IMAGE_ALT_EXPRESSION;
		}
		if( isset( $input['imgseo_image_filename_expression'] ) && ! empty( $input['imgseo_image_filename_expression'] ) ) {
			$new_input['imgseo_image_filename_expression'] = sanitize_text_field( $input['imgseo_image_filename_expression'] );
		} else {
			add_settings_error( 'yoimg_crop_options_group', 'imgseo_image_filename_expression', __( 'imgseo_image_filename_expression is not valid, using default:', YOIMG_DOMAIN ) . ' ' . YOIMG_DEFAULT_IMGSEO_IMAGE_FILENAME_EXPRESSION, 'error' );
			$new_input['imgseo_image_filename_expression'] = YOIMG_DEFAULT_IMGSEO_IMAGE_FILENAME_EXPRESSION;
		}
		return $new_input;
	}
	
}

new WprieSettingsPage ();
