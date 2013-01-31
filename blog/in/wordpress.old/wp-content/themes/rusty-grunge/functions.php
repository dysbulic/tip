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
 * Set theme colors for WordPress.com.
 */
$themecolors = array(
	'bg' => 'ffffff',
	'text' => '000000',
	'link' => '4f998d',
	'border' => '322f28',
	'url' => 'ae1f10',
);

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

if ( ! function_exists( 'toolbox_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own toolbox_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Toolbox 0.4
 */
function toolbox_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer>
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, 40 ); ?>
					<?php printf( __( '%s <span class="says">says:</span>', 'rusty-grunge' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
				</div><!-- .comment-author .vcard -->
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'rusty-grunge' ); ?></em>
					<br />
				<?php endif; ?>

				<div class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s at %2$s', 'rusty-grunge' ), get_comment_date(),  get_comment_time() ); ?>
					</time></a>
					<?php edit_comment_link( __( '(Edit)', 'rusty-grunge' ), ' ' );
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
		<p><?php _e( 'Pingback:', 'rusty-grunge' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'rusty-grunge'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif; // ends check for toolbox_comment()
/**
 * This theme was built with PHP, Semantic HTML, CSS, love, and a Toolbox.
 */