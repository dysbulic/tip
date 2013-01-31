<?php

add_action( 'admin_init', 'theme_options_init' );
add_action( 'admin_menu', 'theme_options_add_page' );

/**
 * Init plugin options to white list our options
 */
function theme_options_init(){
	register_setting( 'bueno_options', 'bueno_theme_options', 'theme_options_validate' );
}

/**
 * Load up the menu page
 */
function theme_options_add_page() {
	add_theme_page( __( 'Theme Options' ), __( 'Theme Options' ), 'edit_theme_options', 'theme_options', 'theme_options_do_page' );
}

/**
 * Set the default options for the select option
 */
$select_options = array( 'Blue', 'Brown', 'Default', 'Green', 'Grey', 'Purple', 'Red' );

/**
 * Create the options page
 */
function theme_options_do_page() {
	global $select_options;
	?>
	<div class="wrap">
	    <?php screen_icon(); echo "<h2>" . get_current_theme() . __( ' Theme Options' ) . "</h2>"; ?>
	    
		<?php if ( isset( $_REQUEST['settings-updated'] ) && $_REQUEST['settings-updated'] == 'true' ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
		<?php endif; ?>
		
		<form method="post" action="options.php">
			<?php settings_fields('bueno_options'); ?>
			<?php $options = get_option('bueno_theme_options'); ?>
			
			<table class="form-table">		
			
				<?php
				/**
				 * Home link in nav menu?
				 */				
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Home Link' ); ?></th>
					<td>
						<input id="bueno_theme_options[homelink]" name="bueno_theme_options[homelink]" type="checkbox" value="1" <?php checked('1', $options['homelink']); ?> />
						<label class="description" for="bueno_theme_options[homelink]"><?php _e( 'Show a link to your home page in the nav menu' ); ?></label>						
					</td>
				</tr>
				
				<?php
				/**
				 * Feed link in nav menu?
				 */				
				?>
				<tr valign="top"><th scope="row"><?php _e( 'Feed Link' ); ?></th>
					<td>
						<input id="bueno_theme_options[feedlink]" name="bueno_theme_options[feedlink]" type="checkbox" value="1" <?php checked('1', $options['feedlink']); ?> />
						<label class="description" for="bueno_theme_options[feedlink]"><?php _e( 'Show a link to your RSS feed in the nav menu' ); ?></label>						
					</td>
				</tr>							
			
				<?php
				/**
				 * Alternate color scheme option
				 */				
				?>				
				<tr valign="top"><th scope="row"><?php _e( 'Color scheme' ); ?></th>
					<td>
						<select name="bueno_theme_options[colorscheme]">
							<?php	
							$selected = $options['colorscheme'];							 						 						
							$p = '';
							$r = '';						
						
							foreach ( $select_options as $option ) {
								if ( ! isset( $options['colorscheme'] ) || ! in_array($options['colorscheme'], $select_options) ) {
									$selected = 'Default';
								}							
								if ( $selected == $option ) // Make default first in list
									$p = "\n\t<option style=\"padding-right: 10px;\" selected='selected' value='" . esc_attr($option) . "'>$option</option>";
								else
									$r .= "\n\t<option style=\"padding-right: 10px;\" value='" . esc_attr($option) . "'>$option</option>";
							}
							echo $p . $r;							?>
						</select>
						<label class="description" for="bueno_theme_options[colorscheme]"><?php _e( 'Select an alternate color scheme' ); ?></label>						
					</td>
				</tr>				
			</table>
			
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
			</p>
		</form>
	</div>
	<?php	
}

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */				
function theme_options_validate($input) {
	global $select_options;
	
	// Our homelink value is either 0 or 1
	$input['homelink'] = ( $input['homelink'] == 1 ? 1 : 0 );

	// Our feedlink value is either 0 or 1
	$input['feedlink'] = ( $input['feedlink'] == 1 ? 1 : 0 );

	// Say our color scheme option must be safe text with no HTML tags and in our array of select options
	if ( in_array( $input['colorscheme'], $select_options) ) {
		$input['colorscheme'] =  $input['colorscheme'];
	} else {
		$input['colorscheme'] =  'Default';
	}
	
	return $input;
}

// adapted from http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/