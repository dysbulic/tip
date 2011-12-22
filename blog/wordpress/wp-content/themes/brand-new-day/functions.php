<?php
/**
 * @package WordPress
 * @subpackage Brand New Day
 */

require_once ( get_template_directory() . '/admin/theme-options.php' );

add_action( 'wp_print_styles', 'brand_new_day_print_styles' );

// Register nav menu locations
register_nav_menus( array(
	'main' => __( 'Main Menu', 'brand-new-day' ),
) );


load_theme_textdomain( 'brand-new-day', get_template_directory() . '/languages' );

$locale = get_locale();
$locale_file = get_template_directory() . "/languages/$locale.php";
if ( is_readable( $locale_file ) ) {
	require_once( $locale_file );
}

function brand_new_day_print_styles() {
	if ( ! is_admin() ) {
		$options = brand_new_day_get_theme_options();

		$brand_new_day_themestyle = $options['theme_style'];

		if ( file_exists( get_template_directory() . '/nightlight.css' ) && 'nightlight' == $brand_new_day_themestyle ) {
			wp_register_style( 'brand_new_day_nightlight', get_template_directory_uri() . '/nightlight.css' );
			wp_enqueue_style( 'brand_new_day_nightlight' );
		} else if ( file_exists( get_template_directory() . '/winterlight.css' ) && 'winterlight' == $brand_new_day_themestyle ) {
			wp_register_style( 'brand_new_day_winterlight', get_template_directory_uri() . '/winterlight.css' );
			wp_enqueue_style( 'brand_new_day_winterlight' );
		} else if ( file_exists( get_template_directory() . '/autumnlight.css' ) && 'autumnlight' == $brand_new_day_themestyle ) {
			wp_register_style( 'brand_new_day_autumnlight', get_template_directory_uri() . '/autumnlight.css' );
			wp_enqueue_style( 'brand_new_day_autumnlight' );
		} else if ( file_exists( get_template_directory() . '/daylight.css' ) ){
			wp_register_style( 'brand_new_day_daylight', get_template_directory_uri() . '/daylight.css' );
			wp_enqueue_style( 'brand_new_day_daylight' );
		}
	}
}

add_action( 'widgets_init', 'brand_new_day_sidebars' );

function brand_new_day_sidebars() {

	register_sidebar( array(
		'id' => 'vertical-sidebar',
		'name' => __( 'Vertical Sidebar', 'brand-new-day' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widgettitle">',
		'after_title' => '</h3>',
		) );

	register_sidebar( array(
		'id' => 'footer-sidebar1',
		'name' => __( 'Footer Sidebar 1', 'brand-new-day' ),
		'before_widget' => '<li id="%1$s" class="footer-widget widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="footer-widgettitle">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'id' => 'footer-sidebar2',
		'name' => __( 'Footer Sidebar 2', 'brand-new-day' ),
		'before_widget' => '<li id="%1$s" class="footer-widget widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="footer-widgettitle">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'id' => 'footer-sidebar3',
		'name' => __( 'Footer Sidebar 3', 'brand-new-day' ),
		'before_widget' => '<li id="%1$s" class="footer-widget widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="footer-widgettitle">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'id' => 'footer-sidebar4',
		'name' => __( 'Footer Sidebar 4', 'brand-new-day' ),
		'before_widget' => '<li id="%1$s" class="footer-widget widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="footer-widgettitle">',
		'after_title' => '</h3>',
	) );
}

if ( ! isset( $content_width ) ) $content_width = 630;

add_theme_support('automatic-feed-links');

add_editor_style();

/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 */
function brand_new_day_enhanced_image_navigation( $url ) {
	global $post;

	if ( wp_attachment_is_image( $post->ID ) )
		$url = $url . '#content';

	return $url;
}
add_filter( 'attachment_link', 'brand_new_day_enhanced_image_navigation' );

/**
 * WP.com: Check the current color scheme and set the correct themecolors array
 */
$options = brand_new_day_get_theme_options();
$theme_style = $options['theme_style'];

switch ( $theme_style ) {
	case 'nightlight':
		$themecolors = array(
			'bg' => '3a6a8c',
			'border' => 'caedf1',
			'text' => 'ffffff',
			'link' => 'd7d8a7',
			'url' => 'd7d8a7',
		);
		break;

	case 'winterlight':
		$themecolors = array(
			'bg' => 'f0f0f0',
			'border' => 'caedf1',
			'text' => '555555',
			'link' => '666666',
			'url' => '666666',
		);
		break;
	
	case 'autumnlight':
		$themecolors = array(
			'bg' => 'f4f8f8',
			'border' => 'd6e6e8',
			'text' => '333333',
			'link' => 'a4461c',
			'url' => 'a4461c',
		);
		break;

	default: // daylight
		$themecolors = array(
			'bg' => 'dcf5f8',
			'border' => 'caedf1',
			'text' => '555555',
			'link' => '55bac6',
			'url' => '55bac6',
		);
		break;
}
