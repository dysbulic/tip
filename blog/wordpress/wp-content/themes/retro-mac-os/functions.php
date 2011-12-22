<?php
/**
 * @package WordPress
 * @subpackage Retro MacOS
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 438; /* pixels */

/**
 * Set a default theme color array for WP.com.
 */
$themecolors = array(
	'bg' => 'ffffff',
	'border' => '000000',
	'text' => '444444',
	'link' => '000000',
	'url' => '000000',
);

if ( ! function_exists( 'retro_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function retro_setup() {
	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Desktop Menu', 'retro' ),
	) );

	/**
	 * Add support for the Aside and Gallery Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside' ) );

	/**
	 * Add support for custom backgrounds
	 */
	add_custom_background();

	// The default header text color
	define( 'HEADER_TEXTCOLOR', '' );

	// By leaving empty, we default to random image rotation
	define( 'HEADER_IMAGE', '' );
	define( 'NO_HEADER_TEXT', true );

	// The height and width of your custom header.
	define( 'HEADER_IMAGE_WIDTH', 758 );
	define( 'HEADER_IMAGE_HEIGHT', 180 );

	// Add support for Manifest header image.
	add_custom_image_header( '', 'retro_admin_header_style' );
}
endif; // retro_setup

/**
 * Tell WordPress to run retro_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'retro_setup' );

/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 */
function retro_admin_header_style() {
?>
	<style type="text/css">
        #headimg {
            width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
            height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
        }
        #heading {
        	display: none;
        }
    </style>
<?php
}

/**
 * Register widgetized area and update sidebar with default widgets
 */
function retro_widgets_init() {

	register_sidebar( array(
		'name' => __( 'Footer Area One', 'retro' ),
		'id' => 'sidebar-1',
		'description' => __( 'An optional widget area for your site footer', 'retro' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Two', 'retro' ),
		'id' => 'sidebar-2',
		'description' => __( 'An optional widget area for your site footer', 'retro' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'init', 'retro_widgets_init' );

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function retro_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-1' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-2' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
	}

	if ( $class )
		echo 'class="' . $class . '"';
}