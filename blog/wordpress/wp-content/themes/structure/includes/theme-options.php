<?php
/**
 * @package WordPress
 * @subpackage Structure
 */

$settings = 'structure-options'; // do not change!

function structure_default_theme_settings() {
	return array( // define our defaults
		'hp_top_cat' => 1,
		'facebook_url' => '',
		'twitter_url' => '',
		'dark_scheme' => '',
		'single_feature' => ''
	);
}

//	push the defaults to the options database,
//	if options don't yet exist there.
add_option($settings, structure_default_theme_settings(), '', 'yes');

//	this function registers our settings in the db
add_action('admin_init', 'register_theme_settings');
function register_theme_settings() {
	global $settings;
	register_setting($settings, $settings, 'structure_sanitize_theme_settings' );
}
//	this function adds the settings page to the Appearance tab
add_action('admin_menu', 'add_theme_options_menu');
function add_theme_options_menu() {
	add_theme_page( __( 'Theme Options' ), __( 'Theme Options' ), 'edit_theme_options', 'theme_options', 'theme_settings_admin' );
}

function structure_sanitize_theme_settings( $in_settings ) {
	$defaults = structure_default_theme_settings();
	$out_settings = array();
	foreach ( $defaults as $k => $v ) {
		if ( isset( $in_settings[$k] ) )
			$out_settings[$k] = $in_settings[$k];
		else
			$out_settings[$k] = $v;
	}
	foreach ( array( 'hp_top_cat', 'single_feature', 'dark_scheme' ) as $absint )
		$out_settings[$absint] = absint( $out_settings[$absint] );

	return $out_settings;
}

// options admin page
function theme_settings_admin() { ?>
<div class="wrap">
<?php
	// display the proper notification if Saved/Reset
	global $settings;
	if(st_option('reset')) {
		echo '<div class="updated fade" id="message"><p>Theme settings reset</p></div>';
		update_option($settings, structure_default_theme_settings());
	} elseif($_REQUEST['settings-updated'] == 'true') {
		echo '<div class="updated fade" id="message"><p>Theme settings saved</p></div>';
	}
	// display icon next to page title
	screen_icon();
?>
	<h2><?php echo get_current_theme() . ' '; _e('Theme Options'); ?></h2>
	<form method="post" action="options.php">
	<?php settings_fields($settings); // important! ?>
	
		<table class="form-table">		
			<tr valign="top">
				<th scope="row"><label><?php _e( 'Featured Category', 'structuretheme' ); ?></label></th>
				
				<td><?php wp_dropdown_categories(array('selected' => absint( st_option('hp_top_cat') ), 'name' => $settings.'[hp_top_cat]', 'hide_empty' => false, 'show_option_all' => __( 'None: use the most recent post' ) )); ?> <span class="description"><?php _e( 'Select which category to use for the front page featured post', 'structuretheme' ) ?></span></td>
			</tr>
			
			<tr valign="top">
				<th scope="row"><label><?php _e( 'Single Feature Image', 'structuretheme' ); ?></label></th>
				
				<td><input type="checkbox" name="<?php echo $settings; ?>[single_feature]" value="1" <?php if ( st_option('single_feature') == 1 ) echo 'checked="checked"'; ?> /> <span class="description"><?php _e( 'Show the set Feature Image on single posts', 'structuretheme' ) ?></span></td>								
			</tr>				    						
			
			<tr valign="top">
				<th scope="row"><label><?php _e( 'Twitter URL', 'structuretheme' ); ?></label></th>
				
				<td><input type="text" name="<?php echo $settings; ?>[twitter_url]" value="<?php echo esc_attr( st_option('twitter_url') ); ?>" size="32" /> <span class="description"><?php _e( 'Enter your twitter URL here to display it in your header with a twitter icon', 'structuretheme' ) ?></span></td>
			</tr>	
			
			<tr valign="top">
				<th scope="row"><label><?php _e( 'Facebook URL', 'structuretheme' ); ?></label></th>
				
				<td><input type="text" name="<?php echo $settings; ?>[facebook_url]" value="<?php echo esc_attr( st_option('facebook_url') ); ?>" size="32" /> <span class="description"><?php _e( 'Enter your Facebook URL here to display it in your header with a facebook icon', 'structuretheme' ) ?></span></td>
			</tr>				    
			
			<tr valign="top">
				<th scope="row"><label><?php _e( 'Color scheme', 'structuretheme' ); ?></label></th>
				
				<td><input type="checkbox" name="<?php echo $settings; ?>[dark_scheme]" value="1" <?php if ( st_option('dark_scheme') == 1 ) echo 'checked="checked"'; ?> /> <span class="description"><?php _e( 'Use the alternate dark color scheme. You may want to change your header text color in Appearance->Header', 'structuretheme' ) ?></span></td>								
			</tr>				    			
				
		</table><!-- .form-table -->			

		<p class="submit">
		<input type="submit" class="button-primary" value="<?php _e('Save Options', 'structuretheme') ?>" />
		<input type="submit" class="button-highlighted" name="<?php echo $settings; ?>[reset]" value="<?php _e('Reset Options', 'structuretheme'); ?>" />
		</p>	
	
	</form>

</div><!--end .wrap-->
<?php }

?>
