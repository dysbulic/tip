<?php

add_action( 'admin_init', 'theme_options_init' );
add_action( 'admin_menu', 'theme_options_add_page' );

/**
 * Init plugin options to white list our options
 */
function theme_options_init(){
	register_setting( 'spectrum_options', 'spectrum_theme_options', 'theme_options_validate' );
}

/**
 * Load up the menu page
 */
function theme_options_add_page() {
	add_theme_page( __( 'Theme Options' ), __( 'Theme Options' ), 'edit_theme_options', 'theme_options', 'theme_options_do_page' );
}

/**
 * Create the options page
 */
function theme_options_do_page() {
	global $select_options, $radio_options;

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

	?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options' ) . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'spectrum_options' ); ?>
			<?php $options = get_option( 'spectrum_theme_options' ); ?>

			<table class="form-table">

				<?php
				/**
				 * A checkbox option for the footer tag cloud
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Tag cloud' ); ?></th>
					<td>
						<input id="spectrum_theme_options[spectrumtagcloud]" name="spectrum_theme_options[spectrumtagcloud]" type="checkbox" value="1" <?php checked( '1', $options['spectrumtagcloud'] ); ?> />
						<label class="description" for="spectrum_theme_options[spectrumtagcloud]"><?php _e( 'No thanks! I&rsquo;d rather not have the Spectrum tag cloud in my blog footer.' ); ?></label>
					</td>
				</tr>

			</table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Options' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function theme_options_validate( $input ) {
	global $select_options, $radio_options;

	// Our checkbox value is either 0 or 1
	if ( ! isset( $input['spectrumtagcloud'] ) )
		$input['spectrumtagcloud'] = null;
	$input['spectrumtagcloud'] = ( $input['spectrumtagcloud'] == 1 ? 1 : 0 );

	return $input;
}

// adapted from http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/