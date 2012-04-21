<?php
/**
 * @package WordPress
 * @subpackage Brand New Day
 */

/**
 * Properly enqueue styles and scripts for our theme options page.
 */
function brand_new_day_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style( 'bnd-theme-options', get_template_directory_uri() . '/admin/theme-options.css', false, '2011-04-28' );
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'brand_new_day_admin_enqueue_scripts' );

/**
 * Register the form setting for our brand_new_day_options array.
 */
function brand_new_day_theme_options_init() {

	// If we have no options in the database, let's add them now.
	if ( false === brand_new_day_get_theme_options() )
		add_option( 'brand_new_day_theme_options', brand_new_day_get_default_theme_options() );

	register_setting(
		'brand_new_day_options',       // Options group, see settings_fields() call in brand_new_day_theme_options_render_page()
		'brand_new_day_theme_options', // Database option, see brand_new_day_get_theme_options()
		'brand_new_day_theme_options_validate' // The sanitization callback, see brand_new_day_theme_options_validate()
	);

	// Register our settings field group
	add_settings_section( 
		'general', // Unique identifier for the settings section
		'', // Section title (we don't want one)
		'__return_false', // Section callback (we don't want anything) 
		'theme_options' // Menu slug, used to uniquely identify the page; see brand_new_day_theme_options_add_page()
	);

	// Register our individual settings fields
	add_settings_field( 'theme_style', __( 'Theme Style', 'bnd' ), 'brand_new_day_settings_field_theme_style', 'theme_options', 'general' );
	add_settings_field( 'sidebar_options', __( 'Sidebar Options', 'bnd' ), 'brand_new_day_settings_field_sidebar_options', 'theme_options', 'general' );
	add_settings_field( 'remove_search', __( 'Remove Search', 'bnd' ), 'brand_new_day_settings_field_remove_search', 'theme_options', 'general' );
	add_settings_field( 'simple_blog', __( 'Simple Blog Mode', 'bnd' ), 'brand_new_day_settings_field_simple_blog', 'theme_options', 'general' );
}
add_action( 'admin_init', 'brand_new_day_theme_options_init' );

/**
 * Change the capability required to save the 'brand_new_day_options' options group.
 */
function brand_new_day_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_brand_new_day_options', 'brand_new_day_option_page_capability' );

/**
 * Add our theme options page to the admin menu, including some help documentation.
 */
function brand_new_day_theme_options_add_page() {
	$theme_page = add_theme_page(
		__( 'Theme Options', 'bnd' ),   // Name of page
		__( 'Theme Options', 'bnd' ),   // Label in menu
		'edit_theme_options',                    // Capability required
		'theme_options',                         // Menu slug, used to uniquely identify the page
		'brand_new_day_theme_options_render_page' // Function that renders the options page
	);

	if ( ! $theme_page )
		return;
}
add_action( 'admin_menu', 'brand_new_day_theme_options_add_page' );

/**
 * Returns an array of theme styles registered for Brand New Day.
 */
function brand_new_day_theme_styles() {
	$theme_style_options = array(
		'daylight' => array(
			'value' => 'daylight',
			'label' => __( 'Daylight', 'bnd' ),
			'thumbnail' => get_template_directory_uri() . '/admin/images/daylight.png',
		),
		'nightlight' => array(
			'value' => 'nightlight',
			'label' => __( 'Nightlight', 'bnd' ),
			'thumbnail' => get_template_directory_uri() . '/admin/images/nightlight.png',
		),
		'winterlight' => array(
			'value' => 'winterlight',
			'label' => __( 'Winterlight', 'bnd' ),
			'thumbnail' => get_template_directory_uri() . '/admin/images/winterlight.png',
		),
		'autumnlight' => array(
			'value' => 'autumnlight',
			'label' => __( 'Autumnlight', 'bnd' ),
			'thumbnail' => get_template_directory_uri() . '/admin/images/autumnlight.png',
		),
	);
	
	return apply_filters( 'brand_new_day_theme_styles', $theme_style_options );
}

/**
 * Returns an array of sidebar options registered for Brand New Day.
 */
function brand_new_day_sidebar_options() {
	$sidebar_options = array(
		'right-sidebar' => array(
			'value' => 'right-sidebar',
			'label' => __( 'Right sidebar', 'bnd' ),
		),
		'left-sidebar' => array(
			'value' => 'left-sidebar',
			'label' => __( 'Left sidebar', 'bnd' ),
		),
		'no-sidebar' => array(
			'value' => 'no-sidebar',
			'label' => __( 'No sidebar', 'bnd' ),
		),
	);
	
	return apply_filters( 'brand_new_day_sidebar_options', $sidebar_options );
}

/**
 * Returns the default options for Brand New Day.
 */
