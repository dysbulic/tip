<?php

$themecolors = array(
	'bg' => 'fcfaf4',
	'text' => '77756b',
	'link' => '9e9e9e'
);

$content_width = 420;

if ( function_exists('register_sidebars') )
	register_sidebars(1);

add_theme_support( 'automatic-feed-links' );

// Custom background
add_custom_background();

function treba_custom_background() {
	if ( '' != get_background_color() && '' == get_background_image() ) { ?>
	<style type="text/css">
		body { background-image: none; }
	</style>
	<?php }
}
add_action( 'wp_head', 'treba_custom_background' );