<?php

add_action( 'admin_init', 'morningafter_theme_options_init' );
add_action( 'admin_menu', 'morningafter_menu_options' );

/**
 * Add theme options page styles
 */
wp_register_style( 'morningafter', get_template_directory_uri() . '/inc/theme-options.css', '', '0.1' );
if ( isset( $_GET['page'] ) && $_GET['page'] == 'theme_options' ) {
	wp_enqueue_style( 'morningafter' );
}

/**
 * Init plugin options to white list our options
 */
function morningafter_theme_options_init() {
	register_setting( 'theme_morningafter_options', 'theme_morningafter_options', 'morningafter_options_validate' );
}

/**
 * Load up the menu page
 */
function morningafter_menu_options() {
	add_theme_page( __( 'Theme Options', 'woothemes' ), __( 'Theme Options', 'woothemes' ), 'edit_theme_options', 'theme_options', 'morningafter_admin_options_page' );
}

/**
 * Set default values
 */
function morningafter_get_default_theme_options() {
	$home = home_url( '/' );
	$feedlink = get_bloginfo( 'rss_url' );
	$defaults = array(
		'show_home_link' => 0,
		'show_feed_link' => 0,
		'show_full_home' => 0,
		'show_full_archive' => 0,
		'featured_heading' => __( 'Featured Posts', 'woothemes' ),
		'featured_thumb' => 65,
		'aside_heading' => '',
		'home' => $home,
		'about' => '',
		'archives' => '',
		'subscribe' => $feedlink,
		'contact' => '',
		'prefix_heading' => '//',
		'homepage_heading' => __( 'home', 'woothemes' ),
		'index_heading' => __( 'index', 'woothemes' ),
		'single_post_heading' => __( "you're reading...", 'woothemes' ),
		'archives_heading' => __( 'archives', 'woothemes' ),
		'search_results_heading' => __( 'here you go', 'woothemes' ),
		'author_archive_heading' => __( 'author archive', 'woothemes' ),
		'four_o_four_heading' => __( 'uh oh!', 'woothemes' ),
	);
	
	return $defaults;

}

/**
 * Returns the current morning after theme options
 */
function morningafter_get_theme_options() {	
	$morningafter_options = get_option( 'theme_morningafter_options', morningafter_get_default_theme_options() );
	return $morningafter_options;
}

/**
 * Setting Page Tabs
 */
function morningafter_get_settings_page_tabs() {
	$tabs = array(
		'general' => __( 'General', 'woothemes' ),
		'homepage' => __( 'Homepage', 'woothemes' ),
		'header_links' => __( 'Header Links', 'woothemes' ),
		'template_headings' => __( 'Template Headings', 'woothemes' )
	);
	return $tabs;
}

/**
 * Define Function to Hold HTML Output for Tabs
 */
function morningafter_admin_options_page_tabs( $current = 'general' ) {
	if ( isset ( $_GET['tab'] ) ) :
		$current = $_GET['tab'];
	else:
		$current = 'general';
	endif;
	$tabs = morningafter_get_settings_page_tabs();
	$links = array();
	foreach( $tabs as $tab => $name ) :
		if ( $tab == $current ) :
			$links[] = "<a class='nav-tab nav-tab-active' href='?page=theme_options&tab=$tab'>$name</a>";
		else :
			$links[] = "<a class='nav-tab' href='?page=theme_options&tab=$tab'>$name</a>";
		endif;
	endforeach;
	echo '<div id="icon-themes" class="icon32"></div>';
	echo '<h2 class="nav-tab-wrapper">';
	foreach ( $links as $link )
	echo $link;
	echo '</h2>';
}

/**
 * Define Function to Hold HTML Output for Theme Options page
 */
function morningafter_admin_options_page() { 

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;	
	?>
	<div class="wrap" id="morningafter_setting">
		
		<?php morningafter_admin_options_page_tabs(); ?>
		
		<?php if ( false !== $_REQUEST['settings-updated'] ) :
			echo "<div class='updated'><p>" .__( 'Theme settings updated successfully.', 'woothemes' )."</p></div>";
		endif; ?>
		
		<form action="options.php" method="post">
			<?php settings_fields( 'theme_morningafter_options' ); ?>
			<?php do_settings_sections( 'morningafter' ); ?>
			<?php $tab = ( isset( $_GET['tab'] ) ? $_GET['tab'] : 'general' ); ?>
			<?php $morningafter_options = morningafter_get_theme_options();  ?>
			<p class="submit">
				<input name="theme_morningafter_options[submit-<?php echo $tab; ?>]" type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Settings', 'woothemes' ); ?>" />
				<input name="theme_morningafter_options[reset-<?php echo $tab; ?>]" type="submit" class="button-secondary" value="<?php esc_attr_e( 'Reset Defaults', 'woothemes' ); ?>" />
			</p>
		</form>
	</div>
<?php }

/**
 * Separating Settings Per Tab
 */
if ( isset( $_GET['page'] ) && 'theme_options' == $_GET['page'] ) :
	if ( isset ( $_GET['tab'] ) ) :
		$tab = $_GET['tab'];
	else:
		$tab = 'general';
	endif;
	switch ( $tab ) :
		case 'general' :
			require( get_template_directory() . '/inc/options-general.php' );
			break;
		case 'homepage' :
			require( get_template_directory() . '/inc/options-homepage.php' );
			break;
		case 'header_links' :
			require( get_template_directory() . '/inc/options-header-links.php' );
			break;
		case 'template_headings' :
			require( get_template_directory() . '/inc/options-template-headings.php' );
			break;
	endswitch;
