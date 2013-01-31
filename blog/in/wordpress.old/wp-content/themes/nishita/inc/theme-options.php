<?php
/**
 * Nishita Theme Options
 *
 * @package WordPress
 * @subpackage Nishita
 */

/**
 * Properly enqueue styles and scripts for our theme options page.
 *
 * This function is attached to the admin_enqueue_scripts action hook.
 *
 */
function nishita_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style( 'nishita-theme-options', get_template_directory_uri() . '/inc/theme-options.css', false, '2011-09-09' );
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'nishita_admin_enqueue_scripts' );

/**
 * Register the form setting for our nishita_options array.
 *
 * This function is attached to the admin_init action hook.
 *
 * This call to register_setting() registers a validation callback, nishita_theme_options_validate(),
 * which is used when the option is saved, to ensure that our option values are complete, properly
 * formatted, and safe.
 *
 * We also use this function to add our theme option if it doesn't already exist.
 *
 */
function nishita_theme_options_init() {

	// If we have no options in the database, let's add them now.
	if ( false === nishita_get_theme_options() )
		add_option( 'nishita_theme_options', nishita_get_default_theme_options() );

	register_setting(
		'nishita_options', // Options group, see settings_fields() call in theme_options_render_page()
		'nishita_theme_options', // Database option, see nishita_get_theme_options()
		'nishita_theme_options_validate' // The sanitization callback, see nishita_theme_options_validate()
	);
}
add_action( 'admin_init', 'nishita_theme_options_init' );

/**
 * Change the capability required to save the 'nishita_options' options group.
 *
 * @see nishita_theme_options_init() First parameter to register_setting() is the name of the options group.
 * @see nishita_theme_options_add_page() The edit_theme_options capability is used for viewing the page.
 *
 * By default, the options groups for all registered settings require the manage_options capability.
 * This filter is required to change our theme options page to edit_theme_options instead.
 * By default, only administrators have either of these capabilities, but the desire here is
 * to allow for finer-grained control for roles and users.
 *
 * @param string $capability The capability used for the page, which is manage_options by default.
 * @return string The capability to actually use.
 */
function nishita_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_nishita_options', 'nishita_option_page_capability' );

/**
 * Add our theme options page to the admin menu, including some help documentation.
 *
 * This function is attached to the admin_menu action hook.
 *
 */
function nishita_theme_options_add_page() {
	$theme_page = add_theme_page(
		__( 'Theme Options', 'nishita' ),   // Name of page
		__( 'Theme Options', 'nishita' ),   // Label in menu
		'edit_theme_options',               // Capability required
		'theme_options',                    // Menu slug, used to uniquely identify the page
		'nishita_theme_options_render_page' // Function that renders the options page
	);

	if ( ! $theme_page )
		return;

	$help = '<p>' . __( 'Some themes provide customization options that are grouped together on a Theme Options screen. If you change themes, options may change or disappear, as they are theme-specific. Your current theme, Nishita, provides the following Theme Options:', 'nishita' ) . '</p>' .
			'<ol>' .
				'<li>' . __( '<strong>Color Scheme</strong>: You can choose a color palette of "Light" (light background with dark text) or "Dark" (dark background with light text) for your site.', 'nishita' ) . '</li>' .
				'<li>' . __( '<strong>Default Layout</strong>: You can choose if you want your site&#8217;s default width to be 1024 pixels or 768 pixels.', 'nishita' ) . '</li>' .
			'</ol>' .
			'<p>' . __( 'Remember to click "Save Changes" to save any changes you have made to the theme options.', 'nishita' ) . '</p>' .
			'<p><strong>' . __( 'For more information:', 'nishita' ) . '</strong></p>' .
			'<p>' . __( '<a href="http://codex.wordpress.org/Appearance_Theme_Options_Screen" target="_blank">Documentation on Theme Options</a>', 'nishita' ) . '</p>' .
			'<p>' . __( '<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>', 'nishita' ) . '</p>';

	add_contextual_help( $theme_page, $help );
}
add_action( 'admin_menu', 'nishita_theme_options_add_page' );

/**
 * Returns an array of color schemes registered for Nishita.
 *
 */
