<?php

$themecolors = array(
	'bg' => 'fefcfa',
	'text' => '000000',
	'link' => '8e7d6c'
);

$content_width = 420;

if ( function_exists('register_sidebars') )
	register_sidebars(1);

add_theme_support( 'automatic-feed-links' );

// Custom background
add_custom_background();

function jentri_custom_background() {
	if ( '' != get_background_color() || '' != get_background_image() ) { ?>
		<style type="text/css">
			#wrap { background-image: none; }
		<?php if ( '' != get_background_color() && '' == get_background_image() ) { ?>
			body { background-image: none; }
		<?php } ?>
		</style>
	<?php }
}
add_action( 'wp_head', 'jentri_custom_background' );