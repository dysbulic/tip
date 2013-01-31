<?php
/**
 * @package WordPress
 * @subpackage Matala
 */

/**
 * Set the theme colors for WordPress.com
 */
if ( ! isset( $themecolors ) ) {
$themecolors = array(
	'bg' => 'd6c08e',
	'border' => 'c5ae7c',
	'text' => '000000',
	'link' => 'd8471d',
	'url' => 'd8471d'
	);
}

/**
 * Set the maximum content width of the normal content column.
 * This prevents large images from overrunning the sides of the column.
 */
if ( ! isset( $content_width ) )
	$content_width = 692;

/**
* Load the Theme Options Page that lets users toggle the display of the Random Images photo gallery on attachments page
*/
require_once ( get_template_directory() . '/inc/theme-options.php' );

// Action hook to do all the major theme setup stuff
add_action( 'after_setup_theme', 'matala_setup' );

/**
 * Tell WordPress to run matala_setup() when the 'after_setup_theme' hook is run.
 */
if ( ! function_exists( 'matala_setup' ) ):
function matala_setup() {

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// This theme supports post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'quote', 'gallery', 'video', 'status' ) );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'matala', TEMPLATEPATH . '/languages' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'matala' ),
	) );

	// This theme allows users to set a custom background.
	add_custom_background();

	// This theme allows users to upload a custom header.
	define( 'HEADER_TEXTCOLOR', 'ffffff' );
	define( 'HEADER_IMAGE', '' );
	define( 'HEADER_IMAGE_WIDTH', 940 ); // use width and height appropriate for your theme
	define( 'HEADER_IMAGE_HEIGHT', 150 );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See mystique_admin_header_style(), below.
	add_custom_image_header( 'matala_header_style', 'matala_admin_header_style' );

	// This theme uses post thumbnails.
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'normal', 300, 300 );

	// Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
	function matala_page_menu_args($args) {
		$args['show_home'] = true;
	return $args;
	}
	add_filter( 'wp_page_menu_args', 'matala_page_menu_args' );

	// This theme styles the visual editor.
	add_editor_style('editor-style.css');

}
endif;

/**
* Add custom header support
*/
if ( ! function_exists( 'matala_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 */
function matala_header_style() {
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
			margin-top: 0;
			padding: 0 10px;
		}
		#branding hgroup {
			background: url(<?php header_image(); ?>) no-repeat;
			height: 150px;
			padding: 0 10px;
			width: 940px;
		}
		#access {
			margin: 25px 0;
		}
		#ie8 #access {
			margin: 20px 0;
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
			border: 0 !important;
			color: #<?php echo get_header_textcolor(); ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif;

if ( ! function_exists( 'matala_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in matala_setup().
 *
 */
function matala_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		background-color: #<?php echo ( '' != get_background_color() ? get_background_color() : '000' ); ?>;
		<?php if ( '' == get_header_image() && '' == get_background_color() )
			echo 'background-image: url(' . get_template_directory_uri() . '/images/bg-wrapper.jpg) !important;';
		?>
		border: none;
		width: 940px;
		height: 200px;
		text-align: left;
	}
	#headimg h1,
	#desc {
		color: #fff;
		font-family: Georgia, serif;
		font-weight: normal;
		padding-left: 10px;
	}
	#headimg h1 {
		padding-top: 45px;
	}
	#headimg h1 a {
		color: #fff;
		display: block;
		font-size: 64px;
		margin-bottom: 30px;
		text-decoration: none;
	}
	#desc {
		display: block;
		font-size: 32px;
		margin: 0;
	}
	#headimg h1 a,
	#desc {
		text-shadow: -2px -2px 4px #000;
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
function matala_widgets_init() {
	register_sidebar( array (
		'name' => __( 'Default sidebar', 'matala' ),
		'id' => 'primary-widget-area',
		'description' => __( 'The primary widget area.', 'matala' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>'
	) );

	register_sidebar( array (
		'name' => __( 'First Supplementary Widget Area', 'matala' ),
		'id' => 'first-supplementary-widget-area',
		'description' => __( 'The first widgt area appearing below the main post column.', 'matala' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>'
	) );

	register_sidebar( array (
		'name' => __( 'Second Supplementary Widget Area', 'matala' ),
		'id' => 'second-supplementary-widget-area',
		'description' => __( 'The second widget area appearing below the main post column.', 'matala' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>'
	) );
}
add_action( 'init', 'matala_widgets_init' );

/**
 * Count the number of footer sidebars to enable dynamic classes for the widgets appearing below the main post content area
 */
function matala_supplementary_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'first-supplementary-widget-area' ) )
		$count++;

	if ( is_active_sidebar( 'second-supplementary-widget-area' ) )
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
 * Removes the default styles that are packaged with the Recent Comments widget.
 */
function matala_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'matala_remove_recent_comments_style' );

