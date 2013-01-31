<?php
/**
 * Theme Options for Vertigo
 *
 * @package Vertigo
 **/

add_action( 'admin_init', 'vertigo_theme_options_init' );
add_action( 'admin_menu', 'vertigo_theme_options_add_page' );

/**
 * Init plugin options to white list our options and add css for options page
 **/
function vertigo_theme_options_init() {
	register_setting( 'vertigo_options', 'vertigo_theme_options', 'vertigo_theme_options_validate' );
	wp_enqueue_style( 'vertigo', get_template_directory_uri() . '/inc/theme-options.css', false, '1.0', 'all' );
}

/**
 * Load up the menu page
 **/
function vertigo_theme_options_add_page() {
	add_theme_page( __( 'Theme Options', 'vertigo' ), __( 'Theme Options', 'vertigo' ), 'edit_theme_options', 'theme_options', 'vertigo_theme_options_do_page' );
}

/**
 * Add JS for theme options form
 **/
function vertigo_enqueue_theme_options_scripts() {
	wp_enqueue_script( 'theme-options', get_template_directory_uri() . '/js/jscolor/jscolor.js' );
}
add_action( 'admin_print_scripts-appearance_page_theme_options', 'vertigo_enqueue_theme_options_scripts' );

/**
 * Describe the available options
 **/
$vertigo_options_template = array(
	array(
		'name' => __( 'Accent Color', 'vertigo' ),
		'desc' => __( 'Change the accent color by entering a HEX color number. (ie: <code>EE3322</code>)', 'vertigo' ),
		'id' => 'accent_color',
		'std' => 'ee3322',
		'type' => 'colorpicker'
	),
	
	array(
		'name' => __( 'Font', 'vertigo' ),
		'desc' => __( 'Enable Hitchcock custom font (Note: this font only supports basic Latin uppercase letters, numerals, and some punctuation.)', 'vertigo' ),
		'id' => 'vertigo_font',
		'std' => ( '1' == get_option( 'lang_id' ) ) ? 'true' : 'false',
		'type' => 'checkbox'
	),	
);


/**
 * Calculate default option values
 *
 * @return array
 **/
function vertigo_get_default_options() {
	global $vertigo_options_template;
	$default_options = array();

	foreach ( $vertigo_options_template as $option )
		$default_options[$option['id']] = $option['std'];

	return $default_options;
}

/**
 * Create the options form
 **/
function vertigo_theme_options_do_page() {
	global $vertigo_options_template;

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;
	?>

	<div class="wrap">

		<?php screen_icon(); echo "<h2>" . get_current_theme() . ' ' . __( 'Theme Options', 'vertigo' ) . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved.', 'vertigo' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'vertigo_options' ); ?>
			<?php $vertigo_options = vertigo_get_theme_options(); ?>

			<table class="form-table">

			<?php foreach ( $vertigo_options_template as $option ) {
				// Use default value if no option exists
				$value = ( isset ( $vertigo_options[$option['id']] ) && !empty( $vertigo_options[$option['id']] ) ? $vertigo_options[$option['id']] : $option['std'] );
			?>
				<tr valign="top">
					<th scope="row">
						<?php echo $option['name']; ?>
					</th>
					<td>
					<?php switch ( $option['type'] ) {
						case 'colorpicker':
					?>
						<input type="text" name="vertigo_theme_options[<?php echo esc_attr( $option['id'] ); ?>]" id="<?php echo esc_attr( $option['id'] ); ?>" value="<?php echo esc_attr( $value ); ?>" class="color { pickerPosition:'right' }" />
					<?php break;

					case 'checkbox':
					?>
						<input type="checkbox" name="vertigo_theme_options[<?php echo esc_attr( $option['id'] ); ?>]" id="<?php echo esc_attr( $option['id'] ); ?>" value="true" <?php echo ( 'true' == $value ) ? 'checked="checked"' : ''; ?> />
					<?php break;

						default:
							break;
					} // END switch ?>

						<label class="description" for="<?php echo esc_attr( $option['id'] ); ?>">
							<?php echo $option['desc']; ?>
							<?php if ( 'vertigo_font' == $option['id'] ) { ?>
								<img src="<?php echo get_template_directory_uri(); ?>/inc/images/hitchcock.gif" alt="Hitchcock" id="hitchcock-sample"/>
							<?php } ?>
						</label>
					</td>
				</tr>
								
			<?php } // END foreach ?>
			</table>

			<p class="submit">
				<?php submit_button( __( 'Save Options', 'vertigo' ), 'primary', 'submit', false ); ?>
				<?php submit_button( __( 'Reset Options', 'vertigo' ), 'secondary', 'vertigo_theme_options[reset]', false, array( 'id' => 'reset' ) ); ?>
			</p>

		</form>

	</div><!-- .form-wrap -->

<?php
}

/**
 * Sanitize and validate form input
 *
 * @param array options
 * @return array sanitized options
 **/
function vertigo_theme_options_validate( $input ) {
	global $vertigo_options_template;
	$defaults = vertigo_get_default_options();

	// Check accent color input format
	// Valid = hexadecimal 3 or 6 digits
	$accent_color = preg_replace( '/[^0-9a-fA-F]/', '', $input['accent_color'] );
	if ( 6 == strlen( $accent_color ) || 3 == strlen( $accent_color ) )
		$input['accent_color'] = $accent_color;
	else
		$input['accent_color'] = $defaults['accent_color'];
		
	// Check that Vertigo font checkbox value is either true or false
	if ( ! isset( $input['vertigo_font'] ) )
	$input['vertigo_font'] = ( $input['vertigo_font'] == 'true' ? 'true' : 'false' );

	// Reset to default options
	if ( ! empty( $input['reset'] ) ) {
		$defaults = vertigo_get_default_options();
		foreach ( $input as $field => $value ) {
			if ( isset( $defaults[$field] ) )
				$input[$field] = $defaults[$field];
			else
				unset( $input[$field] );
		}
	}

	return $input;
}