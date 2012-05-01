<?php
/**
 * @package WordPress
 * @subpackage Notepad
 */
add_action( 'admin_init', 'theme_options_init' );
add_action( 'admin_menu', 'theme_options_add_page' );

/**
 * Init plugin options to white list our options
 */
function theme_options_init(){
	register_setting( 'notepad_options', 'notepad_theme_options', 'theme_options_validate' );
}

/**
 * Load up the menu page
 */
function theme_options_add_page() {
	add_theme_page( __( 'Theme Options' ), __( 'Theme Options' ), 'edit_theme_options', 'theme_options', 'theme_options_do_page' );
}

/**
 * Set an array for our social media links
 */
$notepad_media_links = array(
	array('twitter', __( 'Twitter' ) ),
	array('facebook', __( 'Facebook') ),
	array('flickr', __('Flickr') ),
);

/**
 * Create the options page
 */
function theme_options_do_page() {
	if ( !isset( $_REQUEST['settings-updated'] ) ) {
		//If not isset set as false
		$_REQUEST['settings-updated'] = 'false';
	}
	?>
	<div class="wrap">
	    <?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options' ) . "</h2>"; ?>

		<?php if( $_REQUEST['settings-updated'] == 'true' ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
		<?php endif; ?>

		<p><?php _e( 'Add the URLs of your other personal sites on the web to show links to them in your header. You can also choose to display links to your email and RSS feed.', 'notepad-theme' ); ?></p>

		<form method="post" action="options.php">
			<?php settings_fields('notepad_options'); ?>
			<?php $options = get_option('notepad_theme_options'); ?>

			<table class="form-table">

				<?php
				/**
				 * Text inputs for our social media links
				 */
				global $notepad_media_links;
				foreach ( $notepad_media_links as $notepad_media_link ) :
					$notepad_media_url = "notepad_theme_options[$notepad_media_link[0]url]";
				?>
				<tr valign="top"><th scope="row"><?php echo $notepad_media_link[1]; ?></th>
					<td>
						<input id="<?php echo "notepad_theme_options[$notepad_media_link[0]url]"; ?>" class="regular-text" type="text" name="<?php echo "notepad_theme_options[$notepad_media_link[0]url]"; ?>" value="<?php echo $options["$notepad_media_link[0]url"]; ?>" />
					</td>
				</tr>
				<?php endforeach; ?>

				<?php
				/**
				 * Checkboxes for RSS link in header
				 */
				if ( !isset( $options['rss'] ) ) {
					//If not isset set as NULL
					$options['rss'] = NULL;
				}
				?>
				<tr valign="top"><th scope="row"><?php _e( 'RSS' ); ?></th>
					<td>
						<input id="notepad_theme_options[rss]" name="notepad_theme_options[rss]" type="checkbox" value="1" <?php checked('1', $options['rss']); ?> />
						<label class="description" for="notepad_theme_options[rss]"><?php _e( 'Show a link to your RSS feed in the header' ); ?></label>
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
function theme_options_validate($input) {
	global $notepad_media_links;

	// Say our text option must be safe text with no HTML tags
	foreach ( $notepad_media_links as $notepad_media_link ) {
		$subject = $input["$notepad_media_link[0]url"];
		$pattern1 = 'http:\/\/' . $notepad_media_link[0] . '.com\/';
		$pattern2 = 'http:\/\/www.' . $notepad_media_link[0] . '.com\/';
		if ( preg_match( "/^$pattern1/", $subject ) == 1 ) {
			$input["$notepad_media_link[0]url"] =  wp_filter_nohtml_kses($input["$notepad_media_link[0]url"]);
		} elseif ( preg_match( "/^$pattern2/", $subject ) == 1 ) {
			$input["$notepad_media_link[0]url"] =  wp_filter_nohtml_kses($input["$notepad_media_link[0]url"]);
		} else {
			$input["$notepad_media_link[0]url"] = NULL;
		}
	}

	// Our checkbox values are either 0 or 1
	if ( !isset( $input['rss'] ) ) {
		//If not isset set as NULL
		$input['rss'] = NULL;
	}
	$input['rss'] = ( $input['rss'] == 1 ? 1 : 0 );

	return $input;
}

// adapted from http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/