if ( ! function_exists( 'matala_post_date' ) ) :
/**
 * Prints HTML with meta information for the fancy display of the current post's month and day
 */
function matala_post_date() {
	printf( __( '<div class="post-date"><span class="entry-month">%1$s</span><span class="entry-day">%2$s</span></div>', 'matala' ),
		esc_attr( get_the_time( 'M' ) ),
		esc_attr( get_the_time( 'j' ) )
	);
}
endif;

if ( ! function_exists( 'matala_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post's full date and author
 */
function matala_posted_on() {
	printf( __( '<div class="posted-on"><span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span></div>', 'matala' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'matala' ), get_the_author() ) ),
		esc_html( get_the_author() )
	);
}
endif;

if ( ! function_exists( 'matala_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 */
function matala_posted_in() {
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( '<span class="posted-in">Filed under %1$s and tagged %2$s</span> <span class="sep">|</span>', 'matala' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( '<span class="posted-in">Filed under %1$s</span> <span class="sep">|</span>', 'matala' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list
	);
}
endif;

if ( ! function_exists( 'matala_nav_below' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 */
function matala_content_nav( $nav_id ) {
	global $wp_query;

	if (  $wp_query->max_num_pages > 1 ) : ?>
	<nav id="<?php echo $nav_id; ?>">
		<h3 class="assistive-text"><?php _e( 'Post navigation', 'matala' ); ?></h3>
		<span class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older <span>posts</span>', 'matala' ) ); ?></span>
		<span class="nav-next"><?php previous_posts_link( __( 'Newer <span>posts</span> <span class="meta-nav">&rarr;</span>', 'matala' ) ); ?></span>
	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php endif;
}
endif;

if ( ! function_exists( 'matala_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 */
function matala_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'matala' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'matala' ), ' ' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 50;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 39;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s on %2$s <span class="says">said:</span>', 'matala' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s at %2$s', 'matala' ), get_comment_date(), get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( __( '[Edit]', 'matala' ), ' ' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'matala' ); ?></em>
					<br />
				<?php endif; ?>

			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply &darr;', 'matala' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for matala_comment()
function matala_header_css() {
	// Hide the theme-provided background images if the user adds a custom background image or color
	if ( '' != get_background_image() || '' != get_background_color() || '' != get_header_image() || is_attachment() ) : ?>
	<style type="text/css">
	<?php if ( '' != get_background_image() || '' != get_background_color() ) : ?>
		html,
		body,
		#page,
		#wrapper,
		#inner-wrapper,
		#colophon,
		#primary-bottom,
		#secondary-bottom,
		body.attachment #inner-wrapper,
		body.attachment #access,
		#access,
		#access .current_page_item a,
		#access li:hover > a {
			background: none;
		}
		#primary,
		#secondary {
			background-color: #fff;
		}
		#primary,
		body.attachment #primary {
			margin-top: 0;
			margin-left: 0;
		}
		body.attachment #primary {
			width: 940px;
		}
		#content,
		body.attachment #content {
			margin-top: 10px;
		}
		body.attachment #attachment-wrapper {
			margin-top: 0;
		}
		#primary-bottom,
		#secondary-bottom {
			display: none;
		}
		#secondary {
			margin-top: 100px;
		}
		#secondary-content,
		#ie7 #secondary-content {
			margin-top: 0;
		}
		#colophon {
			padding: 20px 0;
		}
	<?php endif; ?>
	</style>
	<?php endif;
}
add_action( 'wp_head', 'matala_header_css' );