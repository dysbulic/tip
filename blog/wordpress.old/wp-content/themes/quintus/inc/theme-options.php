<?php

/**
 * Properly enqueue styles and scripts for our theme options page.
 *
 * This function is attached to the admin_enqueue_scripts action hook.
 */
function quintus_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style( 'quintus-theme-options', get_template_directory_uri() . '/inc/theme-options.css', false, '2011-04-28' );
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'quintus_admin_enqueue_scripts' );

add_action( 'admin_init', 'theme_options_init' );
add_action( 'admin_menu', 'theme_options_add_page' );

/**
 * Init plugin options to white list our options
 */
function theme_options_init() {
	register_setting( 'quintus_options', 'quintus_theme_options', 'quintus_theme_options_validate' );
}

/**
 * Load up the menu page
 */
function theme_options_add_page() {
	add_theme_page( __( 'Theme Options', 'quintus' ), __( 'Theme Options', 'quintus' ), 'edit_theme_options', 'theme_options', 'theme_options_do_page' );
}

/**
 * Create array for our radio option
 */
$color_scheme_options = array(
	'default' => array(
		'value' => 'default',
		'label' => __( 'Default', 'quintus' )
	),
	'archaic' => array(
		'value' => 'archaic',
		'label' => __( 'Archaic', 'quintus' )
	),
);

/**
 * Create the options page
 */
function theme_options_do_page() {
	global $color_scheme_options;

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

	?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options', 'quintus' ) . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'quintus' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'quintus_options' ); ?>
			<?php $options = get_option( 'quintus_theme_options' ); ?>

			<table class="form-table">

				<?php
				/**
				 * Color Scheme Options
				 */
				?>
				<tr valign="top" class="image-radio-option color-scheme"><th scope="row"><?php _e( 'Color Schemes', 'quintus' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Choose a Color', 'quintus' ); ?></span></legend>
						<?php
							if ( ! isset( $checked ) )
								$checked = '';
							foreach ( $color_scheme_options as $option ) {
								$radio_setting = $options['color_scheme'];

								if ( '' != $radio_setting ) {
									if ( $options['color_scheme'] == $option['value'] ) {
										$checked = "checked=\"checked\"";
									} else {
										$checked = '';
									}
								}
								?>
								<div class="layout">
								<label class="description">
									<input type="radio" name="quintus_theme_options[color_scheme]" value="<?php esc_attr_e( $option['value'] ); ?>" <?php echo $checked; ?> /> <?php echo $option['label']; ?>
									<span>
										<img src="<?php echo get_template_directory_uri() . '/inc/images/color-' . esc_attr( $option['value'] ) . '.png' ; ?>" width="200" height="147" alt="" />
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
				<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Options', 'quintus' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function quintus_theme_options_validate( $input ) {
	global $select_options, $color_scheme_options;

	// Our radio option must actually be in our array of radio options
	if ( ! isset( $input['color_scheme'] ) )
		$input['color_scheme'] = null;
	if ( ! array_key_exists( $input['color_scheme'], $color_scheme_options ) )
		$input['color_scheme'] = null;

	return $input;
}

// adapted from http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/