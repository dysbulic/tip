<?php
/**
 * @package WordPress
 * @subpackage Manifest
 */

/**
 * Set theme colors for WP.com.
 */
$themecolors = array(
	'bg' => 'fff',
	'border' => 'eeeeee',
	'text' => '444444',
	'link' => '9c8a6a',
	'url' => '9c8a6a'
);

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 500; /* pixels */

/**
 * Tell WordPress to setup Manifest when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'manifest_setup' );

function manifest_setup() {
	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Add support for the Aside and Gallery Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'image', 'link', 'status' ) );

	// The default header text color
	define( 'HEADER_TEXTCOLOR', '' );

	// By leaving empty, we default to random image rotation
	define( 'HEADER_IMAGE', '' );
	define( 'NO_HEADER_TEXT', true );

	// The height and width of your custom header.
	define( 'HEADER_IMAGE_WIDTH', 500 );
	define( 'HEADER_IMAGE_HEIGHT', 160 );

	// Add support for Manifest header image.
	add_custom_image_header( '', 'manifest_admin_header_style' );
}

/**
 * Manifest uses wp_nav_menu() in one location.
 */
register_nav_menus( array(
	'primary' => __( 'Primary Menu', 'manifest' ),
) );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu().
 */
function manifest_page_menu() { // fallback for primary navigation ?>
	<ul>
		<?php wp_list_pages( 'title_li=&depth=1' ); ?>
	</ul>
<?php }

/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 */
function manifest_admin_header_style() {
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
 * Register our sidebars and widgetized areas.
 */
function manifest_widgets_init() {

	register_sidebar( array(
		'name' => __( 'Footer area one', 'manifest' ),
		'id' => 'sidebar-1',
		'description' => __( 'An optional widget area for your site footer', 'manifest' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h4 class="widget-title">',
		'after_title' => '</h4>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer area two', 'manifest' ),
		'id' => 'sidebar-2',
		'description' => __( 'An optional widget area for your site footer', 'manifest' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h4 class="widget-title">',
		'after_title' => '</h4>',
	) );

}
add_action( 'widgets_init', 'manifest_widgets_init' );

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function manifest_footer_sidebar_class() {
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

/**
 * Grab the first URL from a Link post
 */
function manifest_url_grabber() {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
		return false;

	return esc_url_raw( $matches[1] );
}