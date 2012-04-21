<?php

add_action( 'admin_init', 'skeptical_theme_options_init' );
add_action( 'admin_menu', 'skeptical_theme_options_add_page' );

/**
 * Properly enqueue styles and scripts for our theme options page.
 *
 * This function is attached to the admin_enqueue_scripts action hook.
 */
function skeptical_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style( 'skeptical-theme-options', get_template_directory_uri() . '/inc/theme-options.css', false, '2011-08-22' );
	if ( is_rtl() ) {
		wp_enqueue_style( 'skeptical-theme-options-rtl', get_template_directory_uri() . '/inc/theme-options-rtl.css', false, '2011-08-22' );
	}
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'skeptical_admin_enqueue_scripts' );

// Init plugin options to white list our options
function skeptical_theme_options_init() {
	register_setting( 'skeptical_options', 'skeptical_theme_options', 'skeptical_theme_options_validate' );
}

// Load up the menu page
function skeptical_theme_options_add_page() {
	add_theme_page( __( 'Theme Options', 'woothemes' ), __( 'Theme Options', 'woothemes' ), 'edit_theme_options', 'theme_options', 'skeptical_theme_options_do_page' );
}

// Return array for our color schemes
function skeptical_color_schemes() {
	$color_schemes = array(
		'gray' => array(
			'value' =>	'gray',
			'label' => __( 'Gray', 'woothemes' )
		),
		'blue' => array(
			'value' =>	'blue',
			'label' => __( 'Blue', 'woothemes' )
		),
		'green' => array(
			'value' =>	'green',
			'label' => __( 'Green', 'woothemes' )
		),
		'red' => array(
			'value' =>	'red',
			'label' => __( 'Red', 'woothemes' )
		),
	);

	return $color_schemes;
}

// Return array for our RSS settings
function skeptical_rss() {
	$theme_rss = array(
		'yes' => array(
			'value' => 'yes',
			'label' => __( 'Yes', 'woothemes' ),
		),
		'no' => array(
			'value' => 'no',
			'label' => __( 'No', 'woothemes' )
		),
	);
	return $theme_rss;
}

// Create the options page
function skeptical_theme_options_do_page() {

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

	?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options', 'woothemes' ) . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'woothemes' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'skeptical_options' ); ?>
			<?php $options = skeptical_get_options(); ?>

			<table class="form-table">

				<?php // Skeptical Color Scheme ?>
				<tr valign="top" id="skeptical-colors"><th scope="row"><?php _e( 'Color Scheme', 'woothemes' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Color Scheme', 'woothemes' ); ?></span></legend>
						<?php
							if ( ! isset( $checked ) )
								$checked = '';
							foreach ( skeptical_color_schemes() as $option ) {
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
									<input type="radio" name="skeptical_theme_options[color_scheme]" value="<?php echo esc_attr( $option['value'] ); ?>" <?php echo $checked; ?> />
									<span>
										<img src="<?php echo get_template_directory_uri(); ?>/images/colors/<?php echo $option['value']; ?>.png"/>
										<?php echo $option['label']; ?>
									</span>
								</label>
								</div>
								<?php
							}
						?>
						</fieldset>
					</td>
				</tr>

				<?php // Skeptical RSS ?>
				<tr valign="top" id="skeptical-rss"><th scope="row"><?php _e( 'Show RSS Link in Header', 'woothemes' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Show RSS Link in Header', 'woothemes' ); ?></span></legend>
						<?php
							if ( ! isset( $checked ) )
								$checked = '';
							foreach ( skeptical_rss() as $option ) {
								$radio_setting = $options['theme_rss'];

								if ( '' != $radio_setting ) {
									if ( $options['theme_rss'] == $option['value'] ) {
										$checked = "checked=\"checked\"";
									} else {
										$checked = '';
									}
								}
								?>
								<div class="layout">
								<label class="description">
									<input type="radio" name="skeptical_theme_options[theme_rss]" value="<?php esc_attr_e( $option['value'], 'woothemes' ); ?>" <?php echo $checked; ?> />
									<span>
										<?php echo $option['label']; ?>
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
				<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Options', 'woothemes' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function skeptical_theme_options_validate( $input ) {
	if ( ! array_key_exists( $input['color_scheme'], skeptical_color_schemes() ) )
		$input['color_scheme'] = 'gray';
		
	if ( ! array_key_exists( $input['theme_rss'], skeptical_rss() ) )
		$input['theme_rss'] = 'yes';

	return $input;
}
// adapted from http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/