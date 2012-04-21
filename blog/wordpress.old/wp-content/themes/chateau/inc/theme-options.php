<?php
/**
 * Chateau Theme Options
 *
 * @package WordPress
 * @subpackage Chateau
 */

/**
 * Properly enqueue styles and scripts for our theme options page.
 *
 * This function is attached to the admin_enqueue_scripts action hook.
 */
function chateau_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style( 'chateau-theme-options', get_template_directory_uri() . '/inc/theme-options.css', false, '2011-06-10' );
	wp_enqueue_script( 'chateau-theme-options', get_template_directory_uri() . '/inc/theme-options.js', array( 'farbtastic' ), '2011-06-10' );
	wp_enqueue_style( 'farbtastic' );
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'chateau_admin_enqueue_scripts' );

/**
 * Register the form setting for our chateau_options array.
 *
 * This function is attached to the admin_init action hook.
 *
 * This call to register_setting() registers a validation callback, chateau_theme_options_validate(),
 * which is used when the option is saved, to ensure that our option values are complete, properly
 * formatted, and safe.
 *
 * We also use this function to add our theme option if it doesn't already exist.
 */
function chateau_theme_options_init() {

	// If we have no options in the database, let's add them now.
	if ( false === chateau_get_theme_options() )
		add_option( 'chateau_theme_options', chateau_get_default_theme_options() );

	register_setting(
		'chateau_options', // Options group, see settings_fields() call in theme_options_render_page()
		'chateau_theme_options', // Database option, see chateau_get_theme_options()
		'chateau_theme_options_validate' // The sanitization callback, see chateau_theme_options_validate()
	);
}
add_action( 'admin_init', 'chateau_theme_options_init' );

/**
 * Change the capability required to save the 'chateau_options' options group.
 *
 * @see chateau_theme_options_init() First parameter to register_setting() is the name of the options group.
 * @see chateau_theme_options_add_page() The edit_theme_options capability is used for viewing the page.
 *
 * By default, the options groups for all registered settings require the manage_options capability.
 * This filter is required to change our theme options page to edit_theme_options instead.
 * By default, only administrators have either of these capabilities, but the desire here is
 * to allow for finer-grained control for roles and users.
 *
 * @param string $capability The capability used for the page, which is manage_options by default.
 * @return string The capability to actually use.
 */
function chateau_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_chateau_options', 'chateau_option_page_capability' );

/**
 * Add our theme options page to the admin menu, including some help documentation.
 *
 * This function is attached to the admin_menu action hook.
 *
 */
function chateau_theme_options_add_page() {
	$theme_page = add_theme_page(
		__( 'Theme Options', 'chateau' ), // Name of page
		__( 'Theme Options', 'chateau' ), // Label in menu
		'edit_theme_options', // Capability required
		'theme_options', // Menu slug, used to uniquely identify the page
		'theme_options_render_page' // Function that renders the options page
	);

	if ( ! $theme_page )
		return;

	$help = '<p>' . __( 'Some themes provide customization options that are grouped together on a Theme Options screen. If you change themes, options may change or disappear, as they are theme-specific. Your current theme, Chateau, provides the following Theme Options:', 'chateau' ) . '</p>' .
			'<ol>' .
				'<li>' . __( '<strong>Color Scheme</strong>: You can choose a color palette of "Light" (light background with dark text) or "Dark" (dark background with light text) for your site.', 'chateau' ) . '</li>' .
				'<li>' . __( '<strong>Accent Color</strong>: You can choose the color used for text links and the top line on your site. You can enter the HTML color or hex code, or you can choose visually by clicking the "Select a Color" button to pick from a color wheel.', 'chateau' ) . '</li>' .
			'</ol>' .
			'<p>' . __( 'Remember to click "Save Changes" to save any changes you have made to the theme options.', 'chateau' ) . '</p>' .
			'<p><strong>' . __( 'For more information:', 'chateau' ) . '</strong></p>' .
			'<p>' . __( '<a href="http://codex.wordpress.org/Appearance_Theme_Options_Screen" target="_blank">Documentation on Theme Options</a>', 'chateau' ) . '</p>' .
			'<p>' . __( '<a href="http://wordpress.com/support/" target="_blank">Support Forums</a>', 'chateau' ) . '</p>';

	add_contextual_help( $theme_page, $help );
}
add_action( 'admin_menu', 'chateau_theme_options_add_page' );

/**
 * Returns an array of color schemes registered for Chateau.
 *
 */
