<?php

/**
 * Properly enqueue styles and scripts for our theme options page.
 *
 * This function is attached to the admin_enqueue_scripts action hook.
 */
function clean_home_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style( 'clean-home-theme-options', get_template_directory_uri() . '/theme-options.css', false, '2011-08-1' );
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'clean_home_admin_enqueue_scripts' );

add_action( 'admin_init', 'cleanhome_theme_options_init' );
add_action( 'admin_menu', 'cleanhome_theme_options_add_page' );

/**
 * Init plugin options to white list our options
 */
function cleanhome_theme_options_init() {
	register_setting( 'cleanhome_options', 'cleanhome_theme_options', 'cleanhome_theme_options_validate' );
}

/**
 * Load up the menu page
 */
function cleanhome_theme_options_add_page() {
	add_theme_page( __( 'Theme Options', 'cleanhome' ), __( 'Theme Options', 'cleanhome' ), 'edit_theme_options', 'theme_options', 'cleanhome_theme_options_do_page' );
}

/**
 * Return array for our color schemes
 */
function cleanhome_color_schemes() {
	$color_schemes = array(
		'light' => array(
			'value' =>	'light',
			'label' => __( 'Light', 'cleanhome' )
		),
		'dark' => array(
			'value' =>	'dark',
			'label' => __( 'Dark', 'cleanhome' )
		),
		'snowy' => array(
			'value' =>	'snowy',
			'label' => __( 'Snowy', 'cleanhome' )
		),
		'sunny' => array(
			'value' =>	'sunny',
			'label' => __( 'Sunny', 'cleanhome' )
		),
	);

	return $color_schemes;
}

/**
 * Create the options page
 */
function cleanhome_theme_options_do_page() {

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

	?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . get_current_theme() . ' ' . __( 'Theme Options', 'cleanhome' ) . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'cleanhome' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'cleanhome_options' ); ?>
			<?php $options = cleanhome_get_theme_options(); ?>

			<table class="form-table">

				<?php
				/**
				 * Clean Home Color Scheme
				 */
				?>
				<tr valign="top" class="image-radio-option color-scheme"><th scope="row"><?php _e( 'Color Schemes', 'cleanhome' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Color Schemes', 'cleanhome' ); ?></span></legend>
						<?php
							if ( ! isset( $checked ) )
								$checked = '';
							foreach ( cleanhome_color_schemes() as $option ) {
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
									<input type="radio" name="cleanhome_theme_options[color_scheme]" value="<?php esc_attr_e( $option['value'] ); ?>" <?php echo $checked; ?> /> <?php echo $option['label']; ?>
									<span>
										<img src="<?php echo get_template_directory_uri() . '/images/color-' . esc_attr( $option['value'] ) . '.png' ; ?>" width="200" height="147" alt="" />
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
				<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Options', 'cleanhome' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function cleanhome_theme_options_validate( $input ) {

	// Our color scheme option must actually be in our array of color scheme options
	if ( ! array_key_exists( $input['color_scheme'], cleanhome_color_schemes() ) )
		$input['color_scheme'] = null;

	return $input;
}