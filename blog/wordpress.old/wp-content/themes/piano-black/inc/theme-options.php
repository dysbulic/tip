<?php
/**
 * Theme Options
 *
 * @package WordPress
 * @subpackage Piano Black
 */

add_action( 'admin_init', 'piano_black_theme_options_init' );
add_action( 'admin_menu', 'piano_black_theme_options_add_page' );

// Init plugin options to white list our options
function piano_black_theme_options_init() {
	register_setting( 'piano_black_options', 'piano_black_theme_options', 'piano_black_theme_options_validate' );
}

// Load up the menu page
function piano_black_theme_options_add_page() {
	add_theme_page( __( 'Theme Options', 'piano-black' ), __( 'Theme Options', 'piano-black' ), 'edit_theme_options', 'theme_options', 'piano_black_theme_options_do_page' );
}

// Return array for our search settings
function piano_black_search() {
	$theme_search = array(
		'yes' => array(
			'value' => 'yes',
			'label' => __( 'Yes', 'piano-black' ),
		),
		'no' => array(
			'value' => 'no',
			'label' => __( 'No', 'piano-black' )
		),
	);

	return $theme_search;
}

// Return array for our RSS settings
function piano_black_rss() {
	$theme_rss = array(
		'yes' => array(
			'value' => 'yes',
			'label' => __( 'Yes', 'piano-black' ),
		),
		'no' => array(
			'value' => 'no',
			'label' => __( 'No', 'piano-black' )
		),
	);
	return $theme_rss;
}

// Create the options page
function piano_black_theme_options_do_page() {

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;
	?>

	<div class="wrap">
		<?php screen_icon(); echo "<h2>" . sprintf( __( '%1$s Theme Options', 'piano-black' ), get_current_theme() )
		 . "</h2>"; ?>
		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'piano-black' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'piano_black_options' ); ?>
			<?php $options = piano_black_get_theme_options(); ?>

			<table class="form-table">
				<?php
				/**
				 * Piano Black Search
				 */
				?>
				<tr valign="top" id="piano_black-search"><th scope="row"><?php _e( 'Show Search in Header', 'piano-black' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Show Search in Header', 'piano-black' ); ?></span></legend>
						<?php
							if ( ! isset( $checked ) )
								$checked = '';
							foreach ( piano_black_search() as $option ) {

								$radio_setting = $options['theme_search'];

								if ( '' != $radio_setting ) {
									if ( $options['theme_search'] == $option['value'] ) {
										$checked = "checked=\"checked\"";
									} else {
										$checked = '';
									}
								}
								?>

								<div class="layout">
								<label class="description">
									<input type="radio" name="piano_black_theme_options[theme_search]" value="<?php esc_attr_e( $option['value'], 'piano-black' ); ?>" <?php echo $checked; ?> />
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

			<?php
				/**
				 * Piano Black RSS
				 */
				?>

				<tr valign="top" id="piano_black-rss"><th scope="row"><?php _e( 'Show RSS Link in Header', 'piano-black' ); ?></th>
					<td>
						<fieldset><legend class="screen-reader-text"><span><?php _e( 'Show RSS Link in Header', 'piano-black' ); ?></span></legend>
						<?php

							if ( ! isset( $checked ) )
								$checked = '';
							foreach ( piano_black_rss() as $option ) {
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
									<input type="radio" name="piano_black_theme_options[theme_rss]" value="<?php esc_attr_e( $option['value'], 'piano-black' ); ?>" <?php echo $checked; ?> />
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
				<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Options', 'piano-black' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function piano_black_theme_options_validate( $input ) {
	if ( ! array_key_exists( $input['theme_search'], piano_black_search() ) )
		$input['theme_search'] = 'yes';

	if ( ! array_key_exists( $input['theme_rss'], piano_black_rss() ) )
		$input['theme_rss'] = 'yes';

	return $input;
}