function chateau_color_schemes() {
	$color_scheme_options = array(
		'light' => array(
			'value' => 'light',
			'label' => __( 'Light', 'chateau' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/light.png',
			'default_link_color' => '#990000',
		),
		'dark' => array(
			'value' => 'dark',
			'label' => __( 'Dark', 'chateau' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/dark.png',
			'default_link_color' => '#ffffff',
		),
	);

	return apply_filters( 'chateau_color_schemes', $color_scheme_options );
}

/**
 * Returns an array of archive styles registered for Chateau.
 *
 */
function chateau_archive_styles() {
	$archive_style_options = array(
		'detail' => array(
			'value' => 'detail',
			'label' => __( 'Detail', 'chateau' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/detail.png',
		),
		'concise' => array(
			'value' => 'concise',
			'label' => __( 'Concise', 'chateau' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/concise.png',
		),
	);

	return apply_filters( 'chateau_archive_styles', $archive_style_options );
}

/**
 * Returns an array of layouts registered for Chateau.
 *
 */
function chateau_theme_layouts() {
	$theme_layout_options = array(
		'sidebar-content' => array(
			'value' => 'sidebar-content',
			'label' => __( 'Sidebar-Content', 'chateau' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/sidebar-content.png',
		),
		'content-sidebar' => array(
			'value' => 'content-sidebar',
			'label' => __( 'Content-Sidebar', 'chateau' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/content-sidebar.png',
		),
		'content' => array(
			'value' => 'content',
			'label' => __( 'One Column, No Sidebar', 'chateau' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/content.png',
		)
	);

	return apply_filters( 'chateau_theme_layouts', $theme_layout_options );
}

/**
 * Returns the default options for Chateau.
 *
 */
function chateau_get_default_theme_options() {
	$default_theme_options = array(
		'color_scheme' => 'light',
		'link_color'   => chateau_get_default_link_color( 'light' ),
		'archive_style' => 'detail',
		'theme_layout' => 'sidebar-content'
	);

	return apply_filters( 'chateau_default_theme_options', $default_theme_options );
}

/**
 * Returns the default link color for Chateau, based on color scheme.
 *
 * @param $string $color_scheme Color scheme. Defaults to the active color scheme.
 * @return $string Color.
*/
function chateau_get_default_link_color( $color_scheme = null ) {
	if ( null === $color_scheme ) {
		$options = chateau_get_theme_options();
		$color_scheme = $options['color_scheme'];
	}

	$color_schemes = chateau_color_schemes();
	if ( ! isset( $color_schemes[ $color_scheme ] ) )
		return false;

	return $color_schemes[ $color_scheme ]['default_link_color'];
}

/**
 * Returns the options array for Chateau.
 *
 */
function chateau_get_theme_options() {
	return get_option( 'chateau_theme_options', chateau_get_default_theme_options() );
}

/**
 * Returns the options array for Chateau.
 *
 */
function theme_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php printf( __( '%s Theme Options', 'chateau' ), get_current_theme() ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'chateau_options' );
				$options = chateau_get_theme_options();
				$default_options = chateau_get_default_theme_options();
			?>

			<table class="form-table">

				<tr valign="top" class="image-radio-option color-scheme"><th scope="row"><?php _e( 'Color Scheme', 'chateau' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Color Scheme', 'chateau' ); ?></span></legend>
						<?php
							foreach ( chateau_color_schemes() as $scheme ) {
								?>
								<div class="layout">
								<label class="description">
									<input type="radio" name="chateau_theme_options[color_scheme]" value="<?php echo esc_attr( $scheme['value'] ); ?>" <?php checked( $options['color_scheme'], $scheme['value'] ); ?> />
									<input type="hidden" id="default-color-<?php echo esc_attr( $scheme['value'] ); ?>" value="<?php echo esc_attr( $scheme['default_link_color'] ); ?>" />
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

				<tr valign="top"><th scope="row"><?php _e( 'Accent Color', 'chateau' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Accent Color', 'chateau' ); ?></span></legend>
							<input type="text" name="chateau_theme_options[link_color]" id="link-color" value="<?php echo esc_attr( $options['link_color'] ); ?>" />
							<a href="#" class="pickcolor hide-if-no-js" id="link-color-example"></a>
							<input type="button" class="pickcolor button hide-if-no-js" value="<?php esc_attr_e( 'Select a Color', 'chateau' ); ?>" />
							<div id="colorPickerDiv" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
							<br />
							<span><?php printf( __( 'Default color: %s', 'chateau' ), '<span id="default-color">' . chateau_get_default_link_color( $options['color_scheme'] ) . '</span>' ); ?></span>
						</fieldset>
					</td>
				</tr>
				<tr valign="top" class="image-radio-option theme-layout"><th scope="row"><?php _e( 'Layout', 'chateau' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Layout', 'chateau' ); ?></span></legend>
						<?php
							foreach ( chateau_theme_layouts() as $layout ) {
								?>
								<div class="layout">
								<label class="description">
									<input type="radio" name="chateau_theme_options[theme_layout]" value="<?php echo esc_attr( $layout['value'] ); ?>" <?php checked( $options['theme_layout'], $layout['value'] ); ?> />

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
				<tr valign="top" class="image-radio-option archive-style"><th scope="row"><?php _e( 'Archive style', 'chateau' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Archive Style', 'chateau' ); ?></span></legend>
						<?php
							foreach ( chateau_archive_styles() as $archive_style ) {
								?>
								<div class="layout">
								<label class="description">
									<input type="radio" name="chateau_theme_options[archive_style]" value="<?php echo esc_attr( $archive_style['value'] ); ?>" <?php checked( $options['archive_style'], $archive_style['value'] ); ?> />

									<span>
										<img src="<?php echo esc_url( $archive_style['thumbnail'] ); ?>" width="250" height="224" alt="" />
										<?php echo $archive_style['label']; ?>
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

			<p class="submit">
				<?php submit_button( __( 'Save Options', 'chateau' ), 'primary', 'submit', false ); ?>
				<?php submit_button( __( 'Reset Options', 'chateau' ), 'secondary', 'chateau_theme_options[reset]', false, array( 'id' => 'reset' ) ); ?>
			</p>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate form input. Accepts an array, return a sanitized array.
 *
 * @see chateau_theme_options_init()
 *
 */
function chateau_theme_options_validate( $input ) {
	$output = $defaults = chateau_get_default_theme_options();

	// Color scheme must be in our array of color scheme options
	if ( isset( $input['color_scheme'] ) && array_key_exists( $input['color_scheme'], chateau_color_schemes() ) )
		$output['color_scheme'] = $input['color_scheme'];

	// Our defaults for the link color may have changed, based on the color scheme.
	$output['link_color'] = $defaults['link_color'] = chateau_get_default_link_color( $output['color_scheme'] );

	// Link color must be 3 or 6 hexadecimal characters
	if ( isset( $input['link_color'] ) && preg_match( '/^#?([a-f0-9]{3}){1,2}$/i', $input['link_color'] ) )
		$output['link_color'] = '#' . strtolower( ltrim( $input['link_color'], '#' ) );

	// Theme layout must be in our array of theme layout options
	if ( isset( $input['theme_layout'] ) && array_key_exists( $input['theme_layout'], chateau_theme_layouts() ) )
		$output['theme_layout'] = $input['theme_layout'];

			// Archive style must be in our array of archive style options
	if ( isset( $input['archive_style'] ) && array_key_exists( $input['archive_style'], chateau_archive_styles() ) )
		$output['archive_style'] = $input['archive_style'];

	// Reset to default options
	if ( ! empty( $input['reset'] ) ) {
		$output = $defaults = chateau_get_default_theme_options();
		foreach ( $ouput as $field => $value ) {
			if ( isset( $defaults[$field] ) )
				$output[$field] = $defaults[$field];
			else
				unset( $output[$field] );
		}
	}
	return apply_filters( 'chateau_theme_options_validate', $output, $input, $defaults );
}

/**
 * Enqueue the styles for the current color scheme.
 *
 */
function chateau_enqueue_color_scheme() {
	$options = chateau_get_theme_options();
	$color_scheme = $options['color_scheme'];

	if ( 'dark' == $color_scheme )
		wp_enqueue_style( 'dark', get_template_directory_uri() . '/css/dark.css', array(), null );

	do_action( 'chateau_enqueue_color_scheme', $color_scheme );
}
add_action( 'wp_enqueue_scripts', 'chateau_enqueue_color_scheme' );

/**
 * Add a style block to the theme for the current link color.
 *
 * This function is attached to the wp_head action hook.
 *

 */
function chateau_print_link_color_style() {
	$options = chateau_get_theme_options();
	$link_color = $options['link_color'];

	$default_options = chateau_get_default_theme_options();

	// Don't do anything if the current link color is the default.
	if ( $default_options['link_color'] == $link_color )
		return;
?>
	<style>
		#page {
			border-color: <?php echo $link_color; ?>;
		}
		#main-title #site-title a:hover,
		.post-title h1,
		.post-title h1 a,
		.post-extras .post-edit-link,
		.post-entry a,
		.post-entry .more-link:hover,
		#author-description a,
		.more-posts .page-title em,
		#more-posts-inner a:hover,
		#comments li a:hover,
		.comment-text p a,
		.comment-text .reply-link a:hover,
		#comments li.byuser .comment-author,
		#comments #respond h3,
		.sidebar-widget a:active,
		#calendar_wrap table td a,
		#nav-below a:active,
		#error404 a:hover,
		#menu .current-menu-item > a,
		#menu .current_page_item > a,
		#comment-nav-above a,
		#comment-nav-below a,
		.comment-text table a {
			color: <?php echo $link_color; ?>;
		}
	</style>
<?php
}
add_action( 'wp_head', 'chateau_print_link_color_style' );

/**
 * Adds Chateau layout classes to the array of body classes.
 *
 */
function chateau_layout_classes( $existing_classes ) {
	$options = chateau_get_theme_options();
	$current_layout = $options['theme_layout'];

	if ( in_array( $current_layout, array( 'content-sidebar', 'sidebar-content' ) ) )
		$classes = array( 'two-column' );
	else
		$classes = array( 'one-column' );

	// override if layout page templates are used
	if ( is_page_template( 'page-fullwidth.php' ) ) $current_layout = 'content';

	$classes[] = $current_layout;

	$classes = apply_filters( 'chateau_layout_classes', $classes, $current_layout );

	return array_merge( $existing_classes, $classes );
}
add_filter( 'body_class', 'chateau_layout_classes' );