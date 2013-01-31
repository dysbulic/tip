<?php
function morningafter_theme_form_setting_init() {
	// Add a form section for the General Options
	add_settings_section( 'morningafter_settings_general', __( 'General Options', 'woothemes' ), 'morningafter_settings_general_text', 'morningafter' );

	// Add Show home link setting to the General Options
	add_settings_field( 'morningafter_setting_show_home_link', __( 'Home Link', 'woothemes' ), 'morningafter_setting_show_home_link', 'morningafter', 'morningafter_settings_general' );
	
	// Add Show feed link setting to the General Options
	add_settings_field( 'morningafter_setting_show_feed_link', __( 'Feed Link', 'woothemes' ), 'morningafter_setting_show_feed_link', 'morningafter', 'morningafter_settings_general' );
	
	// Add Show full home setting to the General Options
	add_settings_field( 'morningafter_setting_show_full_home', __( 'Show Full Content Home', 'woothemes' ), 'morningafter_setting_show_full_home', 'morningafter', 'morningafter_settings_general' );
	
	// Add Shoe full archive setting to the General Options
	add_settings_field( 'morningafter_setting_show_full_archive', __( 'Show Full Content Archive', 'woothemes' ), 'morningafter_setting_show_full_archive', 'morningafter', 'morningafter_settings_general' );

}

/**
 * The morningafter_theme_form_setting_init call is hooked into admin_init
 */
add_action( 'admin_init', 'morningafter_theme_form_setting_init' );


// Text for the General Options
function morningafter_settings_general_text() { ?>
	<p><?php _e( 'Manage General options for the Morning After Theme.', 'woothemes' ); ?></p>
<?php }


// Display Show home link Setting
function morningafter_setting_show_home_link() {
	$morningafter_options = morningafter_get_theme_options(); ?>
	<input id="theme_morningafter_options[show_home_link]" name="theme_morningafter_options[show_home_link]" type="checkbox" value="1" <?php checked( '1', $morningafter_options['show_home_link'] ); ?> />
	<label class="description" for="theme_morningafter_options[show_home_link]"><?php _e( 'Check this if you want to show a link to your home page in the default menu. (This will not affect when you use the custom menu)', 'woothemes' ); ?></label>
<?php }

// Display Show feed link Setting
function morningafter_setting_show_feed_link() {
	$morningafter_options = morningafter_get_theme_options(); ?>
	<input id="theme_morningafter_options[show_feed_link]" name="theme_morningafter_options[show_feed_link]" type="checkbox" value="1" <?php checked( '1', $morningafter_options['show_feed_link'] ); ?> />
	<label class="description" for="theme_morningafter_options[show_feed_link]"><?php _e( 'Check this if you want to show a link to your RSS feed in the nav menu', 'woothemes' ); ?></label>
<?php }


// Display Show full home Setting
function morningafter_setting_show_full_home() {
	$morningafter_options = morningafter_get_theme_options(); ?>
	<input id="theme_morningafter_options[show_full_home]" name="theme_morningafter_options[show_full_home]" type="checkbox" value="1" <?php checked( '1', $morningafter_options['show_full_home'] ); ?> />
	<label class="description" for="theme_morningafter_options[show_full_home]"><?php _e( 'Check this if you want to show the full post content on home page.', 'woothemes' ); ?></label>
<?php }

// Display Show full archive Setting
function morningafter_setting_show_full_archive() {
	$morningafter_options = morningafter_get_theme_options(); ?>
	<input id="theme_morningafter_options[show_full_archive]" name="theme_morningafter_options[show_full_archive]" type="checkbox" value="1" <?php checked( '1', $morningafter_options['show_full_archive'] ); ?> />
	<label class="description" for="theme_morningafter_options[show_full_archive]"><?php _e( 'Check this if you want to show the full post content on archive pages.', 'woothemes' ); ?></label>
<?php }