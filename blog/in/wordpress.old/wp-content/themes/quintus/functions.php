<?php
/**
 * @package WordPress
 * @subpackage Quintus
 */

/**
 * Make theme available for translation
 * Translations can be filed in the /languages/ directory
 * If you're building a theme based on Quintus, use a find and replace
 * to change 'Quintus' to the name of your theme in all the template files
 */
load_theme_textdomain( 'quintus', TEMPLATEPATH . '/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable( $locale_file ) )
	require_once( $locale_file );

/**
 * Load up our theme options.
 */
require( dirname( __FILE__ ) . '/inc/theme-options.php' );

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

/**
 * Set a default theme color array for WP.com.
 */
$themecolors = array(
	'bg' => 'f7f3ee',
	'border' => 'e9e0d1',
	'text' => '333333',
	'link' => '5e191a',
	'url' => 'dac490',
);

/**
 * This theme uses wp_nav_menu() in one location.
 */
register_nav_menus( array(
	'primary' => __( 'Primary Menu', 'quintus' ),
) );

/**
 * Add default posts and comments RSS feed links to head.
 */
add_theme_support( 'automatic-feed-links' );

/**
 * Add support for the Aside and Gallery Post Formats
 */
add_theme_support( 'post-formats', array( 'aside', 'link', 'quote' ) );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function quintus_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'quintus_page_menu_args' );

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function quintus_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar 1', 'quintus' ),
		'id' => 'sidebar-1',
		'description' => __( 'This is Quintus\' widget sidebar. Leave empty if you want a one column layout.', 'quintus' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'init', 'quintus_widgets_init' );

/**
 * Custom Header.
 */
define( 'HEADER_TEXTCOLOR', 'ffffff' );

// By leaving empty, we default to random image rotation.
define( 'HEADER_IMAGE', '' );

define( 'HEADER_IMAGE_WIDTH', 1100 );
define( 'HEADER_IMAGE_HEIGHT', 250 );

add_custom_image_header( 'quintus_header_style', 'quintus_admin_header_style' );

/**
 * Styles the header image.
 */
function quintus_header_style() {

	$header_image = get_header_image();
	if ( ! empty( $header_image ) ) :

	?>
	<style type="text/css">
		.blog-header {
			background: #181818 url(<?php header_image(); ?>) no-repeat top center !important;
			text-align: center;
		}
		.blog-header hgroup {
			background: url(<?php echo get_template_directory_uri(); ?>/images/header.jpg) repeat;
			-moz-border-radius: 3px;
			border-radius: 3px;
			display: inline-block;
			margin: 0 auto;
			padding: 0 40px;
		}
		#site-title, #site-description {
			display: block;
		}
		#site-title a:hover {
			border-top-color: transparent;
		}
		<?php if ( HEADER_TEXTCOLOR != get_header_textcolor() ) : ?>
		.blog-header hgroup {
			background: none;
		}
		#site-description {
			font-weight: 300;
		}
		<?php endif; ?>
	</style>
	<?php

	endif;

	if ( empty( $header_image ) ) : ?>
		<style type="text/css">
			#ie7 .blog-header hgroup {
				display: block;
			}
		</style>
	<?php endif;

	if ( HEADER_TEXTCOLOR != get_header_textcolor() ) :
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == get_header_textcolor() ) :
	?>
		.blog-header hgroup {
			display: block;
			padding: 0;
		}
		#site-title,
		#site-title a {
			color: transparent;
			display: block;
			font-size: 0;
			max-width: 100%;
			min-height: 250px;
			padding: 0;
		}
		#site-title a:hover {
			border: none;
		}
		#site-description {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?>
		}
	<?php endif; ?>
	</style>
	<?php

	endif;
}

/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 */
function quintus_admin_header_style() {
?>
	<style type="text/css">
        #headimg {
		background-color: #<?php echo ( '' != get_background_color() ? get_background_color() : '000' ); ?>;
			<?php if ( '' == get_header_image() && '' == get_background_color() )
				echo 'background-image: url(' . get_template_directory_uri() . '/images/header.jpg) !important;';
			?>
            width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
            height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
            text-align: center;
        }
        #heading {
        	display: none;
        }
		#headimg h1 {
			margin-top: 90px;
		}
		#headimg h1 a {
			font-family: Georgia, serif;
			font-size: 52px;
			font-weight: normal;
			text-decoration: none;
		}
		#desc {
			color: #8f8f8f;
			font-family: 'Helvetica Neue', Helvetica, sans-serif;
			font-size: 17px;
			font-weight: 100;
		}
	</style>
