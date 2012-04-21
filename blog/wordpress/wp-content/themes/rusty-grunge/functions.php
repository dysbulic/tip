<?php
/**
 * @package WordPress
 * @subpackage Rusty Grunge
 */

/**
 * Make theme available for translation
 * Translations can be filed in the /languages/ directory
 */
load_theme_textdomain( 'rusty-grunge', TEMPLATEPATH . '/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable( $locale_file ) )
	require_once( $locale_file );

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 538; /* pixels */

/**
 * This theme uses wp_nav_menu() in one location.
 */
register_nav_menus( array(
	'primary' => __( 'Primary Menu', 'rusty-grunge' ),
) );

function toolbox_css_body_class( $classes ) {
	global $post;

	if ( is_page() || is_single() )
		$classes[] = sanitize_title_with_dashes ( get_the_title( $post->ID ) );

	return $classes;
}
add_filter( 'body_class', 'toolbox_css_body_class' );

/**
 * Custom Header Image.
 */

define( 'HEADER_IMAGE', '' );
define( 'HEADER_IMAGE_WIDTH', 658 );
define( 'HEADER_IMAGE_HEIGHT', 240 );
define( 'HEADER_TEXTCOLOR', '' );
define( 'NO_HEADER_TEXT', true );

add_custom_image_header( '', 'admin_header_style' );

function admin_header_style() { 
	?><style type="text/css">
		#headimg {
			width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
			height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
		}
	</style><?php
}

/**
 * Add Custom Background
 */

add_custom_background();

// Allow custom colors to clear the background image
function rustygrunge_custom_background_color() {
	if ( get_background_image() == '' && get_background_color() != '' ) { ?>
		<style type="text/css">
		body {
			background-image: none;
		}
		</style>
	<?php }
}
add_action( 'wp_head', 'rustygrunge_custom_background_color' );

/**
 * Add default posts and comments RSS feed links to head
 */
add_theme_support( 'automatic-feed-links' );

/**
 * Add support for the Aside and Gallery Post Formats
 */
add_theme_support( 'post-formats', array( 'aside', 'gallery', 'quote', 'status' ) );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function toolbox_page_menu_args($args) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'toolbox_page_menu_args' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function toolbox_widgets_init() {
	register_sidebar( array (
		'name' => __( 'Sidebar 1', 'rusty-grunge' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );

	register_sidebar( array (
		'name' => __( 'Sidebar 2', 'rusty-grunge' ),
		'id' => 'sidebar-2',
		'description' => __( 'An optional second sidebar area', 'rusty-grunge' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'init', 'toolbox_widgets_init' );

/**
 * This theme was built with PHP, Semantic HTML, CSS, love, and a Toolbox.
 */