endif;

/**
 * validation and whitelisting of user-input form data
 */
function morningafter_options_validate( $input ) {

	$morningafter_options = morningafter_get_theme_options();
	$defaults = morningafter_get_default_theme_options();
	$valid_input = $morningafter_options;
	
	// Determine which form action was submitted
	$submit_general = ( ! empty( $input['submit-general'] ) ? true : false );
	$reset_general = ( ! empty( $input['reset-general'] ) ? true : false );
	$submit_homepage = ( ! empty( $input['submit-homepage'] ) ? true : false );
	$reset_homepage = ( ! empty( $input['reset-homepage'] ) ? true : false );
	$submit_header_links = ( ! empty( $input['submit-header_links'] ) ? true : false );
	$reset_header_links = ( ! empty( $input['reset-header_links'] ) ? true : false );
	$submit_template_headings = ( ! empty( $input['submit-template_headings'] ) ? true : false );
	$reset_template_headings = ( ! empty( $input['reset-template_headings'] ) ? true : false );
	
	if ( $submit_general ) { // if General Settings Submit
		$valid_input['show_home_link'] = ( $input['show_home_link'] == 1 ? 1 : 0 );
		$valid_input['show_feed_link'] = ( $input['show_feed_link'] == 1 ? 1 : 0 );
		$valid_input['show_full_home'] = ( $input['show_full_home'] == 1 ? 1 : 0 );
		$valid_input['show_full_archive'] = ( $input['show_full_archive'] == 1 ? 1 : 0 );
	} elseif ( $reset_general ) { // if General Settings Reset Defaults
		$valid_input['show_home_link'] = $defaults['show_home_link'];
		$valid_input['show_feed_link'] = $defaults['show_feed_link'];
		$valid_input['show_full_home'] = $defaults['show_full_home'];
		$valid_input['show_full_archive'] = $defaults['show_full_archive'];
	} elseif ( $submit_homepage ) { // if Homepage Settings Submit
		$valid_input['featured_heading'] = wp_filter_nohtml_kses( $input['featured_heading'] );
		$valid_input['featured_thumb'] = wp_filter_nohtml_kses( $input['featured_thumb'] );
		$valid_input['aside_heading'] = wp_filter_nohtml_kses( $input['aside_heading'] );
	} elseif ( $reset_homepage ) { // if Homepage Settings Reset Defaults
		$valid_input['featured_heading'] = $defaults['featured_heading'];
		$valid_input['featured_thumb'] = $defaults['featured_thumb'];
		$valid_input['aside_heading'] = $defaults['aside_heading'];		
	} elseif ( $submit_header_links ) { // if Header Links Settings Submit
		$valid_input['home'] = wp_filter_nohtml_kses( $input['home'] );
		$valid_input['about'] = wp_filter_nohtml_kses( $input['about'] );
		$valid_input['archives'] = wp_filter_nohtml_kses( $input['archives'] );
		$valid_input['subscribe'] = wp_filter_nohtml_kses( $input['subscribe'] );
		$valid_input['contact'] = wp_filter_nohtml_kses( $input['contact'] );
	} elseif ( $reset_header_links ) { // if Header Links Settings Reset Defaults
		$valid_input['home'] = $defaults['home'];
		$valid_input['about'] = $defaults['about'];
		$valid_input['archives'] = $defaults['archives'];
		$valid_input['subscribe'] = $defaults['subscribe'];
		$valid_input['contact'] = $defaults['contact'];
	} elseif ( $submit_template_headings ) { // if Template Headings Settings Submit
		$valid_input['prefix_heading'] = wp_filter_nohtml_kses( $input['prefix_heading'] );
		$valid_input['homepage_heading'] = wp_filter_nohtml_kses( $input['homepage_heading'] );
		$valid_input['index_heading'] = wp_filter_nohtml_kses( $input['index_heading'] );
		$valid_input['single_post_heading'] = wp_filter_nohtml_kses( $input['single_post_heading'] );
		$valid_input['archives_heading'] = wp_filter_nohtml_kses( $input['archives_heading'] );
		$valid_input['search_results_heading'] = wp_filter_nohtml_kses( $input['search_results_heading'] );
		$valid_input['author_archive_heading'] = wp_filter_nohtml_kses( $input['author_archive_heading'] );
		$valid_input['four_o_four_heading'] = wp_filter_nohtml_kses( $input['four_o_four_heading'] );
	} elseif ( $reset_template_headings ) { // if Template Headings Settings Reset Defaults
		$valid_input['prefix_heading'] = $defaults['prefix_heading'];
		$valid_input['homepage_heading'] = $defaults['homepage_heading'];
		$valid_input['index_heading'] = $defaults['index_heading'];
		$valid_input['single_post_heading'] = $defaults['single_post_heading'];
		$valid_input['archives_heading'] = $defaults['archives_heading'];
		$valid_input['search_results_heading'] = $defaults['search_results_heading'];
		$valid_input['author_archive_heading'] = $defaults['author_archive_heading'];
		$valid_input['four_o_four_heading'] = $defaults['four_o_four_heading'];
	}
	return $valid_input;
}