<?php
}

/**
 * Enqueue font styles.
 */
function quintus_fonts() {
	wp_enqueue_style( 'lato', 'http://fonts.googleapis.com/css?family=Lato:100,400,700&v2' );
}
add_action( 'wp_enqueue_scripts', 'quintus_fonts' );

/**
 * Dequeue font styles.
 */
function quintus_dequeue_fonts() {

	/**
	 * We don't want to enqueue the font scripts if the blog
	 * has WP.com Custom Design and is using a 'Headings' font.
	 */
	if ( class_exists( 'TypekitData' ) ) {

		if ( TypekitData::get( 'upgraded' ) ) {
			$customfonts = TypekitData::get( 'families' );
			if ( ! $customfonts )
				return;
			$headings = $customfonts['headings'];

			if ( $headings['id'] ) {
				wp_dequeue_style( 'lato' );
			}
		}
	}
}
add_action( 'wp_enqueue_scripts', 'quintus_dequeue_fonts' );

/**
 * Grab the first URL from a Link post
 */
function quintus_url_grabber() {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
		return false;

	return esc_url_raw( $matches[1] );
}

/**
 * Add special classes to the WordPress body class.
 */
function quintus_body_classes( $classes ) {

	// If we have one sidebar active we have secondary content
	if ( ! is_active_sidebar( 'sidebar-1' ) )
		$classes[] = 'one-column';

	return $classes;
}
add_filter( 'body_class', 'quintus_body_classes' );

/**
 * Add some useful default widgets to the Quintus sidebar
 */
function quintus_default_widgets() {
	$sidebars = get_option( 'sidebars_widgets' );

	if ( empty ( $sidebars['sidebar-1'] ) && isset( $_GET['activated'] ) ) {
		update_option( 'widget_links', array( 2 => array( 'title' => __( 'Blogs I Read', 'quintus' ) ), '_multiwidget' => 1 ) );
		update_option( 'widget_categories', array( 2 => array( 'title' => __( 'Topics', 'quintus' ) ), '_multiwidget' => 1 ) );
		update_option( 'widget_archives', array( 2 => array( 'title' => __( 'Archives', 'quintus' ) ), '_multiwidget' => 1 ) );

		update_option( 'sidebars_widgets', array(
			'wp_inactive_widgets' => array(),
			'sidebar-1' => array(
				0 => 'links-2',
				1 => 'categories-2',
				2 => 'archives-2',
			),
			'array_version' => 3
		) );
	}
}
add_action( 'after_setup_theme', 'quintus_default_widgets' );

/**
 * Add custom background support.
 */
add_custom_background();

/**
 * Allow a solid background color.
 */
function quintus_solid_background_color() {
	if ( get_background_image() == '' && get_background_color() != '' ) { ?>
		<style type="text/css">
		body {
			background-image: none;
		}
		</style>
	<?php }
}
add_action( 'wp_head', 'quintus_solid_background_color' );

/**
 *  Returns the current Quintus theme options, with default values as fallback.
 */
function quintus_get_theme_options() {
	$defaults = array(
		'color_scheme' => 'default',
	);
	$options = get_option( 'quintus_theme_options', $defaults );

	return $options;
}

/**
 *  Returns the current Quintus color scheme as selected in the theme options.
 */
function quintus_current_color_scheme() {
	$options = quintus_get_theme_options();
	return $options['color_scheme'];
}

/**
 * Register our color scheme and add them to the queue.
 */
function quintus_color_registrar() {
	$color_scheme = quintus_current_color_scheme();

	if ( 'default' == $color_scheme )
		return;

	wp_enqueue_style( $color_scheme, get_template_directory_uri() . '/colors/' . $color_scheme . '.css', null, null );
}
add_action( 'wp_enqueue_scripts', 'quintus_color_registrar' );

/**
 * Adjust the content_width value based on current template.
 *
 */
function quintus_set_full_content_width() {
	global $content_width;
	$content_width = 940;
}

/**
 * This theme was built with PHP, Semantic HTML, CSS, love, and a Toolbox.
 */