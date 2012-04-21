<?php

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '333333',
	'link' => '59708c'
);

$content_width = 470;

function ocadia_widgets_init() {
	register_sidebars(1);
}
add_action('widgets_init', 'ocadia_widgets_init');

add_theme_support( 'automatic-feed-links' );