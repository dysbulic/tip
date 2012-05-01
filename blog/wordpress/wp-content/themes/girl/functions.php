<?php

$themecolors = array(
	'bg' => '272528',
	'text' => '9cb895',
	'link' => 'c3df95',
	'border' => '272528'
);

if ( function_exists('register_sidebars') )
	register_sidebars(1);

add_theme_support( 'automatic-feed-links' );

// Custom background
add_custom_background();

function girl_custom_background() {
	if ( '' != get_background_color() && '' == get_background_image() ) { ?>
	<style type="text/css">
		body { background-image: none; }
	</style>
	<?php }
}
add_action( 'wp_head', 'girl_custom_background' );