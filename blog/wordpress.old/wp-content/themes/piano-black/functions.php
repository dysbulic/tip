<?php
/**
 * @package WordPress
 * @subpackage Piano Black
 */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 549;

$themecolors = array(
	'bg' => '000000',
	'border' => '333333',
	'text' => 'b8babb',
	'link' => '7f8e91',
	'url' => '7f8e91'
);

function piano_black_setup() {
	// Enable custom background
	add_custom_background();

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Enable the primary navigation menu
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'piano-black' ),
	) );

	// Enable custom header image
	define( 'HEADER_TEXTCOLOR', 'ffffff' );
	define( 'HEADER_IMAGE', '' );
	define( 'HEADER_IMAGE_WIDTH', 951 );
	define( 'HEADER_IMAGE_HEIGHT', 160 );
	add_custom_image_header( 'piano_black_header_style', 'piano_black_admin_header_style', '' );

	// Enable use of featured images as header images
	set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

	// Load theme options
	require_once( dirname( __FILE__ ) . '/inc/theme-options.php' );
}
add_action( 'after_setup_theme', 'piano_black_setup' );

// Header style for front-end
function piano_black_header_style() {
	if ( HEADER_TEXTCOLOR == get_header_textcolor() && '' == get_header_image() )
		return;
	?>
	<style type="text/css">
	<?php if ( '' != get_header_image() ) : ?>
		#branding {
			background: url(<?php echo get_header_image(); ?>) no-repeat;
		}
	<?php endif; ?>
	<?php if ( 'blank' == get_header_textcolor() ) : ?>
		#site-title,
		#site-description {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php else : ?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
}

// Header style for Appearance > Header
function piano_black_admin_header_style() {
	$background_color = get_background_color();
	if ( empty( $background_color ) )
		$background_color = '000';
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		background-color: #<?php echo $background_color; ?>;
		background-repeat: no-repeat;
		border: none;
		height: 160px;
	}
	#headimg h1 {
		padding: 60px 0 0 40px;
	}
	#headimg h1 a {
		font-family: sans-serif;
		font-size: 22px;
		text-decoration: none;
	}
	#desc {
		font-family: sans-serif;
		padding-left: 40px;
	}
	</style>
<?php
}

// Background style for front-end
function piano_black_custom_background() {
	if ( '' != get_background_color() && '' == get_background_image() ) : ?>
	<style type="text/css">
		body {
			background: #<?php echo get_background_color(); ?>;
		}
	</style>
	<?php endif;
}
add_action( 'wp_head', 'piano_black_custom_background' );

// Use wp_list_pages as menu fallback
function piano_black_menu_fallback() { ?>
	<ul class="menu">
		<li<?php if ( is_front_page() ) : echo ' class="current_page_item"'; endif; ?>><a href="<?php echo home_url( '/' ); ?>" title="<?php esc_attr_e( 'Home', 'piano-black' ); ?>"><?php _e( 'Home', 'piano-black' ); ?></a></li>
		<?php wp_list_pages( array( 'sort_column' => 'menu_order', 'depth' => 2, 'title_li' => '' ) ); ?>
	</ul>
<?php }

// Template for comments and pingbacks.
function piano_black_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer>
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, 48 ); ?>
					<?php printf( __( '%s <span class="says">says:</span>', 'piano-black' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
				</div><!-- .comment-author .vcard -->
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'piano-black' ); ?></em>
					<br />
				<?php endif; ?>

				<div class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s at %2$s', 'piano-black' ), get_comment_date(), get_comment_time() ); ?>
					</time></a>
					<?php edit_comment_link( __( 'Edit', 'piano-black' ), ' ' );
					?>
				</div><!-- .comment-meta .commentmetadata -->
			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'piano-black' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'piano-black' ), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}

// Register widgetized areas
function piano_black_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar 1', 'piano-black' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'init', 'piano_black_widgets_init' );

// Get theme options with defaults as fallback
function piano_black_get_theme_options() {
	$defaults = array(
		'theme_rss' => 'yes',
		'theme_search' => 'yes',
	);
	$options = get_option( 'piano_black_theme_options', $defaults );
	return $options;
}

/**
 * Adds theme-specific classes to the array of body classes.
 * For use with CSS layout.
 */
function piano_black_body_classes( $classes ) {

	if ( ! is_multi_author() ) {
		$classes[] = 'single-author';
	}

	if ( is_page_template( 'full-width-page.php' ) || is_attachment() )
		$classes[] = 'full-width-layout';

	return $classes;
}
add_filter( 'body_class', 'piano_black_body_classes' );


/**
 * This theme was built with PHP, Semantic HTML, CSS, love, and a Toolbox.
 */