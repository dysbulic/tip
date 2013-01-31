<?php
/**
 * Adventure Journal Theme Options
 *
 * @package Adventure_Journal
 * @since Adventure Journal 2.0
 */

/**
 * Properly enqueue styles and scripts for our theme options page.
 *
 * This function is attached to the admin_enqueue_scripts action hook.
 *
 * @since Adventure Journal 2.0
 *
 */
function adventurejournal_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style( 'adventurejournal-theme-options', get_template_directory_uri() . '//inc/theme-options.css', false, '20111121' );
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'adventurejournal_admin_enqueue_scripts' );

/**
 * Register the form setting for our adventurejournal_options array.
 *
 * This function is attached to the admin_init action hook.
 *
 * This call to register_setting() registers a validation callback, adventurejournal_theme_options_validate(),
 * which is used when the option is saved, to ensure that our option values are complete, properly
 * formatted, and safe.
 *
 * We also use this function to add our theme option if it doesn't already exist.
 *
 * @since Adventure Journal 2.0
 */
function adventurejournal_theme_options_init() {

	// If we have no options in the database, let's add them now.
	if ( false === adventurejournal_get_theme_options() )
		add_option( 'adventurejournal_theme_options', adventurejournal_get_default_theme_options() );

	register_setting(
		'adventurejournal_options',       // Options group, see settings_fields() call in theme_options_render_page()
		'adventurejournal_theme_options', // Database option, see adventurejournal_get_theme_options()
		'adventurejournal_theme_options_validate' // The sanitization callback, see adventurejournal_theme_options_validate()
	);

	// Register our settings field group
	add_settings_section(
		'general', // Unique identifier for the settings section
		'', // Section title (we don't want one)
		'__return_false', // Section callback (we don't want anything)
		'theme_options' // Menu slug, used to uniquely identify the page; see adventurejournal_theme_options_add_page()
	);

	add_settings_field( 'layout', __( 'Layout', 'adventurejournal' ), 'adventurejournal_settings_field_layout', 'theme_options', 'general' );
}
add_action( 'admin_init', 'adventurejournal_theme_options_init' );

/**
 * Change the capability required to save the 'adventurejournal_options' options group.
 *
 * @see adventurejournal_theme_options_init() First parameter to register_setting() is the name of the options group.
 * @see adventurejournal_theme_options_add_page() The edit_theme_options capability is used for viewing the page.
 *
 * By default, the options groups for all registered settings require the manage_options capability.
 * This filter is required to change our theme options page to edit_theme_options instead.
 * By default, only administrators have either of these capabilities, but the desire here is
 * to allow for finer-grained control for roles and users.
 *
 * @param string $capability The capability used for the page, which is manage_options by default.
 * @return string The capability to actually use.
 */
function adventurejournal_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_adventurejournal_options', 'adventurejournal_option_page_capability' );

/**
 * Add our theme options page to the admin menu, including some help documentation.
 *
 * This function is attached to the admin_menu action hook.
 *
 * @since Adventure Journal 2.0
 */
function adventurejournal_theme_options_add_page() {
	$theme_page = add_theme_page(
		__( 'Theme Options', 'adventurejournal' ),   // Name of page
		__( 'Theme Options', 'adventurejournal' ),   // Label in menu
		'edit_theme_options',                    // Capability required
		'theme_options',                         // Menu slug, used to uniquely identify the page
		'adventurejournal_theme_options_render_page' // Function that renders the options page
	);

	if ( ! $theme_page )
		return;

	$help = '<p>' . __( 'Some themes provide customization options that are grouped together on a Theme Options screen. If you change themes, options may change or disappear, as they are theme-specific. Your current theme, Adventure Journal, provides the following Theme Options:', 'adventurejournal' ) . '</p>' .
			'<ol>' .
				'<li>' . __( '<strong>Default Layout</strong>: You can choose to have one-column, two-column or three-column layout.', 'adventurejournal' ) . '</li>' .
			'</ol>' .
			'<p>' . __( 'Remember to click "Save Changes" to save any changes you have made to the theme options.', 'adventurejournal' ) . '</p>';

	add_contextual_help( $theme_page, $help );
}
add_action( 'admin_menu', 'adventurejournal_theme_options_add_page' );

