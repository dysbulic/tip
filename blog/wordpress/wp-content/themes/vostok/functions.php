<?php
// Vostok is a child theme for Twenty Ten, the default WordPress theme
// This file overrides the default parent theme functions

// Colors for WordPress.com
$themecolors = array(
	'bg' => '2F2F2F',
	'border' => '3C3C3C',
	'text' => '999999',
	'link' => 'F90',
	'url' => 'F90'
);

// Set the content width
$content_width = 520;

function twentyten_setup() {
	// This theme styles the visual editor with editor-style.css to match the theme style.
	// add_editor_style();

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location. 
	register_nav_menus( array( 
		'primary' => __( 'Primary Navigation', 'twentyten' ),
		'footer' => __( 'Footer Navigation', 'twentyten' ),
	) );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'twentyten', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme allows users to set a custom background
	add_custom_background();

	$options = get_option('vostok_theme_options');

	// Load color stylesheet (if enabled in theme options)
	// wp_register_style( 'vostok-white', get_bloginfo( 'stylesheet_directory' ) . '/css/white.css', '', '' );
	// if ( isset( $options['color_scheme'] ) && 'light' == $options['color_scheme'] )
	// 	wp_enqueue_style( 'vostok-white' );

	if ( 1 == $options['show-header-image'] ) {
		// Your changeable header business starts here
		define( 'HEADER_TEXTCOLOR', '' );
		// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
		define( 'HEADER_IMAGE', '%s/images/headers/path.jpg' );

		// The height and width of your custom header. You can hook into the theme's own filters to change these values.
		// Add a filter to twentyten_header_image_width and twentyten_header_image_height to change these values.
		define( 'HEADER_IMAGE_WIDTH', apply_filters( 'twentyten_header_image_width',  600 ) );
		define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'twentyten_header_image_height',	126 ) );

		// We'll be using post thumbnails for custom header images on posts and pages.
		// We want them to be 600 pixels wide by 126 pixels tall (larger images will be auto-cropped to fit).
		set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

		// Don't support text inside the header image.
		define( 'NO_HEADER_TEXT', true );

		// Add a way for the custom header to be styled in the admin panel that controls
		// custom headers. See twentyten_admin_header_style(), below.
		add_custom_image_header( '', 'twentyten_admin_header_style' );

		// ... and thus ends the changeable header business.		

		// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
		register_default_headers( array(
			'berries' => array(
				'url' => '%s/images/headers/berries.jpg',
				'thumbnail_url' => '%s/images/headers/berries-thumbnail.jpg',
				'description' => __( 'Berries', 'twentyten' )
			),
			'cherryblossom' => array(
				'url' => '%s/images/headers/cherryblossoms.jpg',
				'thumbnail_url' => '%s/images/headers/cherryblossoms-thumbnail.jpg',
				'description' => __( 'Cherry Blossoms', 'twentyten' )
			),
			'concave' => array(
				'url' => '%s/images/headers/concave.jpg',
				'thumbnail_url' => '%s/images/headers/concave-thumbnail.jpg',
				'description' => __( 'Concave', 'twentyten' )
			),
			'fern' => array(
				'url' => '%s/images/headers/fern.jpg',
				'thumbnail_url' => '%s/images/headers/fern-thumbnail.jpg',
				'description' => __( 'Fern', 'twentyten' )
			),
			'forestfloor' => array(
				'url' => '%s/images/headers/forestfloor.jpg',
				'thumbnail_url' => '%s/images/headers/forestfloor-thumbnail.jpg',
				'description' => __( 'Forest Floor', 'twentyten' )
			),
			'inkwell' => array(
				'url' => '%s/images/headers/inkwell.jpg',
				'thumbnail_url' => '%s/images/headers/inkwell-thumbnail.jpg',
				'description' => __( 'Inkwell', 'twentyten' )
			),
			'path' => array(
				'url' => '%s/images/headers/path.jpg',
				'thumbnail_url' => '%s/images/headers/path-thumbnail.jpg',
				'description' => __( 'Path', 'twentyten' )
			),
			'sunset' => array(
				'url' => '%s/images/headers/sunset.jpg',
				'thumbnail_url' => '%s/images/headers/sunset-thumbnail.jpg',
				'description' => __( 'Sunset', 'twentyten' )
			)
		) );
	}
}

// Register widgetized areas
function vostok_widgets_init() {

	// Area 1 (left column)
	register_sidebar( array(
		'name' => 'Left Footer Widget Area',
		'id' => 'primary-widget-area',
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 2 (right column)
	register_sidebar( array(
		'name' => 'Right Footer Widget Area',
		'id' => 'secondary-widget-area',
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

}

// Replace default widgets
function vostok_setup() {
	remove_action( 'widgets_init', 'twentyten_widgets_init' );
	add_action( 'widgets_init', 'vostok_widgets_init' );
}
add_action( 'after_setup_theme', 'vostok_setup' );

// We loves us some Theme Options =]
require_once( dirname( __FILE__ ) . '/inc/theme-options.php' );