<?php

// Functions file for Clean Home

$themecolors = array(
	'bg' => 'f6f6f6',
	'border' => 'eeeeee',
	'text' => '333333',
	'link' => 'e12000',
	'url' => 'e94325'
);

$content_width = 590;


// Setup Clean Home
add_action( 'after_setup_theme', 'cleanhome_theme_setup' );

function cleanhome_theme_setup() {

	// Theme options
	require( dirname( __FILE__ ) . '/theme-options.php' );

	// Feed
	add_theme_support( 'automatic-feed-links' );

	// Background
	add_custom_background();
	
	// Header Image
	define( 'HEADER_IMAGE', '' );
	define( 'HEADER_IMAGE_WIDTH', 900 );
	define( 'HEADER_IMAGE_HEIGHT', 200 );
	define( 'HEADER_TEXTCOLOR', '' );
	define( 'NO_HEADER_TEXT', true );

	add_custom_image_header( '', 'admin_header_style' );

	add_action( 'init', 'cleanhome_register_menus' );

	add_action( 'widgets_init', 'cleanhome_widgets_init' );
	
	add_action( 'wp_print_styles', 'cleanhome_color_registrar' );

}

// Header navigation menu
function cleanhome_register_menus() {
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'cleanhome' ),
	) );
}	

// Admin Header
function admin_header_style() { 
		?><style type="text/css">
			#headimg {
				width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
				height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
			}
		</style><?php
	}

// Menu Fallback
function cleanhome_page_menu() { // fallback for primary navigation ?>
<ul>
	<?php wp_list_pages( 'title_li=&depth=1' ); ?>
</ul>
<?php }

// Widgets
function cleanhome_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', 'cleanhome' ),
		'id' => 'primary-widget-area',
		'description' => __( 'The primary widget area', 'cleanhome' ),
		'before_widget' => '<div id="%1$s" class="widget block %2$s sidebar-box">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
	));
	register_sidebar( array(
		'name' => __( 'Header', 'cleanhome' ),
		'id' => 'header',
		'description' => __( 'Header widget area with big font size.', 'cleanhome' ),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ));
}

// Returns the current Clean Home color scheme as selected in the theme options
function cleanhome_current_color_scheme() {
	$options = get_option( 'cleanhome_theme_options' );

	return $options['color_scheme'];
}

// Register our color schemes and add them to the queue
function cleanhome_color_registrar() {
	$color_scheme = cleanhome_current_color_scheme();

	if ( 'dark' == cleanhome_current_color_scheme() ) {
		wp_register_style( 'dark', get_template_directory_uri() . '/colors/dark.css', null, null );
		wp_enqueue_style( 'dark' );
	}
	if ( 'snowy' == cleanhome_current_color_scheme() ) {
		wp_register_style( 'snowy', get_template_directory_uri() . '/colors/snowy.css', null, null );
		wp_enqueue_style( 'snowy' );
	}
	if ( 'sunny' == cleanhome_current_color_scheme() ) {
		wp_register_style( 'sunny', get_template_directory_uri() . '/colors/sunny.css', null, null );
		wp_enqueue_style( 'sunny' );
	}
}

?>