function brand_new_day_get_default_theme_options() {
	$default_theme_options = array(
		'theme_style' => 'daylight',
		'sidebar_options' => 'right-sidebar',
		'remove_search' => 'off',
		'simple_blog' => 'off',
	);

	return apply_filters( 'brand_new_day_default_theme_options', $default_theme_options );
}

/**
 * Returns the options array for Twenty Eleven.
 *
 * @since Twenty Eleven 1.0
 */
function brand_new_day_get_theme_options() {
	return get_option( 'brand_new_day_theme_options', brand_new_day_get_default_theme_options() );
}

/**
 * Renders the Theme Style setting field.
 */
function brand_new_day_settings_field_theme_style() {
	$options = brand_new_day_get_theme_options();

	foreach ( brand_new_day_theme_styles() as $style ) {
	?>
	<div class="layout image-radio-option theme-style">
	<label class="description">
		<input type="radio" name="brand_new_day_theme_options[theme_style]" value="<?php echo esc_attr( $style['value'] ); ?>" <?php checked( $options['theme_style'], $style['value'] ); ?> />
		<span>
			<img src="<?php echo esc_url( $style['thumbnail'] ); ?>" width="240" height="181" alt="" />
			<?php echo $style['label']; ?>
		</span>
	</label>
	</div>
	<?php
	}
}

/**
 * Renders the Sidebar Options setting field.
 */
function brand_new_day_settings_field_sidebar_options() {
	$options = brand_new_day_get_theme_options();

	foreach ( brand_new_day_sidebar_options() as $sidebar_option ) {
	?>
	<div class="layout sidebar-options">
	<label class="description">
		<input type="radio" name="brand_new_day_theme_options[sidebar_options]" value="<?php echo esc_attr( $sidebar_option['value'] ); ?>" <?php checked( $options['sidebar_options'], $sidebar_option['value'] ); ?> />
		<span>
			<?php echo $sidebar_option['label']; ?>
		</span>
	</label>
	</div>
	<?php
	}
}

/**
 * Renders the Search bar option setting field.
 */
function brand_new_day_settings_field_remove_search() {
	$options = brand_new_day_get_theme_options();

	?>
	<div class="layout remove-search">
	<label class="description">
		<input id="brand_new_day_theme_options[remove_search]" name="brand_new_day_theme_options[remove_search]" type="checkbox" <?php checked( 'on', $options['remove_search'] ); ?> />
		<span>
			<?php _e( 'Remove search bar from header', 'brand-new-day' ); ; ?>
		</span>
	</label>
	</div>
	<?php
}

/**
 * Renders the Simple Blog option setting field.
 */
function brand_new_day_settings_field_simple_blog() {
	$options = brand_new_day_get_theme_options();

	?>
	<div class="layout simple-blog">
	<label class="description">
		<input id="brand_new_day_theme_options[simple_blog]" name="brand_new_day_theme_options[simple_blog]" type="checkbox" <?php checked( 'on', $options['simple_blog'] ); ?> />
		<span>
			<?php _e( 'Remove the sidebar, the search bar, and narrow the content column', 'brand-new-day' ); ; ?>
		</span>
	</label>
	</div>
	<?php
}

/**
 * Returns the options array for Brand New Day.
 */
function brand_new_day_theme_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php printf( __( '%s Theme Options', 'bnd' ), get_current_theme() ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'brand_new_day_options' );
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
 * @see brand_new_day_theme_options_init()
 * @todo set up Reset Options action
 *
 * @since Twenty Eleven 1.0
 */
function brand_new_day_theme_options_validate( $input ) {
	$output = $defaults = brand_new_day_get_default_theme_options();

	// Theme Style must be in our array of theme style options
	if ( isset( $input['theme_style'] ) && array_key_exists( $input['theme_style'], brand_new_day_theme_styles() ) )
		$output['theme_style'] = $input['theme_style'];

	// Sidebar layout must be in our array of sidebar layout options
	if ( isset( $input['sidebar_options'] ) && array_key_exists( $input['sidebar_options'], brand_new_day_sidebar_options() ) )
		$output['sidebar_options'] = $input['sidebar_options'];
		
	// The remove search option should either be on or off
	if ( ! isset( $input['remove_search'] ) )
		$input['remove_search'] = 'off';
	$output['remove_search'] = ( $input['remove_search'] == 'on' ? 'on' : 'off' );

	// And the simple blog option should either be on or off
	if ( ! isset( $input['simple_blog'] ) )
		$input['simple_blog'] = 'off';
	$output['simple_blog'] = ( $input['simple_blog'] == 'on' ? 'on' : 'off' );

	return apply_filters( 'brand_new_day_theme_options_validate', $output, $input, $defaults );
}
