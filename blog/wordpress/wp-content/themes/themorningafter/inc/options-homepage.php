<?php
function morningafter_theme_form_setting_init() {
	// Add a form section for the Latest Post Options
	add_settings_section( 'morningafter_settings_homepage_latest', __( 'Latest Post Options', 'woothemes' ), 'morningafter_settings_homepage_latest_section_text', 'morningafter' );
	
	// Add Featured heading setting to the Featured Posts Options
	add_settings_field( 'morningafter_setting_ignore_sticky', __( 'Ignore Sticky', 'woothemes' ), 'morningafter_setting_ignore_sticky', 'morningafter', 'morningafter_settings_homepage_latest' );	
	
	// Add a form section for the Featured Posts Options
	add_settings_section( 'morningafter_settings_homepage_featured', __( 'Featured (Sticky) Posts Options', 'woothemes' ), 'morningafter_settings_homepage_featured_section_text', 'morningafter' );

	// Add Featured heading setting to the Featured Posts Options
	add_settings_field( 'morningafter_setting_featured_heading', __( 'Featured Heading', 'woothemes' ), 'morningafter_setting_featured_heading', 'morningafter', 'morningafter_settings_homepage_featured' );
	
	// Add Featured thumbnail size setting to the Featured Posts Options
	add_settings_field( 'morningafter_setting_featured_thumb', __( 'Featured Thumbnail Size', 'woothemes' ), 'morningafter_setting_featured_thumb', 'morningafter', 'morningafter_settings_homepage_featured' );
}

/**
 * The morningafter_theme_form_setting_init call is hooked into admin_init
 */
add_action( 'admin_init', 'morningafter_theme_form_setting_init' );

// Text for the Latest Post Options
function morningafter_settings_homepage_latest_section_text() { ?>
	<p><?php _e( 'Manage Latest Post options on home page.', 'woothemes' ); ?></p>
<?php }


// Text for the Featured Posts Options
function morningafter_settings_homepage_featured_section_text() { ?>
	<p><?php _e( 'Manage Featured Posts options on home page.', 'woothemes' ); ?></p>
<?php }


// Display Show home link Setting
function morningafter_setting_ignore_sticky() {
	$morningafter_options = morningafter_get_theme_options(); ?>
	<input id="theme_morningafter_options[ignore_sticky]" name="theme_morningafter_options[ignore_sticky]" type="checkbox" value="1" <?php checked( '1', $morningafter_options['ignore_sticky'] ); ?> />
	<label class="description" for="theme_morningafter_options[ignore_sticky]"><?php _e( 'Check this if you want to ignore sticky post in latest post section.', 'woothemes' ); ?></label>
<?php }


// Display Featured Heading Setting
function morningafter_setting_featured_heading() {
	$morningafter_options = morningafter_get_theme_options(); ?>
	<input name="theme_morningafter_options[featured_heading]" type="text" value="<?php echo esc_attr( stripslashes( $morningafter_options['featured_heading'] ) ); ?>" size="30">
	<span class="description"><?php _e( 'Type a custom heading for your featured sticky posts section.', 'woothemes' ); ?></span>
<?php }


// Display Featured Thumbnail Size Setting
function morningafter_setting_featured_thumb() {
	$morningafter_options = morningafter_get_theme_options(); ?>
	<input name="theme_morningafter_options[featured_thumb]" type="text" value="<?php echo esc_attr( stripslashes( $morningafter_options['featured_thumb'] ) ); ?>" size="3">px square.
	<span class="description"><?php _e( 'Enter an integer value i.e. 80 for the desired size which will be used for thumbnail', 'woothemes' ); ?></span>
<?php }