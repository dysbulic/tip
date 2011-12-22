<?php

add_action( 'admin_init', 'oulipo_theme_options_init' );
add_action( 'admin_menu', 'oulipo_theme_options_add_page' );

/**
 * Init plugin options to white list our options
 */
function oulipo_theme_options_init(){
	register_setting( 'oulipo_options', 'oulipo_theme_options', 'oulipo_theme_options_validate' );
}

/**
 * Load up the menu page
 */
function oulipo_theme_options_add_page() {
	add_theme_page( __( 'Theme Options' ), __( 'Theme Options' ), 'edit_theme_options', 'theme_options', 'oulipo_theme_options_do_page' );
}

/**
 * Return array for our color schemes
 */
function oulipo_color_schemes() {
	$color_schemes = array(
		'light' => array(
			'value' =>	'light',
			'label' => __( 'Light' )
		),
		'dark' => array(
			'value' =>	'dark',
			'label' => __( 'Dark' )
		),
	);

	return $color_schemes;
}

/**
 * Create the options page
 */
function oulipo_theme_options_do_page() {

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

	?>
	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options' ) . "</h2>"; ?>

		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'oulipo_options' ); ?>
			<?php $options = oulipo_get_theme_options(); ?>

			<table class="form-table">

				<?php
				/**
				 * Oulipo Color Scheme
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Color Scheme' ); ?></th>
					<td>
						<select name="oulipo_theme_options[color_scheme]">
							<?php
								$selected = $options['color_scheme'];
								$p = '';
								$r = '';

								foreach ( oulipo_color_schemes() as $option ) {
									$label = $option['label'];

									if ( $selected == $option['value'] ) // Make default first in list
										$p = "\n\t<option selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
									else
										$r .= "\n\t<option value='" . esc_attr( $option['value'] ) . "'>$label</option>";
								}
								echo $p . $r;
							?>
						</select>
						<label class="description" for="oulipo_theme_options[color_scheme]"><?php _e( 'Select a default color scheme' ); ?></label>
					</td>
				</tr>

			</table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Options' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */
function oulipo_theme_options_validate( $input ) {

	// Our color scheme option must actually be in our array of color scheme options
	if ( ! array_key_exists( $input['color_scheme'], oulipo_color_schemes() ) )
		$input['color_scheme'] = null;

	return $input;
}