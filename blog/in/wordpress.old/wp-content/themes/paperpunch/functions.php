<?php
/**
 * @package WordPress
 * @subpackage Paperpunch
 */

// Comments
require_once( get_template_directory() . '/functions/comments.php' );

// Widgets
register_sidebar( array(
	'name'=> __( 'Sidebar', 'paperpunch' ),
	'id' => 'sidebar',
	'before_widget' => '<li id="%1$s" class="widget %2$s">',
	'after_widget' => '</li>',
	'before_title' => '<h2 class="widgettitle">',
	'after_title' => '</h2>',
) );
register_sidebar( array(
	'name'=> __( 'Footer 1', 'paperpunch' ),
	'id' => 'footer-1',
	'before_widget' => '<li id="%1$s" class="widget %2$s">',
	'after_widget' => '</li>',
	'before_title' => '<h2 class="widgettitle">',
	'after_title' => '</h2>',
) );
register_sidebar( array(
	'name'=> __( 'Footer 2', 'paperpunch' ),
	'id' => 'footer-2',
	'before_widget' => '<li id="%1$s" class="widget %2$s">',
	'after_widget' => '</li>',
	'before_title' => '<h2 class="widgettitle">',
	'after_title' => '</h2>',
) );
register_sidebar( array(
	'name'=> __( 'Footer 3', 'paperpunch' ),
	'id' => 'footer-3',
	'before_widget' => '<li id="%1$s" class="widget %2$s">',
	'after_widget' => '</li>',
	'before_title' => '<h2 class="widgettitle">',
	'after_title' => '</h2>',
) );
register_sidebar( array(
	'name'=> __( 'Footer 4', 'paperpunch' ),
	'id' => 'footer-4',
	'before_widget' => '<li id="%1$s" class="widget %2$s">',
	'after_widget' => '</li>',
	'before_title' => '<h2 class="widgettitle">',
	'after_title' => '</h2>',
) );

// Add support for post thumbnails
add_theme_support( 'post-thumbnails' );

// Feed links
add_theme_support( 'automatic-feed-links' );

// Enable primary menu
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'paperpunch' ),
) );

function paperpunch_page_menu() { // fallback for primary navigation ?>
<ul class="menu">
	<?php wp_list_pages( 'sort_column=menu_order&title_li=' ); ?>
</ul>
<?php }

// Enable custom background
add_custom_background();
function paperpunch_custom_background() {
	$background = get_background_image();
	$color = get_background_color();

	if ( $background || $color ) { ?><style type="text/css">
	#header, #navigation { background-color: transparent; background-image: none; }
</style>
	<?php }

	if ( !$background && $color ) { ?><style type="text/css">
	body { background-image: none; }
</style>
	<?php }
}
add_action( 'wp_head', 'paperpunch_custom_background' );

// Maximum width
$content_width = 595;

// WordPress.com ads
$themecolors = array(
	'bg' => 'ffffff',
	'border' => 'bebcad',
	'text' => '444444',
	'link' => '5785a4',
	'url' => 'e7e6ea',
);