function nishita_color_schemes() {
	$color_scheme_options = array(
		'light' => array(
			'value' => 'light',
			'label' => __( '<strong>Light:</strong> A minimal design that allows for the crisp presentation of both photography and content.', 'nishita' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/light.png'
		),
		'dark' => array(
			'value' => 'dark',
			'label' => __( '<strong>Dark:</strong> A dark, minimal color palette that encourages users to focus on photographs.', 'nishita' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/dark.png'
		),
	);

	return apply_filters( 'nishita_color_schemes', $color_scheme_options );
}

/**
 * Returns an array of layout options registered for Nishita.
 *
 */
function nishita_layouts() {
	$layout_options = array(
		'photoblog' => array(
			'value' => 'photoblog',
			'label' => __( '<strong>Photoblog:</strong> A 1024-pixels wide layout with optional sidebar that allows for the presentation of beautiful, large images.', 'nishita' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/photoblog.png',
		),
		'blog' => array(
			'value' => 'blog',
			'label' => __( '<strong>Blog:</strong> A slimmer, 768-pixels wide layout with optional sidebar that offers better readability for interesting blog posts.', 'nishita' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/blog.png',
		)
	);

	return apply_filters( 'nishita_layouts', $layout_options );
}

/**
 * Returns the default options for Nishita.
 *
 */
function nishita_get_default_theme_options() {
	$default_theme_options = array(
		'color_scheme' => 'dark',
		'theme_layout' => 'photoblog',
	);

	return apply_filters( 'nishita_default_theme_options', $default_theme_options );
}

/**
 * Returns the options array for Nishita.
 *
 */
function nishita_get_theme_options() {
	return get_option( 'nishita_theme_options', nishita_get_default_theme_options() );
}

/**
 * Returns the options array for Nishita.
 *
 */
function nishita_theme_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php printf( __( '%s Theme Options', 'nishita' ), get_current_theme() ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'nishita_options' );
				$options = nishita_get_theme_options();
				$default_options = nishita_get_default_theme_options();
			?>

			<table class="form-table">

				<tr valign="top" class="image-radio-option color-scheme"><th scope="row"><?php _e( 'Color Scheme', 'nishita' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Color Scheme', 'nishita' ); ?></span></legend>
						<?php
							foreach ( nishita_color_schemes() as $scheme ) {
								?>
								<div class="layout">
								<label class="description">
									<input type="radio" name="nishita_theme_options[color_scheme]" value="<?php echo esc_attr( $scheme['value'] ); ?>" <?php checked( $options['color_scheme'], $scheme['value'] ); ?> />
									<span>
										<img src="<?php echo esc_url( $scheme['thumbnail'] ); ?>" width="136" height="122" alt="" />
										<?php echo $scheme['label']; ?>
									</span>
								</label>
								</div>
								<?php
							}
						?>
						</fieldset>
					</td>
				</tr>

				<tr valign="top" class="image-radio-option theme-layout"><th scope="row"><?php _e( 'Default Layout', 'nishita' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Color Scheme', 'nishita' ); ?></span></legend>
						<?php
							foreach ( nishita_layouts() as $layout ) {
								?>
								<div class="layout">
								<label class="description">
									<input type="radio" name="nishita_theme_options[theme_layout]" value="<?php echo esc_attr( $layout['value'] ); ?>" <?php checked( $options['theme_layout'], $layout['value'] ); ?> />
									<span>
										<img src="<?php echo esc_url( $layout['thumbnail'] ); ?>" width="136" height="122" alt="" />
										<?php echo $layout['label']; ?>
									</span>
								</label>
								</div>
								<?php
							}
						?>
						</fieldset>
					</td>
				</tr>
			</table>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate form input. Accepts an array, return a sanitized array.
 *
 * @see nishita_theme_options_init()
 * @todo set up Reset Options action
 *
 */
function nishita_theme_options_validate( $input ) {
	$output = $defaults = nishita_get_default_theme_options();

	// Color scheme must be in our array of color scheme options
	if ( isset( $input['color_scheme'] ) && array_key_exists( $input['color_scheme'], nishita_color_schemes() ) )
		$output['color_scheme'] = $input['color_scheme'];

	// Theme layout must be in our array of theme layout options
	if ( isset( $input['theme_layout'] ) && array_key_exists( $input['theme_layout'], nishita_layouts() ) )
		$output['theme_layout'] = $input['theme_layout'];

	return apply_filters( 'nishita_theme_options_validate', $output, $input, $defaults );
}

/**
 * Enqueue the styles for the current color scheme.
 *
 */
function nishita_enqueue_color_scheme() {
	$options = nishita_get_theme_options();
	$color_scheme = $options['color_scheme'];

	if ( 'dark' == $color_scheme )
		wp_enqueue_style( 'dark', get_template_directory_uri() . '/colors/dark.css', array(), null );

	do_action( 'nishita_enqueue_color_scheme', $color_scheme );
}
add_action( 'wp_enqueue_scripts', 'nishita_enqueue_color_scheme' );

/**
 * Adds Nishita layout classes to the array of body classes.
 *
 */
function nishita_layout_classes( $existing_classes ) {
	$options = nishita_get_theme_options();
	$current_layout = $options['theme_layout'];

	if ( in_array( $current_layout, array( 'photoblog' ) ) )
		$classes = array( 'layout-photoblog' );
	else
		$classes = array( 'layout-blog' );

	$classes = apply_filters( 'nishita_layout_classes', $classes, $current_layout );

	return array_merge( $existing_classes, $classes );
}
add_filter( 'body_class', 'nishita_layout_classes' );