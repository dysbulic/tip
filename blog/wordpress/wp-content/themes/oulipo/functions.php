<?php
/**
 * @package WordPress
 * @subpackage Oulipo
 */

require_once ( get_template_directory() . '/theme-options.php' );

if ( function_exists( 'register_sidebar' ) )
	register_sidebar();

$themecolors = array(
	'bg' => 'ffffff',
	'border' => 'eeeeee',
	'text' => '666666',
	'link' => '000000',
	'url' => 'ff9900'
);

if ( 'dark' == oulipo_current_color_scheme() ) {
	$themecolors = array(
		'bg' => '000000',
		'border' => '888888',
		'text' => 'dddddd',
		'link' => 'cccccc',
		'url' => '999999'
	);
}

$content_width = 480;

register_nav_menus( array( 'primary' => __( 'Primary Navigation', 'oulipo' ), ) );

add_theme_support( 'automatic-feed-links' );

add_custom_background();

define( 'HEADER_IMAGE', '' );
define( 'HEADER_IMAGE_WIDTH', 712 );
define( 'HEADER_IMAGE_HEIGHT', 80 );
define( 'HEADER_TEXTCOLOR', '' );
define( 'NO_HEADER_TEXT', true );

function admin_header_style() {
	?><style type="text/css">
			#headimg {
					width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
					height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
			}
	</style><?php
}

add_custom_image_header( '', 'admin_header_style' );

/**
 *  Returns the current Oulipo color scheme as selected in the theme options
 */
function oulipo_current_color_scheme() {
	$options = get_option( 'oulipo_theme_options' );

	return $options['color_scheme'];
}

/**
 * Register our color schemes and add them to the queue
 */
function oulipo_color_registrar() {
	if ( 'dark' == oulipo_current_color_scheme() ) {
		wp_register_style( 'dark', get_template_directory_uri() . '/colors/dark.css', null, null );
		wp_enqueue_style( 'dark' );
	}
}
add_action( 'wp_print_styles', 'oulipo_color_registrar' );

/**
 * Adjust fallback page menu output to match custom menu output
 */
function oulipo_page_menu( $menu ) {
	return preg_replace( '/<ul>/', '<ul class="menu">', $menu, 1 );
}
add_filter( 'wp_page_menu', 'oulipo_page_menu' );

function oulipo_page_menu_args( $args ) {
	$args['menu_class'] = 'menu-wrap';
	return $args;
}
add_filter( 'wp_page_menu_args', 'oulipo_page_menu_args' );