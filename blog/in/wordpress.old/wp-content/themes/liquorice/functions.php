<?php
/**
 * @package WordPress
 * @subpackage Liquorice
 */

/**
 * Set the theme colors for WordPress.com
 */
$themecolors = array(
	'bg' => 'f7f3ed',
	'border' => 'd1bfa6',
	'text' => '121212',
	'link' => 'cc4d22',
	'url' =>  '07818c',
);

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width  = 610;

/**
* Load the Theme Options Page that lets users control the social media icons at the top
*/
require_once ( get_template_directory() . '/inc/theme-options.php' );

/**
 * Tell WordPress to run liquorice_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'liquorice_setup' );

if ( ! function_exists( 'liquorice_setup' ) ):

function liquorice_setup() {

	// This theme uses post thumbnails.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 150, 150 );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// This theme supports post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'quote', 'gallery' ) );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'liquorice', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'liquorice' ),
	) );

	// This theme allows users to set a custom background.
	add_custom_background();

	// This theme allows users to upload a custom header.
	define( 'HEADER_TEXTCOLOR', 'cc4d22' );
	define( 'HEADER_IMAGE', '' );
	define( 'HEADER_IMAGE_WIDTH', 900 ); // use width and height appropriate for your theme
	define( 'HEADER_IMAGE_HEIGHT', 235 );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See liquorice_admin_header_style(), below.
	add_custom_image_header( 'liquorice_header_style', 'liquorice_admin_header_style' );

	// Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
	function liquorice_page_menu_args($args) {
		$args['show_home'] = true;
	return $args;
	}
	add_filter( 'wp_page_menu_args', 'liquorice_page_menu_args' );

}
endif;

if ( ! function_exists( 'liquorice_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 */
function liquorice_header_style() {
	// If no custom options for text are set, let's bail
	if ( HEADER_TEXTCOLOR == get_header_textcolor() && '' == get_header_image() )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Do we have a custom header image?
		if ( '' != get_header_image() ) :
	?>
		#branding {
			background: url(<?php header_image(); ?>);
			margin: 30px auto;
			width: 900px;
			height: 225px;
		}
		#canvas {
			background: none;
		}
	<?php
		endif;

		// Has the text been hidden?
		if ( 'blank' == get_header_textcolor() ) :
	?>
		#site-title,
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
			background: none !important;
			color: #<?php echo get_header_textcolor(); ?> !important;
			line-height: 1.2 !important;
			text-shadow: none;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif;

if ( ! function_exists( 'liquorice_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in liquorice_setup().
 *
 */
function liquorice_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		background: #<?php echo get_background_color(); ?>;
		border: none;
		width: 900px;
		height: 225px;
		text-align: center;
	}
	#headimg h1,
	#desc {
		font-family: 'Lobster', 'lobster-1', 'lobster-2', Georgia, 'Times New Roman', Times, serif;
	}
	#headimg h1 {
		margin: 0;
	}
	#headimg h1 a {
		font-size: 80px;
		line-height: 1.3;
		text-decoration: none;
	}
	#desc {
		font-size: 31px;
		padding: 0 0 9px 0;
	}
	<?php
		// If the user has set a custom color for the text use that
		if ( HEADER_TEXTCOLOR != get_header_textcolor() ) :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	</style>
<?php
}
endif;

/**
 * Register widgetized area and update sidebar with default widgets
 */
function liquorice_widgets_init() {
	register_sidebar( array (
		'name' => __( 'Sidebar', 'liquorice' ),
		'id' => 'primary',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => "</li>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

}
add_action( 'init', 'liquorice_widgets_init' );

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 */
function liquorice_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'liquorice_remove_recent_comments_style' );

if ( ! function_exists( 'liquorice_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post's date/time and author.
 */
function liquorice_posted_on() {
	// use the "byline" class to hide the author name and link.
	printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="%4$s"><span class="meta-sep">by</span> %3$s</span>', 'liquorice' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'liquorice' ), get_the_author() ) ),
			get_the_author()
		),
		'byline'
	);
}
endif;

if ( ! function_exists( 'liquorice_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 */
function liquorice_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'Posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'liquorice' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'Posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'liquorice' );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'liquorice' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;

if ( ! function_exists( 'liquorice_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own liquorice_comment(), and that function will be used instead.
 *
 */
function liquorice_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 32 ); ?>

			<cite class="fn"><?php comment_author_link(); ?></cite>

			<span class="comment-meta commentmetadata">
				|
				<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
				<?php
					/* translators: 1: date, 2: time */
					printf( __( '%1$s at %2$s', 'liquorice' ),
						get_comment_date(),
						get_comment_time()
					); ?></a>
					<?php edit_comment_link( __( 'Edit', 'liquorice' ), ' | ' );
				?>
			</span><!-- .comment-meta .commentmetadata -->
		</div><!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em><?php _e( 'Your comment is awaiting moderation.', 'liquorice' ); ?></em>
			<br />
		<?php endif; ?>

		<div class="comment-body">
			<?php comment_text(); ?>
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div>

	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'liquorice' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'liquorice' ), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;

function liquorice_header_css() {
	// Hide the theme-provided background images if the user adds a custom background image or color
	if ( '' != get_background_image() || '' != get_background_color() ) : ?>
	<style type="text/css">
	     body,
	     #canvas,
		.nav-previous a,
		.previous-image a,
		.nav-next a,
		.next-image a,
		#secondary-content {
			background: none;
		}
		#secondary-content {
			border-width: 0;
		}
	<?php
		// Make the bg of the post date/author match the site bg.
		if ( '' != get_background_image() ) : ?>
		#primary-content .date small {
			background: url(<?php background_image(); ?>);
		}
	<?php elseif ( '' != get_background_color() ) : ?>
		#primary-content .date small {
			background: #<?php background_color(); ?> !important;
		}
	<?php endif; ?>
		.nav-previous a,
		.previous-image a,
		.nav-next a,
		.next-image a {
			padding: 0;
		}
		.meta-nav {
			display: inline;
		}
	</style>
	 <?php endif;
}
add_action( 'wp_head', 'liquorice_header_css' );