<?php
/**
 * Theme Options: allow removal of navigation search bar
 *
 * @package WordPress
 * @subpackage Koi
 */

add_action( 'admin_init', 'koi_theme_options_init' );
add_action( 'admin_menu', 'koi_theme_options_add_page' );

// Init theme options to white list our options
function koi_theme_options_init(){
	register_setting( 'koi_theme', 'koi_theme_options', 'koi_theme_options_validate' );
}

// Load up the menu page
function koi_theme_options_add_page() {
	add_theme_page( __( 'Theme Options', 'ndesignthemes' ), __( 'Theme Options', 'ndesignthemes' ), 'edit_theme_options', 'theme_options', 'koi_theme_options_do_page' );
}

// Create the options page
function koi_theme_options_do_page() {
	?>
	<div class="wrap">
	    <?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options', 'ndesignthemes' ) . "</h2>"; ?>

		<?php if ( isset( $_REQUEST['settings-updated'] ) && 'true' == $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved', 'ndesignthemes' ); ?></strong></p></div>
		<?php endif; ?>

		<form method="post" action="options.php">
			<?php settings_fields('koi_theme'); ?>
			<?php $options = get_option('koi_theme_options'); ?>

			<table class="form-table">
				<?php
				/**
				 * Show search in header
				 */
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Hide the header search form?', 'ndesignthemes' ); ?></th>
					<td>
						<input id="koi_theme_options[hide-header-search]" name="koi_theme_options[hide-header-search]" type="checkbox" value="1" <?php checked( '1', $options['hide-header-search'] ); ?> />
						<label class="description" for="koi_theme_options[hide-header-search]"><?php _e( "Yes, I'd like to hide the header search form.", 'ndesignthemes' ); ?></label>
					</td>
				</tr>
			</table>

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Options', 'ndesignthemes' ); ?>" />
			</p>
		</form>
	</div>
	<?php
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function koi_theme_options_validate( $input ) {
	// Checkbox value should be 0 or 1
	$input['hide-header-search'] = ( isset( $input['hide-header-search'] ) && $input['hide-header-search'] == 1 ? 1 : 0 );
	return $input;
}