/**
 * Returns an array of layout options registered for Adventure Journal.
 *
 * @since Adventure Journal 2.0
 */
function adventurejournal_layouts() {
	$layout_options = array(
		'col-1' => array(
			'value' => 'col-1',
			'label' => __( 'One-column', 'adventurejournal' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/content.png',
		),
		'col-2-left' => array(
			'value' => 'col-2-left',
			'label' => __( 'Two column, content left', 'adventurejournal' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/content-sidebar.png',
		),
		'col-2-right' => array(
			'value' => 'col-2-right',
			'label' => __( 'Two column, content right', 'adventurejournal' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/sidebar-content.png',
		),
		'col-3' => array(
			'value' => 'col-3',
			'label' => __( 'Three column, content midle', 'adventurejournal' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/sidebar-content-sidebar.png',
		),
		'col-3-left' => array(
			'value' => 'col-3-left',
			'label' => __( 'Three column, content left', 'adventurejournal' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/content-sidebar-sidebar.png',
		),
	);

	return apply_filters( 'adventurejournal_layouts', $layout_options );
}

/**
 * Renders the Layout setting field.
 *
 * @since Adventure Journal 2.0
 */
function adventurejournal_settings_field_layout() {
	$options = adventurejournal_get_theme_options();
	foreach ( adventurejournal_layouts() as $layout ) {
		?>
		<div class="layout image-radio-option theme-layout">
		<label class="description">
			<input type="radio" name="adventurejournal_theme_options[theme_layout]" value="<?php echo esc_attr( $layout['value'] ); ?>" <?php checked( $options['theme_layout'], $layout['value'] ); ?> />
			<span>
				<img src="<?php echo esc_url( $layout['thumbnail'] ); ?>" width="136" height="122" alt="" />
				<?php echo $layout['label']; ?>
			</span>
		</label>
		</div>
		<?php
	}
}

/**
 * Returns the options array for Adventure Journal.
 *
 * @since Adventure Journal 2.0
 */
function adventurejournal_theme_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php printf( __( '%s Theme Options', 'adventurejournal' ), get_current_theme() ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'adventurejournal_options' );
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
 * @see adventurejournal_theme_options_init()
 *
 * @since Adventure Journal 2.0
 */
function adventurejournal_theme_options_validate( $input ) {
	$output = $defaults = adventurejournal_get_default_theme_options();

	// Theme layout must be in our array of theme layout options
	if ( isset( $input['theme_layout'] ) && array_key_exists( $input['theme_layout'], adventurejournal_layouts() ) )
		$output['theme_layout'] = $input['theme_layout'];

	return apply_filters( 'adventurejournal_theme_options_validate', $output, $input, $defaults );
}

/**
 * Adds Adventure Journal layout classes to the array of body classes.
 *
 * @since Adventure Journal 2.0
 */
function adventurejournal_layout_classes( $existing_classes ) {
	$options = adventurejournal_get_theme_options();
	$current_layout = $options['theme_layout'];
	$allowed_layouts = array('col-1','col-2-left','col-2-right','col-3','col-3-left');

	if ( in_array( $current_layout, $allowed_layouts ) ) {
		// Checks if a page template for layout has been selected
		// Otherwise sets the layout to what has been choosen in the options
		if ( is_page_template( 'template-onecol.php' ) ) {
			$classes = array( 'col-1' );
		} else {
			$classes[] = $current_layout;
		}
	} else {
		$classes = array( 'col-1' );
	}

	$classes = apply_filters( 'adventurejournal_layout_classes', $classes, $current_layout );

	return array_merge( $existing_classes, $classes );
}
add_filter( 'body_class', 'adventurejournal_layout_classes' );