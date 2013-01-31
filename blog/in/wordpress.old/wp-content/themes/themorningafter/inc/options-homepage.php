<?php
function morningafter_theme_form_setting_init() {
	// Add a form section for the Latest Post Options
	add_settings_section( 'morningafter_settings_homepage_options', __( 'Home Page Example', 'woothemes' ), 'morningafter_settings_homepage_options_text', 'morningafter' );
	
	// Add a form section for the Featured Posts Options
	add_settings_section( 'morningafter_settings_homepage_featured', __( 'Featured (Sticky) Posts Options', 'woothemes' ), 'morningafter_settings_homepage_featured_section_text', 'morningafter' );

	// Add Featured heading setting to the Featured Posts Options
	add_settings_field( 'morningafter_setting_featured_heading', __( 'Featured Heading', 'woothemes' ), 'morningafter_setting_featured_heading', 'morningafter', 'morningafter_settings_homepage_featured' );
	
	// Add Featured thumbnail size setting to the Featured Posts Options
	add_settings_field( 'morningafter_setting_featured_thumb', __( 'Featured Thumbnail Size', 'woothemes' ), 'morningafter_setting_featured_thumb', 'morningafter', 'morningafter_settings_homepage_featured' );
	
	// Add a form section for the Aside Posts Options
	add_settings_section( 'morningafter_settings_homepage_aside', __( 'Aside Posts Options', 'woothemes' ), 'morningafter_settings_homepage_aside_section_text', 'morningafter' );
	
	add_settings_field( 'morningafter_setting_aside_heading', __( 'Aside Heading', 'woothemes' ), 'morningafter_setting_aside_heading', 'morningafter', 'morningafter_settings_homepage_aside' );
}

/**
 * The morningafter_theme_form_setting_init call is hooked into admin_init
 */
add_action( 'admin_init', 'morningafter_theme_form_setting_init' );

// Text for the Latest Post Options
function morningafter_settings_homepage_options_text() { ?>
	<div class="example" style="width: 510px">
		<img src="<?php echo get_template_directory_uri() . '/inc/images/homepage-example.jpg'; ?>" alt="<?php esc_attr_e( 'Home Page Example.', 'woothemes' ); ?>" />
		<p class="example-text"><?php _e( 'Home Page Example.', 'woothemes' ); ?></p>
	</div>	
<?php }


// Text for the Featured Posts Options
function morningafter_settings_homepage_featured_section_text() { ?>
	<p><?php _e( 'Manage Featured Posts options on home page.', 'woothemes' ); ?></p>
<?php }

// Text for the Featured Posts Options
function morningafter_settings_homepage_aside_section_text() { ?>
	<p><?php _e( 'Manage Aside Posts options on home page. Aside posts are designed to tell readers a little bit of information and it only supports text and links on home page.', 'woothemes' ); ?></p>
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

// Display Aisde Heading Setting
function morningafter_setting_aside_heading() {
	$morningafter_options = morningafter_get_theme_options(); ?>
	<input name="theme_morningafter_options[aside_heading]" type="text" value="<?php echo esc_attr( stripslashes( $morningafter_options['aside_heading'] ) ); ?>" size="30">
	<span class="description"><?php _e( 'Type a custom heading for your aside posts section.', 'woothemes' ); ?></span>
<?php }