<?php

/**
 * Custom stlyes for the Theme Options page.
 */
function comet_theme_options_styles( $hook_suffix ) {
	wp_enqueue_style( 'comet-theme-options', get_template_directory_uri() . '/inc/theme-options.css', false, '2011-08-25' );
}
add_action( 'admin_print_styles-appearance_page_comet_theme_options', 'comet_theme_options_styles' );

/**
 * Register the form setting for our comet_options array.
 *
 * This function is attached to the admin_init action hook.
 *
 * This call to register_setting() registers a validation callback, comet_theme_options_validate(),
 * which is used when the option is saved, to ensure that our option values are complete, properly
 * formatted, and safe.
 *
 * We also use this function to add our theme option if it doesn't already exist.
 */
function comet_theme_options_init() {
	register_setting(
		'comet_options',               // Options group
		'comet_theme_options',         // Database option, see comet_get_theme_options()
		'comet_theme_options_validate' // The sanitization callback, see comet_theme_options_validate()
	);
}
add_action( 'admin_init', 'comet_theme_options_init' );

/**
 * Validate Theme Options.
 */
function comet_theme_options_validate( $dirty ) {
	$dirty = wp_parse_args( $dirty, comet_get_theme_option_defaults() );

	$clean = comet_get_theme_option_defaults();
	if ( array_key_exists( $dirty['color_scheme'], comet_color_schemes() ) )
		$clean['color_scheme'] = $dirty['color_scheme'];

	if ( array_key_exists( $dirty['theme_layout'], comet_theme_layouts() ) )
		$clean['theme_layout'] = $dirty['theme_layout'];

	return $clean;
}

/**
 * Setup the custom administration page.
 */
function comet_theme_options_add_menu_link() {
	add_theme_page(
		__( 'Theme Options', 'comet' ), // Name of page
		__( 'Theme Options', 'comet' ), // Label in menu
		'edit_theme_options',           // Capability required
		'comet_theme_options',          // Menu slug, used to uniquely identify the page
		'comet_theme_options_page'      // Function that renders the options page
	);
}
add_action( 'admin_menu', 'comet_theme_options_add_menu_link' );

/**
 * Display the custom administration page.
 */
function comet_theme_options_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php printf( __( '%s Theme Options', 'comet' ), get_current_theme() ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'comet_options' );
				$options = comet_get_theme_options();
			?>

			<table class="form-table">

				<tr valign="top"><th scope="row"><?php _e( 'Color Scheme', 'comet' ); ?></th>
					<td>
						<select id="comet-theme-options-color-scheme" name="comet_theme_options[color_scheme]">
							<?php
								$selected_color = $options['color_scheme'];
								$p = '';
								$r = '';

								foreach ( comet_color_schemes() as $option ) {
									if ( $selected_color == $option['value'] ) { // Make default first in list
										$p = "\n\t" . '<option selected="selected" value="' . esc_attr( $option['value'] ) . '">' . esc_html( $option['label'] ) . '</option>';
									}
									else {
										$r .= "\n\t" . '<option value="' . esc_attr( $option['value'] ) . '">' . esc_html( $option['label'] ) . '</option>';
									}
								}
								echo $p . $r;
							?>
						</select>
						<label class="description" for="comet-theme-options-color-scheme"><?php _e( 'Select a default color scheme', 'comet' ); ?></label>
					</td>
				</tr>

				<tr id="comet-layouts" valign="top" class="image-radio-option"><th scope="row"><?php _e( 'Layout', 'comet' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Color Scheme', 'comet' ); ?></span></legend>
						<?php
							foreach ( comet_theme_layouts() as $layout ) {
								?>
								<div class="layout">
								<label class="description">
									<input type="radio" name="comet_theme_options[theme_layout]" value="<?php echo esc_attr( $layout['value'] ); ?>" <?php checked( $options['theme_layout'], $layout['value'] ); ?> />
									<span>
										<img src="<?php echo esc_url( $layout['thumbnail'] ); ?>" alt=""/>
										<?php echo esc_html( $layout['label'] ); ?>
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
