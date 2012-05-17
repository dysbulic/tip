<?php
/**
 * Matala Theme Options
 *
 * @package WordPress
 * @subpackage Matala
 */

/**
 * Register the form setting for our matala_options array.
 *
 * This function is attached to the admin_init action hook.
 *
 * This call to register_setting() registers a validation callback, matala_theme_options_validate(),
 * which is used when the option is saved, to ensure that our option values are complete, properly
 * formatted, and safe.
 *
 * We also use this function to add our theme option if it doesn't already exist.
 */
function matala_theme_options_init() {

	// If we have no options in the database, let's add them now.
	if ( false === matala_get_theme_options() )
		add_option( 'matala_theme_options', matala_get_default_theme_options() );

	register_setting(
		'matala_options', // Options group, see settings_fields() call in theme_options_render_page()
		'matala_theme_options', // Database option, see matala_get_theme_options()
		'matala_theme_options_validate' // The sanitization callback, see matala_theme_options_validate()
	);
}
add_action( 'admin_init', 'matala_theme_options_init' );

/**
 * Change the capability required to save the 'matala_options' options group.
 *
 * @see matala_theme_options_init() First parameter to register_setting() is the name of the options group.
 * @see matala_theme_options_add_page() The edit_theme_options capability is used for viewing the page.
 *
 * By default, the options groups for all registered settings require the manage_options capability.
 * This filter is required to change our theme options page to edit_theme_options instead.
 * By default, only administrators have either of these capabilities, but the desire here is
 * to allow for finer-grained control for roles and users.
 *
 * @param string $capability The capability used for the page, which is manage_options by default.
 * @return string The capability to actually use.
 */
function matala_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_matala_options', 'matala_option_page_capability' );

/**
 * Add our theme options page to the admin menu, including some help documentation.
 *
 * This function is attached to the admin_menu action hook.
 *
 */
function matala_theme_options_add_page() {
	$theme_page = add_theme_page(
		__( 'Theme Options', 'matala' ), // Name of page
		__( 'Theme Options', 'matala' ), // Label in menu
		'edit_theme_options', // Capability required
		'theme_options', // Menu slug, used to uniquely identify the page
		'theme_options_render_page' // Function that renders the options page
	);

	if ( ! $theme_page )
		return;

	$help = '<p>' . __( 'Some themes provide customization options that are grouped together on a Theme Options screen. If you change themes, options may change or disappear, as they are theme-specific. Your current theme, Matala, provides the following Theme Options:', 'matala' ) . '</p>' .
			'<ol>' .
				'<li>' . __( '<strong>Random Photos Gallery</strong>: You can choose to display a gallery of three random images at the bottom of your single-image pages.', 'matala' ) . '</li>' .
				'<li>' . __( '<strong>Random Photos Gallery Header</strong>: You can enter a custom title for your Random Photos Gallery. This title appears above the gallery and only shows if you are displaying the Random Photos Gallery.', 'matala' ) . '</li>' .
			'</ol>' .
			'<p>' . __( 'Remember to click "Save Changes" to save any changes you have made to the theme options.', 'matala' ) . '</p>' .
			'<p><strong>' . __( 'For more information:', 'matala' ) . '</strong></p>' .
			'<p>' . __( '<a href="http://codex.wordpress.org/Appearance_Theme_Options_Screen" target="_blank">Documentation on Theme Options</a>', 'matala' ) . '</p>' .
			'<p>' . __( '<a href="http://wordpress.com/support/" target="_blank">Support Forums</a>', 'matala' ) . '</p>';

	add_contextual_help( $theme_page, $help );
}
add_action( 'admin_menu', 'matala_theme_options_add_page' );

/**
 * Returns the default options for Matala.
 *
 */
function matala_get_default_theme_options() {
	$default_theme_options = array(
		'show_random_photos' => false,
		'random_photos_header' => 'Random Photos'
	);

	return apply_filters( 'matala_default_theme_options', $default_theme_options );
}

/**
 * Returns the options array for Matala.
 *
 */
function matala_get_theme_options() {
	return get_option( 'matala_theme_options', matala_get_default_theme_options() );
}

/**
 * Returns the options array for Matala.
 *
 */
function theme_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php printf( __( '%s Theme Options', 'matala' ), get_current_theme() ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'matala_options' );
				$options = matala_get_theme_options();
				$default_options = matala_get_default_theme_options();
			?>

			<table class="form-table">

				<?php
				/**
				 * Show / Hide Random Photos Gallery
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Show Random Photos Gallery?', 'matala' ); ?></th>
					<td>
						<input id="matala_theme_options[show_random_photos]" name="matala_theme_options[show_random_photos]" type="checkbox" value="1" <?php checked( '1', $options['show_random_photos'] ); ?> />
						<label class="description" for="matala_theme_options[show_random_photos]"><?php _e( 'You can find the "Random Photos" gallery at the bottom of single-image pages. It will display three random photos that you have added to your posts.', 'matala' ); ?></label>
					</td>
				</tr>

				<?php
				/**
				 * Random Photos Gallery Header
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Random Photos Gallery header:', 'matala' ); ?></th>
					<td>
						<input id="matala_theme_options[random_photos_header]" class="regular-text" type="text" name="matala_theme_options[random_photos_header]" value="<?php esc_attr_e( $options['random_photos_header'] ); ?>" />
						<label class="description" for="matala_theme_options[random_photos_header]"><?php _e( 'Only applies if you are showing the random photos gallery. (Example: Random Photos.)', 'matala' ); ?></label>
					</td>
				</tr>

			</table>

			<p class="submit">
				<?php submit_button( __( 'Save Options', 'matala' ), 'primary', 'submit', false ); ?>
			</p>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate form input. Accepts an array, return a sanitized array.
 *
 * @see matala_theme_options_init()
 *
 */
function matala_theme_options_validate( $input ) {
	$output = $defaults = matala_get_default_theme_options();

	// Our checkbox value must be either 0 or 1
	if ( ! isset( $input['show_random_photos'] ) )
		$output['show_random_photos'] = null;
		$output['show_random_photos'] = ( $input['show_random_photos'] == 1 ? 1 : 0 );

	// Our text option must be safe text with no HTML tags
	$output['random_photos_header'] = wp_filter_nohtml_kses( $input['random_photos_header'] );

	// Reset to default options
	if ( ! empty( $input['reset'] ) ) {
		$output = $defaults = matala_get_default_theme_options();
		foreach ( $ouput as $field => $value ) {
			if ( isset( $defaults[$field] ) )
				$output[$field] = $defaults[$field];
			else
				unset( $output[$field] );
		}
	}
	return apply_filters( 'matala_theme_options_validate', $output, $input, $defaults );
}