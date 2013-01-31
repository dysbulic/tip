<?php
/**
 * iTheme2 Theme Options
 *
 * @package WordPress
 * @subpackage iTheme2
 * @since iTheme2 1.2-wpcom
 */

/**
 * Properly enqueue styles for our theme options page.
 *
 * @since iTheme2 1.2-wpcom
 *
 */
function itheme2_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style( 'itheme2-theme-options', get_template_directory_uri() . '/inc/theme-options.css', false, '2011-04-28' );
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'itheme2_admin_enqueue_scripts' );

/**
 * Register the form setting for our itheme2_options array.
 *
 * This function is attached to the admin_init action hook.
 *
 * This call to register_setting() registers a validation callback, itheme2_theme_options_validate(),
 * which is used when the option is saved, to ensure that our option values are complete, properly
 * formatted, and safe.
 *
 * We also use this function to add our theme option if it doesn't already exist.
 *
 * @since iTheme2 1.0
 */
function itheme2_theme_options_init() {

	// If we have no options in the database, let's add them now.
	if ( false === itheme2_get_theme_options() )
		add_option( 'itheme2_theme_options', itheme2_get_default_theme_options() );

	register_setting(
		'itheme2_options',       // Options group, see settings_fields() call in theme_options_render_page()
		'itheme2_theme_options', // Database option, see itheme2_get_theme_options()
		'itheme2_theme_options_validate' // The sanitization callback, see itheme2_theme_options_validate()
	);

	// Register our settings field group
	add_settings_section(
		'general', // Unique identifier for the settings section
		'', // Section title (we don't want one)
		'__return_false', // Section callback (we don't want anything)
		'theme_options' // Menu slug, used to uniquely identify the page; see itheme2_theme_options_add_page()
	);

	// Register our individual settings fields
	add_settings_field(
		'color_scheme',  // Unique identifier for the field for this section
		__( 'Color Scheme', 'itheme2' ), // Setting field label
		'itheme2_settings_field_color_scheme', // Function that renders the settings field
		'theme_options', // Menu slug, used to uniquely identify the page; see itheme2_theme_options_add_page()
		'general' // Settings section. Same as the first argument in the add_settings_section() above
	);
}
add_action( 'admin_init', 'itheme2_theme_options_init' );

/**
 * Change the capability required to save the 'itheme2_options' options group.
 *
 * @see itheme2_theme_options_init() First parameter to register_setting() is the name of the options group.
 * @see itheme2_theme_options_add_page() The edit_theme_options capability is used for viewing the page.
 *
 * By default, the options groups for all registered settings require the manage_options capability.
 * This filter is required to change our theme options page to edit_theme_options instead.
 * By default, only administrators have either of these capabilities, but the desire here is
 * to allow for finer-grained control for roles and users.
 *
 * @param string $capability The capability used for the page, which is manage_options by default.
 * @return string The capability to actually use.
 */
function itheme2_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_itheme2_options', 'itheme2_option_page_capability' );

/**
 * Add our theme options page to the admin menu, including some help documentation.
 *
 * This function is attached to the admin_menu action hook.
 *
 * @since iTheme2 1.0
 */
function itheme2_theme_options_add_page() {
	$theme_page = add_theme_page(
		__( 'Theme Options', 'itheme2' ),   // Name of page
		__( 'Theme Options', 'itheme2' ),   // Label in menu
		'edit_theme_options',                    // Capability required
		'theme_options',                         // Menu slug, used to uniquely identify the page
		'itheme2_theme_options_render_page' // Function that renders the options page
	);

	if ( ! $theme_page )
		return;
}
add_action( 'admin_menu', 'itheme2_theme_options_add_page' );

/**
 * Returns an array of color schemes registered for iTheme2.
 *
 * @since iTheme2 1.0
 */
function itheme2_color_schemes() {
	$color_scheme_options = array(
		'white' => array(
			'value' => 'white',
			'label' => __( 'White', 'itheme2' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/white.png',
		),
		'black' => array(
			'value' => 'black',
			'label' => __( 'Black', 'itheme2' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/black.png',
		),
		'gray' => array(
			'value' => 'gray',
			'label' => __( 'Gray', 'itheme2' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/gray.png',
		),
	);

	return apply_filters( 'itheme2_color_schemes', $color_scheme_options );
}

/**
 * Returns the default options for iTheme2.
 *
 * @since iTheme2 1.0
 */
function itheme2_get_default_theme_options() {
	$default_theme_options = array(
		'color_scheme' => 'white',
	);

	return apply_filters( 'itheme2_default_theme_options', $default_theme_options );
}

/**
 * Returns the options array for iTheme2.
 *
 * @since iTheme2 1.0
 */
function itheme2_get_theme_options() {
	return get_option( 'itheme2_theme_options', itheme2_get_default_theme_options() );
}

/**
 * Renders the Color Scheme setting field.
 *
 * @since iTheme2 1.3
 */
function itheme2_settings_field_color_scheme() {
	$options = itheme2_get_theme_options();

	foreach ( itheme2_color_schemes() as $scheme ) {
	?>
	<div class="layout image-radio-option color-scheme">
	<label class="description">
		<input type="radio" name="itheme2_theme_options[color_scheme]" value="<?php echo esc_attr( $scheme['value'] ); ?>" <?php checked( $options['color_scheme'], $scheme['value'] ); ?> />
		<input type="hidden" id="default-color-<?php echo esc_attr( $scheme['value'] ); ?>" value="<?php echo esc_attr( $scheme['default_link_color'] ); ?>" />
		<span>
			<img src="<?php echo esc_url( $scheme['thumbnail'] ); ?>" width="136" height="122" alt="" />
			<?php echo $scheme['label']; ?>
		</span>
	</label>
	</div>
	<?php
	}
}

/**
 * Returns the options array for iTheme2.
 *
 * @since iTheme2 1.2
 */
function itheme2_theme_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php printf( __( '%s Theme Options', 'itheme2' ), get_current_theme() ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'itheme2_options' );
				do_settings_sections( 'theme_options' );
				submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate form input. Accepts an array, return a sanitized array.
 *
 * @see itheme2_theme_options_init()
 * @todo set up Reset Options action
 *
 * @since iTheme2 1.0
 */
function itheme2_theme_options_validate( $input ) {
	$output = $defaults = itheme2_get_default_theme_options();

	// Color scheme must be in our array of color scheme options
	if ( isset( $input['color_scheme'] ) && array_key_exists( $input['color_scheme'], itheme2_color_schemes() ) )
		$output['color_scheme'] = $input['color_scheme'];

	return apply_filters( 'itheme2_theme_options_validate', $output, $input, $defaults );
}

/**
 * Enqueue the styles for the current color scheme.
 *
 * @since iTheme2 1.0
 */
function itheme2_enqueue_color_scheme() {
	$options = itheme2_get_theme_options();
	$color_scheme = $options['color_scheme'];

	if ( 'white' != $color_scheme )
		wp_enqueue_style( $color_scheme, get_template_directory_uri() . '/skins/' . $color_scheme . '/' . $color_scheme . '.css', array(), null );

	do_action( 'itheme2_enqueue_color_scheme', $color_scheme );
}
add_action( 'wp_enqueue_scripts', 'itheme2_enqueue_color_scheme' );
