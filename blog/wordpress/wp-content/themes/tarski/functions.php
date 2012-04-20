<?php

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '545454',
	'link' => '005a80'
);

$content_width = 490; //takes into account aligned images with border - 4px padding with every size + 2pix of border width

/**
 * Make theme available for translation
 * Translations can be filed in the /languages/ directory
 */
load_theme_textdomain( 'tarski', get_template_directory() . '/languages' );

$locale = get_locale();
$locale_file = get_template_directory() . "/languages/$locale.php";
if ( is_readable( $locale_file ) )
	require_once( $locale_file );

$themeData = get_theme_data( get_template_directory() . '/style.css');
$installedVersion = $themeData['Version'];
if(!$installedVersion) {
	$installedVersion = "unknown";
}

// Add default posts and comments RSS feed links to head
add_theme_support( 'automatic-feed-links' );

$highlightColor = "#a3c5cc";

// widgets!
if(function_exists('register_sidebar')) {
	register_sidebar(array(
		'name' => __( 'Main Sidebar', 'tarski' ),
		'id' => 'main-sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
	));
	register_sidebar(array(
		'name' => __( 'Footer Widgets', 'tarski' ),
		'id' => 'footer-widgets',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>'
	));
}

// No CSS, just IMG call

define('HEADER_TEXTCOLOR', '');
define('HEADER_IMAGE', '%s/images/greytree.jpg'); // %s is theme dir uri
define('HEADER_IMAGE_WIDTH', 720);
define('HEADER_IMAGE_HEIGHT', 180);
define('NO_HEADER_TEXT', true );

function tarski_admin_header_style() {
?>
<style type="text/css">
#headimg {
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
}

#headimg h1, #headimg #desc {
	display: none;
}

</style>
<?php
}

add_custom_image_header( '', 'tarski_admin_header_style' );

// Custom menu support
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'tarski' ),
) );

function tarski_page_menu() { // fallback for primary navigation ?>
<ul id="nav-1">
	<li<?php if ( is_home() ) echo " class='current_page_item'"; ?>><a title="<?php _e( 'Return to front page', 'tarski' ); ?>" href="<?php echo home_url( '/' ); ?>"><?php _e( 'Home', 'tarski' ); ?></a></li>
	<?php wp_list_pages( 'sort_column=menu_order&depth=1&title_li=' ); ?>
</ul>
<?php }

// Custom background
add_custom_background();