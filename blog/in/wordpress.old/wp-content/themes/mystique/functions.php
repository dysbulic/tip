<?php
/**
 * @package WordPress
 * @subpackage Mystique
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = mystique_attachment_width();

/**
 * Tell WordPress to run mystique_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'mystique_setup' );

if ( ! function_exists( 'mystique_setup' ) ):

function mystique_setup() {

	// This theme has an options page that lets users pick layout, color scheme, featured post title text and configure a twitter icon
	require_once( dirname( __FILE__ ) . '/inc/theme-options.php' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// This theme supports post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'quote', 'gallery' ) );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'mystique', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'mystique' ),
	) );

	// This theme allows users to set a custom background.
	add_custom_background();

	// This theme allows users to upload a custom header.
	define( 'HEADER_TEXTCOLOR', 'ffffff' );
	define( 'HEADER_IMAGE', '' );
	define( 'HEADER_IMAGE_WIDTH', 940 ); // use width and height appropriate for your theme
	define( 'HEADER_IMAGE_HEIGHT', 200 );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See mystique_admin_header_style(), below.
	add_custom_image_header( 'mystique_header_style', 'mystique_admin_header_style' );

	// This theme uses post thumbnails.
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'normal', 90, 70, true ); // normal thumbnail size
	add_image_size( 'large-feature', HEADER_IMAGE_WIDTH, 240, true ); // Used for featured images in the header, below the menu (sticky posts)

	// Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
	function mystique_page_menu_args($args) {
		$args['show_home'] = true;
	return $args;
	}
	add_filter( 'wp_page_menu_args', 'mystique_page_menu_args' );

}
endif;

/**
 * Returns the current Mystique theme options, with default values as fallback
 *
 */
function mystique_get_theme_options() {
	$defaults = array(
		'color_scheme' => 'green',
		'theme_layout' => 'content-sidebar',
		'show_rss_link' => 0,
		'twitter_link' => '',
		'facebook_link' => '',
		'flickr_link' => '',
		'youtube_link' => '',
		'featured_post_label' => 'Featured:',
		'featured_post_home_only' => 0,
	);
	$options = get_option( 'mystique_theme_options', $defaults );

	return $options;
}

function mystique_attachment_width() {
	$options = mystique_get_theme_options();
	$current_layout = $options['theme_layout'];

	$three_columns = array( 'content-sidebar-sidebar', 'sidebar-sidebar-content', 'sidebar-content-sidebar' );
	$no_columns = array( 'no-sidebar' );

	if ( in_array( $current_layout, $three_columns ) )
		return 480;
	elseif ( in_array( $current_layout, $no_columns ) )
		return 914;
	else
		return 604;
}

