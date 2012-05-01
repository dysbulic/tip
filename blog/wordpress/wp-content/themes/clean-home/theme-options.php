<?php

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
	add_theme_page( __( 'Theme Options' ), __( 'Theme Options' ), 'edit_theme_options', 'theme_options', 'cleanhome_theme_options_do_page' );
}

/**
 * Return array for our color schemes
 */
function cleanhome_color_schemes() {
	$color_schemes = array(
		'light' => array(
			'value' =>	'light',
			'label' => __( 'Light' )
		),
		'dark' => array(
			'value' =>	'dark',
			'label' => __( 'Dark' )
		),
		'snowy' => array(
			'value' =>	'snowy',
			'label' => __( 'Snowy' )
		),
		'sunny' => array(
			'value' =>	'sunny',
			'label' => __( 'Sunny' )
		),
	);

	return $color_schemes;
}

/**
 * Set default options
 */
function cleanhome_default_options() {
	$options = get_option( 'cleanhome_theme_options' );

	if ( ! isset( $options['color_scheme'] ) ) {
		$options['color_scheme'] = 'light';
		update_option( 'cleanhome_theme_options', $options );
	}
}
add_action( 'init', 'cleanhome_default_options' );

/**
 * Create the options page
 */
function cleanhome_theme_options_do_page() {

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

	?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options' ) . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'cleanhome' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'cleanhome_options' ); ?>
			<?php $options = get_option( 'cleanhome_theme_options' ); ?>

			<table class="form-table">

				<?php
				/**
				 * Clean Home Color Scheme
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Color Scheme', 'cleanhome' ); ?></th>
					<td>
						<select name="cleanhome_theme_options[color_scheme]">
							<?php
								$selected = $options['color_scheme'];
								$p = '';
								$r = '';

								foreach ( cleanhome_color_schemes() as $option ) {
									$label = $option['label'];

									if ( $selected == $option['value'] ) // Make default first in list
										$p = "\n\t<option selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
									else
										$r .= "\n\t<option value='" . esc_attr( $option['value'] ) . "'>$label</option>";
								}
								echo $p . $r;
							?>
						</select>
						<label class="description" for="cleanhome_theme_options[color_scheme]"><?php _e( 'Select a default color scheme', 'cleanhome' ); ?></label>
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

// adapted from http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/