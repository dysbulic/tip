<?php
/**
 * @package Imbalance 2
 */
?>
<?php
// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 710;

// Tell WordPress to run imbalance2_setup() when the 'after_setup_theme' hook is run. */
if ( ! function_exists( 'imbalance2_setup' ) ):

function imbalance2_setup() {

	load_theme_textdomain( 'imbalance2', get_template_directory() . '/languages' );

	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// Load up our theme options page and related code.
	require( dirname( __FILE__ ) . '/inc/theme-options.php' );

	// Add default posts and comments RSS feed links to <head>.
	add_theme_support( 'automatic-feed-links' );

	// This theme uses Featured Images
	add_theme_support( 'post-thumbnails' );

	// Add support for custom backgrounds
	add_custom_background();

	// The default header text color
	define( 'HEADER_TEXTCOLOR', '333' );

	// By leaving empty, we allow for random image rotation.
	define( 'HEADER_IMAGE', '' );

	// The height and width of your custom header.
	// Add a filter to twentyeleven_header_image_width and twentyeleven_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'imbalance2_header_image_width', 210 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'imbalance2_header_image_height', 170 ) );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See twentyeleven_admin_header_style(), below.
	add_custom_image_header( 'imbalance2_header_style', 'imbalance2_admin_header_style' );

	// Set thumbnail size.
	set_post_thumbnail_size( 210 );

	// Add a custom featured image size.
	add_image_size( 'homepage-thumb', 210 );

	// This theme uses Custom menu in two locations
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'imbalance2' ),
		'secondary' => __( 'Secondary Navigation', 'imbalance2' )
	) );
}
endif;
add_action( 'after_setup_theme', 'imbalance2_setup' );

// Styles the header image and text displayed on the blog
function imbalance2_header_style() {

	if ( HEADER_TEXTCOLOR == get_header_textcolor() && '' == get_header_image() )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php if ( '' != get_header_image() ) : ?>
		#branding {
			min-height: 150px;
			padding: 10px;
			width: 190px;
		}
	<?php endif; ?>
	<?php if ( 'blank' == get_header_textcolor() ) : ?>
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
			color: #<?php echo get_header_textcolor(); ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
}

// Styles the header image displayed on the Appearance > Header admin panel.
function imbalance2_admin_header_style() {
	$options = imbalance2_get_theme_options();
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
		height: 170px;
		text-align: left;
		width: 210px;
	}
	#headimg h1,
	#desc {
		font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
	}
	#headimg h1 {
		font-size: 23px;
		font-weight: bold;
		line-height: 27px;
	<?php if ( '' != get_header_image() ) : ?>
		margin: 0 10px 7px;
		padding: 10px 0 0 0;
	<?php else : ?>
		margin: 0 0 7px 0;
	<?php endif; ?>
	}
	#headimg h1 a {
		text-decoration: none;
	}
	#desc {
		color: #9d9d9d;
		font-weight: normal;
		font-size: 12px;
		line-height: 1.2em;
	<?php if ( '' != get_header_image() ) : ?>
		margin: 0 10px;
	<?php else : ?>
		margin: 0;
	<?php endif; ?>
	}
	</style>
<?php
}

// Background style for front-end.
function imbalance2_custom_background() {
	if ( '' != get_background_color() && '' == get_background_image() ) : ?>
	<style type="text/css">
		body {
			background: #<?php echo get_background_color(); ?>;
		}
	</style>
	<?php endif;
}
add_action( 'wp_head', 'imbalance2_custom_background' );

// Enqueue scripts
function imbalance2_scripts() {
	wp_enqueue_script( 'jquery-masonry', get_template_directory_uri() . '/libs/jquery.masonry.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'jquery-imagesloaded', get_template_directory_uri() . '/libs/jquery.imagesloaded.js', array( 'jquery' ) );
	wp_enqueue_script( 'jquery-ui', get_template_directory_uri() . '/libs/jquery-ui.custom.min.js', array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'imbalance2_scripts' );

// Register widgetized area and update sidebar with default widgets
function imbalance2_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Header Area', 'imbalance2' ),
		'id' => 'sidebar-1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area One', 'imbalance2' ),
		'id' => 'sidebar-2',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Two', 'imbalance2' ),
		'id' => 'sidebar-3',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'imbalance2_widgets_init' );

// Sets the post excerpt length to 40 characters.
function imbalance2_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'imbalance2_excerpt_length' );

// Replaces "[...]" (appended to automatically generated excerpts).
function imbalance2_auto_excerpt_more( $more ) {
	return '';
}
add_filter( 'excerpt_more', 'imbalance2_auto_excerpt_more' );

// Template for comments and pingbacks.
function imbalance2_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
			<div class="comment-avatar">
				<?php echo get_avatar( $comment, 60 ); ?>
			</div>
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'imbalance2' ); ?></em>
			<br />
		<?php endif; ?>

			<div class="comment-author">
				<?php printf( __( '%s', 'imbalance2' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
			</div>

			<div class="comment-meta commentmetadata">
				<?php
					/* translators: 1: date, 2: time */
					printf( __( '%1$s at %2$s', 'imbalance2' ), get_comment_date(),  get_comment_time() ); ?><?php edit_comment_link( __( '(Edit)', 'imbalance2' ), ' ' );
				?>
			</div><!-- .comment-meta .commentmetadata -->

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->

			<div class="comment-body"><?php comment_text(); ?></div>
		</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'imbalance2' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'imbalance2' ), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}

// Returns true if a blog has more than 1 category
function imbalance2_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'all_the_cool_cats' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}

	if ( '1' != $all_the_cool_cats ) {
		// This blog has more than 1 category so toolbox_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so toolbox_categorized_blog should return false
		return false;
	}
}

// Prints HTML with meta information for the current author
function imbalance2_posted_by() {
	printf( __( '<span class="byline"><span class="meta-sep">By</span> <a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>', 'imbalance2' ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'imbalance2' ), get_the_author() ) ),
		esc_html( get_the_author() )
	);
}

// Prints HTML with meta information for the post date
function imbalance2_posted_on() {
	printf( __( '<span class="online"><span class="main-separator"> / </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a></span>', 'imbalance2' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);
}

// Prints HTML with meta information for the current catgory
function imbalance2_posted_in() {
	if ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( '<span class="main-separator"> / </span>%1$s', 'imbalance2' );
	} else {
		$posted_in = __( '<span class="main-separator"> / </span>Bookmark the <a href="%2$s" title="Permalink to %3$s" rel="bookmark">permalink</a>.', 'imbalance2' );
	}
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}

// Prints HTML with meta information for the current tag
function imbalance2_tags() {
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) printf(__( '<div class="entry-tags"><span>Tags:</span> %1$s</div>', 'imbalance2' ), $tag_list );
}

// Set a default theme color array for WP.com.
$themecolors = array(
	'bg' => 'ffffff',
	'border' => 'dedfe0',
	'text' => '333333',
	'link' => 'f05133',
	'url' => 'f05133',
);