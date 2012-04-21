<?php
function morningafter_theme_form_setting_init() {
	// Add a form section for the Template Headings Options
	add_settings_section( 'morningafter_settings_template_headings', __( 'Template Headings Options', 'woothemes' ), 'morningafter_settings_template_headings_text', 'morningafter' );

	// Add Prefix setting to the Template Headings Options
	add_settings_field( 'morningafter_setting_prefix_heading', __( 'Prefix', 'woothemes' ), 'morningafter_setting_prefix_heading', 'morningafter', 'morningafter_settings_template_headings' );
	
	// Add Home heading setting to the Template Headings Options
	add_settings_field( 'morningafter_setting_homepage_heading', __( 'Home Page', 'woothemes' ), 'morningafter_setting_homepage_heading', 'morningafter', 'morningafter_settings_template_headings' );
	
	// Add Index heading setting to the Template Headings Options
	add_settings_field( 'morningafter_setting_index_heading', __( 'Index Page', 'woothemes' ), 'morningafter_setting_index_heading', 'morningafter', 'morningafter_settings_template_headings' );
	
	// Add Single Post heading setting to the Template Headings Options
	add_settings_field( 'morningafter_setting_single_post_heading', __( 'Single Post Page', 'woothemes' ), 'morningafter_setting_single_post_heading', 'morningafter', 'morningafter_settings_template_headings' );
	
	// Add Archives heading setting to the Template Headings Options
	add_settings_field( 'morningafter_setting_archives_heading', __( 'Archives Page', 'woothemes' ), 'morningafter_setting_archives_heading', 'morningafter', 'morningafter_settings_template_headings' );
	
	// Add Search heading setting to the Template Headings Options
	add_settings_field( 'morningafter_setting_search_heading', __( 'Search Result Page', 'woothemes' ), 'morningafter_setting_search_heading', 'morningafter', 'morningafter_settings_template_headings' );
	
	// Add Author Archive heading setting to the Template Headings Options
	add_settings_field( 'morningafter_setting_arthor_archive_heading', __( 'Author Archive Page', 'woothemes' ), 'morningafter_setting_arthor_archive_heading', 'morningafter', 'morningafter_settings_template_headings' );
	
	// Add 404 heading setting to the Template Headings Options
	add_settings_field( 'morningafter_setting_404_heading', __( '404 (Not Found) Page', 'woothemes' ), 'morningafter_setting_404_heading', 'morningafter', 'morningafter_settings_template_headings' );
}

/**
 * The morningafter_theme_form_setting_init call is hooked into admin_init
 */
add_action( 'admin_init', 'morningafter_theme_form_setting_init' );


// Text for the Template Headings Options
function morningafter_settings_template_headings_text() { ?>
	<p><?php _e( 'Manage Template Heading options for the Morning After Theme.', 'woothemes' ); ?></p>
	<div class="example" style="width: 582px">
		<img src="<?php echo get_template_directory_uri() . '/inc/images/template_heading.jpg'; ?>" alt="<?php _e( 'Template Heading Example.', 'woothemes' ); ?>" />
		<p class="example-text"><?php _e( 'Template Heading Example.', 'woothemes' ); ?></p>
	</div>	
<?php }


// Display Prefix Setting
function morningafter_setting_prefix_heading() {
	$morningafter_options = morningafter_get_theme_options(); ?>
	<input name="theme_morningafter_options[prefix_heading]" type="text" value="<?php echo esc_attr( $morningafter_options['prefix_heading'] ); ?>" size="30">
	<span class="description"><?php _e( 'Leave empty to hide this.', 'woothemes' ); ?></span>
<?php }

// Display Home heading Setting
function morningafter_setting_homepage_heading() {
	$morningafter_options = morningafter_get_theme_options(); ?>
	<input name="theme_morningafter_options[homepage_heading]" type="text" value="<?php echo esc_attr( stripslashes( $morningafter_options['homepage_heading'] ) ); ?>" size="30">
	<span class="description"><?php _e( 'Leave empty to hide this.', 'woothemes' ); ?></span>
<?php }


// Display Index heading Setting
function morningafter_setting_index_heading() {
	$morningafter_options = morningafter_get_theme_options(); ?>
	<input name="theme_morningafter_options[index_heading]" type="text" value="<?php echo esc_attr( stripslashes( $morningafter_options['index_heading'] ) ); ?>" size="30">
	<span class="description"><?php _e( 'Leave empty to hide this.', 'woothemes' ); ?></span>
<?php }


// Display Single post heading Setting
function morningafter_setting_single_post_heading() {
	$morningafter_options = morningafter_get_theme_options(); ?>
	<input name="theme_morningafter_options[single_post_heading]" type="text" value="<?php echo esc_attr( stripslashes( $morningafter_options['single_post_heading'] ) ); ?>" size="30">
	<span class="description"><?php _e( 'Leave empty to hide this.', 'woothemes' ); ?></span>
<?php }


// Display Archives Heading Setting
function morningafter_setting_archives_heading() {
	$morningafter_options = morningafter_get_theme_options(); ?>
	<input name="theme_morningafter_options[archives_heading]" type="text" value="<?php echo esc_attr( stripslashes( $morningafter_options['archives_heading'] ) ); ?>" size="30">
	<span class="description"><?php _e( 'Leave empty to hide this.', 'woothemes' ); ?></span>
<?php }


// Display Search Results Heading Setting
function morningafter_setting_search_heading() {
	$morningafter_options = morningafter_get_theme_options(); ?>
	<input name="theme_morningafter_options[search_results_heading]" type="text" value="<?php echo esc_attr( stripslashes( $morningafter_options['search_results_heading'] ) ); ?>" size="30">
	<span class="description"><?php _e( 'Leave empty to hide this.', 'woothemes' ); ?></span>
<?php }


// Display Author Archive Heading Setting
function morningafter_setting_arthor_archive_heading() {
	$morningafter_options = morningafter_get_theme_options(); ?>
	<input name="theme_morningafter_options[author_archive_heading]" type="text" value="<?php echo esc_attr( stripslashes( $morningafter_options['author_archive_heading'] ) ); ?>" size="30">
	<span class="description"><?php _e( 'Leave empty to hide this.', 'woothemes' ); ?></span>
<?php }


// Display 404 Heading Setting
function morningafter_setting_404_heading() {
	$morningafter_options = morningafter_get_theme_options(); ?>
	<input name="theme_morningafter_options[four_o_four_heading]" type="text" value="<?php echo esc_attr( stripslashes( $morningafter_options['four_o_four_heading'] ) ); ?>" size="30">
	<span class="description"><?php _e( 'Leave empty to hide this.', 'woothemes' ); ?></span>
<?php }