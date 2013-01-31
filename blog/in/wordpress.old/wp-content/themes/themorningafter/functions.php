<?php
/**
 * @package WordPress
 * @subpackage The Morning After
 */

// Set the content width based on the Theme CSS
if ( ! isset( $content_width ) )
	$content_width = 750;

$themecolors = array(
	'bg' => 'ffffff',
	'border' => 'ebeff2',
	'text' => '333333',
	'link' => '3a6999',
	'url' => 'a11b1b',
);

/** Tell WordPress to run morningafter_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'morningafter_setup' );

if ( ! function_exists( 'morningafter_setup' ) ):

function morningafter_setup() {

	// Load up the theme options
	require( dirname( __FILE__ ) . '/inc/theme-options.php' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();
	
	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );
	
	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );
	
	//Add support for the Aside Formats
	add_theme_support( 'post-formats', array( 'aside' ) );	
		
	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'woothemes', TEMPLATEPATH . '/languages' );
	
	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'woothemes' ),
	) );
	
	// This theme allows users to set a custom background
	add_custom_background();
	
	// Your changeable header business starts here
	if ( ! defined( 'HEADER_TEXTCOLOR' ) )
		define( 'HEADER_TEXTCOLOR', '' );
	
	// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
	define( 'HEADER_IMAGE', '%s/images/headers/book.png' );

	// The height and width of your custom header. You can hook into the theme's own filters to change these values.
	// Add a filter to morningafter_header_image_width and morningafter_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'morningafter_header_image_width',  960 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'morningafter_header_image_height',	70 ) );

	// Don't support text inside the header image.
	if ( ! defined( 'NO_HEADER_TEXT' ) )
		define( 'NO_HEADER_TEXT', true );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See morningafter_admin_header_style(), below.
	add_custom_image_header( '', 'morningafter_admin_header_style' );

	// ... and thus ends the changeable header business.

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( array (
		'book' => array (
			'url' => '%s/images/headers/book.png',
			'thumbnail_url' => '%s/images/headers/book_thumb.png',
			'description' => __( 'Book', 'woothemes' )
		),
		'sky' => array (
			'url' => '%s/images/headers/sky.png',
			'thumbnail_url' => '%s/images/headers/sky_thumb.png',
			'description' => __( 'Sky', 'woothemes' )
		),
		'road' => array (
			'url' => '%s/images/headers/road.png',
			'thumbnail_url' => '%s/images/headers/road_thumb.png',
			'description' => __( 'Road', 'woothemes' )
		)
	) );
	
	// Set the thumbnail size used in Featured Posts in home page
	$morningafter_options = morningafter_get_theme_options();
	$thumb_size = $morningafter_options['featured_thumb'];
	add_image_size('featured_thumbnail', $thumb_size, $thumb_size, true);		
}
endif;

if ( ! function_exists( 'morningafter_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in morningafter_setup().
 *
 * @since 3.0.0
 */
function morningafter_admin_header_style() {
?>
<style type="text/css">
#headimg {
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
}
#headimg h1, #headimg #desc {
	display: none;
}
.appearance_page_custom-header #headimg {
	min-height:1px;
}
</style>
<?php
}
endif;

//Changed wp_page_menu structure to get rid of the wrapped div and add menu_class arguments to <ul>
function morningafter_add_menu_class ( $page_markup ) {
	preg_match( '/^<div class=\"([a-z0-9-_ ]+)\">/i', $page_markup, $matches );
	$divclass = $matches[1];
	$toreplace = array( '<div class="'.$divclass.'">', '</div>' );
	$new_markup = str_replace( $toreplace, '', $page_markup );
	$new_markup = preg_replace( '/^<ul>/i', '<ul class="'.$divclass.'">', $new_markup );
	return $new_markup;
}
add_filter( 'wp_page_menu', 'morningafter_add_menu_class' );

// Register widgetized areas
function morningafter_widgets_init() {
	register_sidebar( array(
		'name'=>__( 'Primary Sidebar', 'woothemes' ),
		'id' => 'primary-sidebar',
		'description' => __( 'The primary widget area.', 'woothemes' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="mast">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name'=>__( 'Secondary Sidebar', 'woothemes' ),
		'id' => 'secondary-sidebar',
		'description' => __( 'The widget area only appears on the home page.', 'woothemes' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="mast">',
		'after_title' => '</h3>',
	) );
	
	register_sidebar( array(
		'name'=>__( 'Home Page Feature Widget Area', 'woothemes' ),
		'id' => 'feature-widget-area',
		'description' => __( 'The feature widget area above the sidebars on the home page.', 'woothemes' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="mast">',
		'after_title' => '</h3>',
	) );	
}

add_action( 'init', 'morningafter_widgets_init' );

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @return string "Continue Reading" link
 */
function morningafter_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&raquo;</span>', 'woothemes' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and morningafter_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @return string An ellipsis
 */
function morningafter_auto_excerpt_more( $more ) {
	return ' &hellip;' . morningafter_continue_reading_link();
}
add_filter( 'excerpt_more', 'morningafter_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function morningafter_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= morningafter_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'morningafter_custom_excerpt_more' );

/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in The Morning After's style.css. This is just
 * a simple filter call that tells WordPress to not use the default styles.
 *
 */
add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Template for comments and pingbacks.
 *
 */
function custom_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment; ?>

	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">

		<div class="commentcont" id="comment-<?php comment_ID(); ?>">
		
			<?php if ( get_comment_type() == "comment" ) { ?>
				<div class="fright"><?php echo get_avatar( $comment, 40 ); ?></div>
			<?php } ?>

			<?php comment_text(); ?>

			<p>
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation','woothemes' );?>.</em>
				<?php endif; ?>
			</p>

			<cite>
				<?php _e( 'Posted by','woothemes' ); ?> <span class="commentauthor"><?php comment_author_link(); ?></span> | <a href="#comment-<?php comment_ID(); ?>" title=""><?php comment_date( get_option( 'date_format' ) ); ?>, <?php comment_time(); ?></a> <?php edit_comment_link( 'edit','| ','' ); ?>
			</cite>
		
		</div>
		
		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'reply_text' => 'Reply to this comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div>
<?php }

// PINGBACK / TRACKBACK OUTPUT
function list_pings( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment; ?>

	<li id="comment-<?php comment_ID(); ?>" class="post pingback">
		<p><?php _e( 'Pingback:', 'woothemes' ); ?> <span class="author"><?php comment_author_link(); ?></span> - <span class="date"><?php echo get_comment_date(); ?></span> <span class="edit"><?php edit_comment_link( __( 'Edit', 'woothemes' ), '<span class="edit-link">', '</span>' ); ?></span></p>

<?php }

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link depeding on the user's choice.
 */
function morningafter_page_menu_args( $args ) {
	$morningafter_options = morningafter_get_theme_options();
	if ( $morningafter_options['show_home_link'] == 1 ) {
		$args['show_home'] = true;
	}else{
		$args['show_home'] = false;
	}
	return $args;
}
add_filter( 'wp_page_menu_args', 'morningafter_page_menu_args' );

/**
 * Set comment form default parameters
 */
function morning_after_comment_form_defaults( $args ) {
	$args['title_reply'] = __( 'Leave a Comment', 'woothemes' );
	return $args;
}
add_filter( 'comment_form_defaults', 'morning_after_comment_form_defaults' );
