<?php
/**
 * @package WordPress
 * @subpackage Fleur
 */

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '000000',
	'link' => 'aa7d39',
	'border' => 'c96666',
	'url' => 'dadace',
);

$content_width = 500;

add_filter( 'body_class', '__return_empty_array', 1 );

if ( function_exists('register_sidebars') )
	register_sidebars(1);

add_theme_support( 'automatic-feed-links' );

add_custom_background();
