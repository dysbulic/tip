<?php
/**
 * @package WordPress
 * @subpackage Fruit Shake
 */

/**
 * Make theme available for translation
 * Translations can be filed in the /languages/ directory
 * If you're building a theme based on toolbox, use a find and replace
 * to change 'fruit-shake' to the name of your theme in all the template files
 */
load_theme_textdomain( 'fruit-shake', TEMPLATEPATH . '/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable( $locale_file ) )
	require_once( $locale_file );

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

/**
 * Load up our theme options
 */
require( dirname( __FILE__ ) . '/inc/theme-options/theme-options.php' );

/**
 * Load up our fruitylicious custom widget
 */
require( dirname( __FILE__ ) . '/inc/widgets.php' );

/**
 * This theme uses wp_nav_menu() in one location.
 */
register_nav_menus( array(
	'primary' => __( 'Primary Menu', 'fruit-shake' ),
) );

/**
 * Add default posts and comments RSS feed links to head
 */
add_theme_support( 'automatic-feed-links' );

/**
 * Add support for the Aside and Gallery Post Formats
 */
add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'status', 'quote' ) );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function fruit_shake_page_menu_args($args) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'fruit_shake_page_menu_args' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function fruit_shake_widgets_init() {
	register_sidebar( array (
		'name' => __( 'Sidebar 1', 'fruit-shake' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area One', 'fruit-shake' ),
		'id' => 'sidebar-2',
		'description' => __( 'An optional widget area for your site footer', 'fruit-shake' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Two', 'fruit-shake' ),
		'id' => 'sidebar-3',
		'description' => __( 'An optional widget area for your site footer', 'fruit-shake' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Three', 'fruit-shake' ),
		'id' => 'sidebar-4',
		'description' => __( 'An optional widget area for your site footer', 'fruit-shake' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'init', 'fruit_shake_widgets_init' );

/**
 * Add some useful default widgets to the Fruit Shake sidebar
 */
function fruit_shake_default_widgets() {
	$sidebars = get_option( 'sidebars_widgets' );

	if ( empty ( $sidebars['sidebar-1'] ) && isset( $_GET['activated'] ) ) {
		update_option( 'widget_pages', array( 2 => array( 'title' => __( 'Menu', 'fruit-shake' ) ), '_multiwidget' => 1 ) );
		update_option( 'widget_categories', array( 2 => array( 'title' => __( 'Topics', 'fruit-shake' ) ), '_multiwidget' => 1 ) );
		update_option( 'widget_archives', array( 2 => array( 'title' => __( 'Archives', 'fruit-shake' ) ), '_multiwidget' => 1 ) );
		update_option( 'widget_meta', array( 2 => array( 'title' => __( 'Meta', 'fruit-shake' ) ), '_multiwidget' => 1 ) );

		update_option( 'sidebars_widgets', array(
			'wp_inactive_widgets' => array(),
			'sidebar-1' => array(
				0 => 'pages-2',
				1 => 'categories-2',
				2 => 'archives-2',
				3 => 'meta-2',
			),
			'array_version' => 3
		) );
	}
}
add_action( 'after_setup_theme', 'fruit_shake_default_widgets' );

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function fruit_shake_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-2' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-3' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-4' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
	}

	if ( $class )
		echo 'class="' . $class . '"';
}

/**
 * Add support for custom backgrounds
 */
add_custom_background();


/**
 * The custom header image business
 */

// Check the current fruit scheme and return a default header image that matches it
function fruit_shake_default_header_image() {
	$fruit_scheme = fruit_shake_current_fruit_scheme();
	return get_template_directory_uri() . '/images/headers/header-' . $fruit_scheme . '.png';
}

// The default header text color
define( 'HEADER_TEXTCOLOR', '000' );

// The default header image
define( 'HEADER_IMAGE', '' );

// The height and width of your custom header.
define( 'HEADER_IMAGE_WIDTH', 980 );
define( 'HEADER_IMAGE_HEIGHT', 285 );

// No header text controls here
define('NO_HEADER_TEXT', true );

// Add a way for the custom header to be styled in the admin panel that controls
// custom headers. See fruit_shake_admin_header_style(), below.
add_custom_image_header( 'fruit_shake_header_style', 'fruit_shake_admin_header_style' );

// ... and thus ends the changeable header business.

function fruit_shake_header_style() {}

function fruit_shake_admin_header_style() {}

/**
 * Returns a "Continue Reading" link for excerpts
 */
function fruit_shake_continue_reading_link() {
	return ' <a class="more-link" href="'. esc_url( get_permalink() ) . '">' . __( 'Keep&nbsp;reading&nbsp;<span class="meta-nav">&rarr;</span>', 'fruit-shake' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and fruit_shake_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
function fruit_shake_auto_excerpt_more( $more ) {
	return ' &hellip;' . fruit_shake_continue_reading_link();
}
add_filter( 'excerpt_more', 'fruit_shake_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
function fruit_shake_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= fruit_shake_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'fruit_shake_custom_excerpt_more' );

/**
 * Grab the first URL from a Link post
 */
function fruit_shake_url_grabber() {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
		return false;

	return esc_url_raw( $matches[1] );
}

/**
 *  Returns the current fruit shake theme options, with default values as fallback
 *
 * @since fruit shake 1.0
 */
function fruit_shake_get_theme_options() {
	$defaults = array(
		'fruit_scheme' => 'banana',
	);
	$options = get_option( 'fruit_shake_theme_options', $defaults );

	return $options;
}

/**
 *  Returns the current fruit shake color scheme as selected in the theme options
 *
 * @since fruit shake 1.0
 */
function fruit_shake_current_fruit_scheme() {
	$options = fruit_shake_get_theme_options();
	return $options['fruit_scheme'];
}

/**
 * Register our color schemes and add them to the queue
 */
function fruit_shake_color_registrar() {
	$fruit_scheme = fruit_shake_current_fruit_scheme();

	if ( 'banana' == $fruit_scheme )
		return;

	wp_enqueue_style( $fruit_scheme, get_template_directory_uri() . '/colors/' . $fruit_scheme . '.css', null, null );
}
add_action( 'wp_enqueue_scripts', 'fruit_shake_color_registrar' );

/**
 * Add special fruity classes to the WordPress body class
 */
function fruit_shake_body_classes( $classes ) {
	// We should always have content
	$classes[] = 'primary';

	// If we have 1 sidebar active we have secondary content
	if ( is_active_sidebar( 'sidebar-1' ) )
		$classes[] = 'secondary';

	/**
	 * What's going on here?
	 * If there is a 'secondary' class we can override our basic CSS structure to make a 2-column layout
	 * adding some page width and some CSS to accommodate one widget area
	 */

	// A useful class for styling Post Formats that aren't viewn as single posts
	if ( ! is_singular() )
		$classes[] = 'indexed';

	return $classes;
}
add_filter( 'body_class', 'fruit_shake_body_classes' );

/**
 * Set the default theme colors based on the current color scheme
 */
$fruit_scheme = fruit_shake_current_fruit_scheme();

switch ( $fruit_scheme ) {
	case 'blueberry':
		$themecolors = array(
			'bg' => '0a0a0a',
			'border' => '282828',
			'text' => 'd8d8cd',
			'link' => '1c9bdc',
			'url' => '1c9bdc'
		);
		break;

	case 'dragon-fruit':
		$themecolors = array(
			'bg' => '29241b',
			'border' => '3a3121',
			'text' => '9f9c80',
			'link' => 'b58942',
			'url' => 'b58942'
		);
		break;

	case 'brown-banana':
		$themecolors = array(
			'bg' => 'b62413',
			'border' => 'e23817',
			'text' => 'fae8e6',
			'link' => 'b58942',
			'url' => 'b58942'
		);
		break;

	default:
		$themecolors = array(
			'bg' => 'ffffff',
			'border' => 'bbbbbb',
			'text' => '333333',
			'link' => '1c9bdc',
			'url' => '1c9bdc'
		);
		break;
}

/**
 * Return the number of daily posts in the last week
 */
function fruit_shake_daily_posts_in_last_week() {
	global $wpdb;

	$post_date = date( 'Y-m-d h:i:s', strtotime( '-1 weeks' ) );

	$querystr = $wpdb->prepare(
		"SELECT COUNT( DISTINCT ( SUBSTRING( post_date, 1, 10 ) ) ) FROM $wpdb->posts WHERE post_date > %s AND post_type = 'post'",
		$post_date
	);

	$daily_posts_last_week = $wpdb->get_var( $querystr );

	return (int) $daily_posts_last_week;
}

if ( ! function_exists( 'fruit_shake_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own fruit_shake_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Fruit Shake 0.4
 */
function fruit_shake_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer>
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, 40 ); ?>
					<cite class="fn"><?php comment_author_link(); ?></cite>
				</div><!-- .comment-author .vcard -->
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'fruit-shake' ); ?></em>
					<br />
				<?php endif; ?>

				<div class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s at %2$s', 'fruit-shake' ), get_comment_date(),  get_comment_time() ); ?>
					</time> <span class="infin">&infin;</span></a>
					<?php edit_comment_link( __( '[Edit]', 'fruit-shake' ), ' <span class="edit-comment-link">', '</span>' );
					?>
				</div><!-- .comment-meta .commentmetadata -->
			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'fruit-shake' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'fruit-shake'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif; // ends check for fruit_shake_comment()

/**
 * Filter comment_form() defaults here instead of in comments.php
 * so that plugins can override the settings.
 */
function fruit_shake_comment_form_defaults( $args ) {
	$args['cancel_reply_link'] = __( '[Cancel reply]', 'fruit-shake' );
	return $args;
}
add_filter( 'comment_form_defaults', 'fruit_shake_comment_form_defaults' );

/**
 * This theme was built with PHP, Semantic HTML, CSS, love, and a Toolbox.
 */