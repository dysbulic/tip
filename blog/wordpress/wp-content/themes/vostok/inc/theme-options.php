<?php
/**
 * Vostok theme options
 *
 * @package WordPress
 * @subpackage Vostok
 */

add_action( 'admin_init', 'vostok_theme_options_init' );
add_action( 'admin_menu', 'vostok_theme_options_add_page' );

// Init theme options to white list our options
function vostok_theme_options_init(){
	register_setting( 'vostok_theme', 'vostok_theme_options', 'vostok_theme_options_validate' );
}

// Load up the menu page
function vostok_theme_options_add_page() {
	add_theme_page( __( 'Theme Options' ), __( 'Theme Options' ), 'edit_theme_options', 'theme_options', 'vostok_theme_options_do_page' );
}

// Color scheme options
function vostok_color_schemes() {
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

// Set default options
function vostok_default_options() {
	$options = get_option( 'vostok_theme_options' );

	if ( ! isset( $options['color_scheme'] ) ) {
		$options['color_scheme'] = 'dark';
		update_option( 'vostok_theme_options', $options );
	}

}
add_action( 'init', 'vostok_default_options' );

// Create the options page
function vostok_theme_options_do_page() {
	?>
	<div class="wrap">
	    <?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options' ) . "</h2>"; ?>

		<?php if( 'true' == $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields('vostok_theme'); ?>
			<?php $options = get_option('vostok_theme_options'); ?>

			<table class="form-table">

				<?php
				/**
				 * Show header image and navigation
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Show header image?' ); ?></th>
					<td>
						<input id="vostok_theme_options[show-header-image]" name="vostok_theme_options[show-header-image]" type="checkbox" value="1" <?php checked('1', $options['show-header-image']); ?> />
						<label class="description" for="vostok_theme_options[show-header-image]"><?php _e( 'Yes, show the header image.' ); ?><br /><?php _e( 'If you enable the header image, you can customize the image via <em>Appearance</em> > <em>Header</em>.' ); ?></label>
					</td>
				</tr>

				<tr valign="top"><th scope="row"><?php _e( 'Show top navigation?' ); ?></th>
					<td>
						<input id="vostok_theme_options[show-header-nav]" name="vostok_theme_options[show-header-nav]" type="checkbox" value="1" <?php checked('1', $options['show-header-nav']); ?> />
						<label class="description" for="vostok_theme_options[show-header-nav]"><?php _e( 'Yes, show the top navigation.' ); ?><br /><?php _e( 'If you enable the top navigation, you can customize the links via <em>Appearance</em> > <em>Menus</em>.' ); ?></label>
					</td>
				</tr>

				<?php
				/**
				 * Color Scheme
				 */
				?>
				<?php /*
					

				<tr valign="top"><th scope="row"><?php _e( 'Color Scheme' ); ?></th>
					<td>
						<select name="vostok_theme_options[color_scheme]">
							<?php
								$selected = $options['color_scheme'];
								$p = '';
								$r = '';

								foreach ( vostok_color_schemes() as $option ) {
									$label = $option['label'];

									if ( $selected == $option['value'] )
										$p = "\n\t<option selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
									else
										$r .= "\n\t<option value='" . esc_attr( $option['value'] ) . "'>$label</option>";
								}
								echo $p . $r;
							?>
						</select>
						<label class="description" for="vostok_theme_options[color_scheme]"><?php _e( 'Select a color scheme' ); ?></label>
					</td>
				</tr>

				*/ ?>

			</table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Options') ?>" />
			</p>
		</form>
	</div>
	<?php
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function vostok_theme_options_validate( $input ) {

	// Our color scheme option must actually be in our array of color scheme options
	if ( ! array_key_exists( $input['color_scheme'], vostok_color_schemes() ) )
		$input['color_scheme'] = null;

	// Checkbox value should be 0 or 1
	$input['show-header-image'] = ( $input['show-header-image'] == 1 ? 1 : 0 );
	$input['show-header-nav'] = ( $input['show-header-nav'] == 1 ? 1 : 0 );

	return $input;
}

// Theme Options adapted from <a rel="noreferrer nofollow" href="http://href.li/?http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/">http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/</a>