/**
* Add custom header support
*/
if ( ! function_exists( 'mystique_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 */
function mystique_header_style() {
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
			width: 940px;
			height: 148px; /* 200 - 52 for top padding */
		}
		#page {
			background: none;
		}
	<?php
		endif;

		// Has the text been hidden?
		if ( 'blank' == get_header_textcolor() ) :
	?>
		#logo,
		#site-description {
 	 		position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		#logo a,
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

if ( ! function_exists( 'mystique_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in mystique_setup().
 *
 */
function mystique_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		background-color: #<?php echo ( '' != get_background_color() ? get_background_color() : '000' ); ?>;
		<?php if ( '' == get_header_image() && '' == get_background_color() )
			echo 'background-image: url(' . get_template_directory_uri() . '/images/header.jpg) !important;';
		?>
		border: none;
		width: 940px;
		height: 200px;
		text-align: left;
	}
	#headimg h1 {
		float: left;
		font-family: 'Myriad Pro', 'myriad-pro-1', 'myriad-pro-2', 'Trebuchet MS', Helvetica, Arial, sans-serif;
		font-size: 52px;
		font-style: normal;
		font-weight: bold;
		line-height: 60px;
		margin: 25px 0 0 10px;
		padding: 0;
	}
	#headimg h1 a {
		color: #fff;
		font-variant: small-caps;
		letter-spacing: -0.04em;
		text-decoration: none;
		text-shadow: #000 1px 1px 1px;
	}
	#desc {
		color: #fff;
		float: left;
		font-size: 18px;
		font-weight: normal;
		letter-spacing: 1px;
		margin: 1.7em 0 0 1em;
		padding: .8em 0.2em;
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
* Load the custom tabbed widget
*/
require_once( dirname( __FILE__ ) . '/inc/widgets.php' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function mystique_widgets_init() {
	register_sidebar( array (
		'name' => __( 'Default sidebar', 'mystique' ),
		'id' => 'primary-widget-area',
		'description' => __( 'The primary widget area.', 'mystique' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>'
	) );

	register_sidebar( array (
		'name' => __( 'Secondary sidebar', 'mystique' ),
		'id' => 'secondary-widget-area',
		'description' => __( 'The secondary widget area.', 'mystique' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>'
	) );

	register_sidebar( array (
		'name' => __( 'First Footer Widget Area', 'mystique' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area.', 'mystique' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	) );

	register_sidebar( array (
		'name' => __( 'Second Footer Widget Area', 'mystique' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer widget area.', 'mystique' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	) );

	register_sidebar( array (
		'name' => __( 'Third Footer Widget Area', 'mystique' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third footer widget area.', 'mystique' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	) );

	register_sidebar( array (
		'name' => __( 'Fourth Footer Widget Area', 'mystique' ),
		'id' => 'fourth-footer-widget-area',
		'description' => __( 'The fourth footer widget area.', 'mystique' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	) );
}
add_action( 'init', 'mystique_widgets_init' );

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 */
function mystique_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'mystique_remove_recent_comments_style' );

if ( ! function_exists( 'mystique_post_meta' ) ) :
/**
 * Prints HTML with meta information for the current post (date, category, tags and permalink).
 */
function mystique_post_meta() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'Posted on %1$s, in %2$s and tagged %3$s. Bookmark the <a href="%4$s" title="Permalink to %5$s" rel="bookmark">permalink</a>.', 'mystique' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'Posted on %1$s, in %2$s. Bookmark the <a href="%4$s" title="Permalink to %5$s" rel="bookmark">permalink</a>.', 'mystique' );
	} else {
		$posted_in = __( 'Posted on %1$s. Bookmark the <a href="%4$s" title="Permalink to %5$s" rel="bookmark">permalink</a>.', 'mystique' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_date(),
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;

if ( ! function_exists( 'mystique_comment' ) ) :
/**
 * Template for comments.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own mystique_comment(), and that function will be used instead.
 *
 */
function mystique_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>" class="tiptrigger">
		<div class="comment-head comment-author vcard">
			<?php echo get_avatar( $comment, 48 ); ?>

			<cite class="fn"><?php comment_author_link(); ?></cite>

			<span class="comment-meta commentmetadata">
				|
				<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
				<?php
					/* translators: 1: date, 2: time */
					printf( __( '%1$s at %2$s', 'mystique' ),
						get_comment_date(),
						get_comment_time()
					); ?></a>
					<?php //edit_comment_link( __( 'Edit', 'mystique' ), ' | ' );
				?>
			</span><!-- .comment-meta .commentmetadata -->
		</div><!-- .comment-head .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em><?php _e( 'Your comment is awaiting moderation.', 'mystique' ); ?></em>
			<br />
		<?php endif; ?>

		<div class="comment-body">
			<?php comment_text(); ?>
		</div><!-- .comment-body -->

		 <div class="act tip">
			<?php if ( comments_open() ): ?>
	 		<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => __( 'Reply', 'mystique' ) ) ) ); ?></span>
			<?php endif; ?>
			<?php edit_comment_link( __( 'Edit', 'mystique' ), '', '' ); ?>
		 </div><!-- .act .tip -->

	</div><!-- #comment-## -->
<?php }
endif;

if ( ! function_exists( 'mystique_pings' ) ) :
/**
 * Template for Trackbacks and Pingbacks
 */
function mystique_pings( $comment, $args, $depth ) {
 $GLOBALS[ 'comment' ] = $comment;
 ?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'mystique' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'mystique' ), ' ' ); ?></p>
	</li>
<?php }
endif;

/**
 * Returns the current Mystique color scheme as selected in the theme options
 *
 */
function mystique_current_color_scheme() {
	$options = mystique_get_theme_options();
	$color_scheme = $options['color_scheme'];

	return $color_scheme;
}

/**
 * Register our color schemes and add them to the queue
 */
function mystique_color_registrar() {
	$color = mystique_current_color_scheme();

	wp_register_style( $color, get_template_directory_uri() . '/colors/color-' . $color . '.css', null, null );
	wp_register_style( $color . '_rtl' , get_template_directory_uri() . '/colors/color-' . $color . '-rtl.css', null, null );

	wp_enqueue_style( $color );

	if ( 'rtl' == get_option( 'text_direction' ) ) {
		wp_enqueue_style( $color . '_rtl' );
	}
}
add_action( 'wp_enqueue_scripts', 'mystique_color_registrar' );



/**
 * Returns the current Mystique layout as selected in the theme options
 */
function mystique_layout_type() {
	$options = mystique_get_theme_options();
	$layout = $options['theme_layout'];

	// override if layout page templates are used
	if ( is_page_template( 'page-nosidebar.php' ) ) $layout = 'no-sidebar';
	if ( is_page_template( 'page-sidebar-content.php' ) ) $layout = 'sidebar-content';
	if ( is_page_template( 'page-content-sidebar.php' ) ) $layout = 'content-sidebar';
	if ( is_page_template( 'page-sidebar-content-sidebar.php' ) ) $layout = 'sidebar-content-sidebar';
	if ( is_page_template( 'page-sidebar-sidebar-content.php' ) ) $layout = 'sidebar-sidebar-content';
	if ( is_page_template( 'page-content-sidebar-sidebar.php' ) ) $layout = 'content-sidebar-sidebar';

	return $layout;
}

/**
 * Adds mystique_layout_type() to the array of body classes
 */
function mystique_body_class($classes) {
	$classes[] = mystique_layout_type();

	return $classes;
}
add_filter( 'body_class', 'mystique_body_class' );

function mystique_header_css() {
	// Hide the theme-provided background images if the user adds a custom background image or color
	if ( '' != get_background_image() || '' != get_background_color() || '' != get_header_image() ) : ?>
	<style type="text/css">
	<?php if ( '' != get_background_image() || '' != get_background_color() ) : ?>
		#page {
			background: none;
		}
	<?php endif; ?>
	<?php if ( get_header_image() ) : ?>
		#branding {
			margin-top: 1em;
			padding: 52px 0 0;
		}
		#branding #logo {
			border-width: 0;
			margin: 0 0 0 .3em;
		}
	<?php endif; ?>
	</style>
	<?php endif;
}
add_action( 'wp_head', 'mystique_header_css' );

/**
 * Functions to store and retrieve the post ID of the current featured post
 */
function mystique_set_featured_post( $post_id ) {
	global $mystique_featured_post;
	if ( !empty( $post_id ) )
		$mystique_featured_post = (int) $post_id;
	return $mystique_featured_post;
}
function mystique_get_featured_post() {
	global $mystique_featured_post;
	return $mystique_featured_post;
}

/**
 * Set the theme colors for WordPress.com
 */
$theme_color = mystique_current_color_scheme();

switch ( $theme_color ) {
	case 'green':
		$themecolors = array(
			'bg' => 'ffffff',
			'border' => 'bcb7b4',
			'text' => '4e4e4e',
			'link' => '0071bb',
			'url' => 'b9de20',
		);
		break;

	case 'red':
		$themecolors = array(
			'bg' => 'ffffff',
			'border' => 'ed1e24',
			'text' => '4e4e4e',
			'link' => '0071bb',
			'url' => 'd91d27',
		);
		break;

	case 'blue':
		$themecolors = array(
			'bg' => 'ffffff',
			'border' => '5ba1e0',
			'text' => '4e4e4e',
			'link' => '0071bb',
			'url' => '44a1fb',
		);
		break;

	case 'grey':
		$themecolors = array(
			'bg' => 'ffffff',
			'border' => 'eeeeee',
			'text' => '4e4e4e',
			'link' => '888888',
			'url' => '7b7b7b',
		);
		break;

	case 'pink':
		$themecolors = array(
			'bg' => 'ffffff',
			'border' => 'ff7888',
			'text' => '4e4e4e',
			'link' => 'db2f5e',
			'url' => 'eb5266',
		);
		break;

	case 'purple':
		$themecolors = array(
			'bg' => 'ffffff',
			'border' => 'd480de',
			'text' => '4e4e4e',
			'link' => 'b02cc4',
			'url' => 'b02cc4',
		);
		break;
}