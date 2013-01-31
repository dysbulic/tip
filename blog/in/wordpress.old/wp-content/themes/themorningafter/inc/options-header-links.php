<?php
function morningafter_theme_form_setting_init() {
	// Add a form section for the Header Links Options
	add_settings_section( 'morningafter_settings_header_links', __( 'Header Links Options', 'woothemes' ), 'morningafter_settings_header_links_section_text', 'morningafter' );

	// Add Home link setting to the Header Links Options
	add_settings_field( 'morningafter_setting_home_link', __( 'Home', 'woothemes' ), 'morningafter_setting_home_link', 'morningafter', 'morningafter_settings_header_links' );
	
	// Add About link setting to the Header Links Options
	add_settings_field( 'morningafter_setting_about_link', __( 'About', 'woothemes' ), 'morningafter_setting_about_link', 'morningafter', 'morningafter_settings_header_links' );
	
	// Add Archives link setting to the Header Links Options
	add_settings_field( 'morningafter_setting_archives_link', __( 'Archives', 'woothemes' ), 'morningafter_setting_archives_link', 'morningafter', 'morningafter_settings_header_links' );
	
	// Add Subscribe link setting to the Header Links Options
	add_settings_field( 'morningafter_setting_subscribe_link', __( 'Subscribe', 'woothemes' ), 'morningafter_setting_subscribe_link', 'morningafter', 'morningafter_settings_header_links' );
	
	// Add Contact link setting to the Header Links Options
	add_settings_field( 'morningafter_setting_contact_link', __( 'Contact', 'woothemes' ), 'morningafter_setting_contact_link', 'morningafter', 'morningafter_settings_header_links' );
}

/**
 * The morningafter_theme_form_setting_init call is hooked into admin_init
 */
add_action( 'admin_init', 'morningafter_theme_form_setting_init' );


// Text for the Header Links Options
function morningafter_settings_header_links_section_text() { ?>
	<p><?php _e( 'Manage Header Links options for the Morning After Theme.', 'woothemes' ); ?></p>
	<div class="example" style="width: 420px">
		<img src="<?php echo get_template_directory_uri() . '/inc/images/header_links.jpg'; ?>" alt="<?php esc_attr_e( 'Header Links Example.', 'woothemes' ); ?>" />
		<p class="example-text"><?php _e( 'Header Links Example.', 'woothemes' ); ?></p>
	</div>
<?php }


// Display Home Link Setting
function morningafter_setting_home_link() {
	$morningafter_options = morningafter_get_theme_options(); ?>
	<input name="theme_morningafter_options[home]" type="text" value="<?php echo esc_url( $morningafter_options['home'] ); ?>" size="30">
	<span class="description"><?php _e( 'Leave empty to hide this link.', 'woothemes' ); ?></span>
<?php }


// Display About Link Setting
function morningafter_setting_about_link() {
	$morningafter_options = morningafter_get_theme_options(); ?>
	<input name="theme_morningafter_options[about]" type="text" value="<?php echo esc_url( $morningafter_options['about'] ); ?>" size="30">
	<span class="description"><?php _e( 'Leave empty to hide this link.', 'woothemes' ); ?></span>
<?php }


// Display Archives link Setting
function morningafter_setting_archives_link() {
	$morningafter_options = morningafter_get_theme_options(); ?>
	<input name="theme_morningafter_options[archives]" type="text" value="<?php echo esc_url( $morningafter_options['archives'] ); ?>" size="30">
	<span class="description"><?php _e( 'Leave empty to hide this link.', 'woothemes' ); ?></span>
<?php }


// Display Subscribe link Setting
function morningafter_setting_subscribe_link() {
	$morningafter_options = morningafter_get_theme_options(); ?>
	<input name="theme_morningafter_options[subscribe]" type="text" value="<?php echo esc_url( $morningafter_options['subscribe'] ); ?>" size="30">
	<span class="description"><?php _e( 'Leave empty to hide this link.', 'woothemes' ); ?></span>
<?php }


// Display Contact link Setting
function morningafter_setting_contact_link() {
	$morningafter_options = morningafter_get_theme_options(); ?>
	<input name="theme_morningafter_options[contact]" type="text" value="<?php echo esc_url( $morningafter_options['contact'] ); ?>" size="30">
	<span class="description"><?php _e( 'Leave empty to hide this link.', 'woothemes' ); ?></span>
<?php }