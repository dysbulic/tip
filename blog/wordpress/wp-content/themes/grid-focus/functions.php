<?php
/**
 *	@package WordPress
 *	@subpackage Grid_Focus
 */
$content_width = 406;

// Make theme available for translation
// Translations can be filed in the /languages/ directory
load_theme_textdomain( 'grid-focus', get_template_directory() . '/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable( $locale_file ) )
	require_once( $locale_file );


if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => 'Primary - Index',
		'before_widget' => '<div id="%1$s" class="widgetContainer %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widgetTitle">',
		'after_title' => '</h3>'
	));
	register_sidebar(array(
		'name' => 'Primary - Post',
		'before_widget' => '<div id="%1$s" class="widgetContainer %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widgetTitle">',
		'after_title' => '</h3>'
	));
	register_sidebar(array(
		'name' => 'Secondary - Shared',
		'before_widget' => '<div id="%1$s" class="widgetContainer %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="widgetTitle">',
		'after_title' => '</h3>'
	));
}

add_theme_support( 'automatic-feed-links' );

add_custom_background();
