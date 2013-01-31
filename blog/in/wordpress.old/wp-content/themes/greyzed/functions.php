<?php
/**
 * @package Greyzed
 */

// Make theme available for translation
// Translations can be filed in the /languages/ directory
load_theme_textdomain( 'greyzed', TEMPLATEPATH . '/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable( $locale_file ) )
	require_once( $locale_file );

// Theme colors and content width
$themecolors = array(
	'bg' => 'f9f9f9',
	'border' => 'bcc5c1',
	'text' => '333333',
	'link' => 'CC0000',
	'url' => '575b59',
);
$content_width = 614; // pixels

// Add Posts and Comments feeds to theme
add_theme_support( 'automatic-feed-links' );

// This theme uses wp_nav_menu() in one location.
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'greyzed' ),
) );

// Add a home link and make the menu fallback markup more like the menu markup
function greyzed_page_menu_args( $args ) {
	$args['show_home'] = true;
	$args['menu_class'] = 'menu-header';
	return $args;
}
add_filter( 'wp_page_menu_args', 'greyzed_page_menu_args' );

function greyzed_widgets_init() {
	register_sidebar(array(
		'name' => 'Sidebar 1',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	));

	register_sidebar(array(
		'name' => 'Footer Left',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h4 class="footerwidget">',
		'after_title' => '</h4>',
	));

	register_sidebar(array(
		'name' => 'Footer Middle',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h4 class="footerwidget">',
		'after_title' => '</h4>',
	));

	register_sidebar(array(
		'name' => 'Footer Right',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h4 class="footerwidget">',
		'after_title' => '</h4>',
	));
}
add_action( 'widgets_init', 'greyzed_widgets_init' );
