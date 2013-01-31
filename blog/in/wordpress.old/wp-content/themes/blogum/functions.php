<?php
/**
 * @package WordPress
 * @subpackage Blogum
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 545;

/**
 * WP.com: set themecolors array
 */
if ( ! isset( $themecolors ) ) {
	$themecolors = array(
		'bg' => 'ffffff',
		'border' => 'e9e9e9',
		'text' => '9d9d9d',
		'link' => '000000',
		'url' => '000000',
	);
}

// Tell WordPress to run blogum_setup() when the 'after_setup_theme' hook is run.
add_action( 'after_setup_theme', 'blogum_setup' );

if ( ! function_exists( 'blogum_setup' ) ):

function blogum_setup() {

	load_theme_textdomain( 'blogum', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );
		
	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();		

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	//Add support for the Aside, Gallery nad Image Post Formats
	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'image' ) );
	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'blogum' ) );

	// Add support for custom backgrounds
	add_custom_background();

	// The default header text color
	define( 'HEADER_TEXTCOLOR', '000' );
	define( 'NO_HEADER_TEXT', true );
	
	define( 'HEADER_IMAGE', '' );
	
	// The height and width of your custom header.
	// Add a filter to blogum_header_image_width and blogum_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'blogum_header_image_width', 945 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'blogum_header_image_height', 150 ) );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See blogum_admin_header_style(), below.
	add_custom_image_header( '', 'blogum_admin_header_style' );
}
endif; // end blogum_setup

/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 */
function blogum_admin_header_style() {
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
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 */
function blogum_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'blogum_page_menu_args' );

/**
 *Changed wp_page_menu structure to get rid of the wrapped div and add menu_class arguments to <ul>
 */
function blogum_add_menu_class ( $page_markup ) {
	preg_match( '/^<div class=\"([a-z0-9-_]+)\">/i', $page_markup, $matches );
	$divclass = $matches[1];
	$toreplace = array( '<div class="'.$divclass.'">', '</div>' );
	$new_markup = str_replace( $toreplace, '', $page_markup );
	$new_markup = preg_replace( '/^<ul>/i', '<ul class="'.$divclass.' clear">', $new_markup );
	return $new_markup;
}
add_filter( 'wp_page_menu', 'blogum_add_menu_class' );

if ( ! function_exists( 'blogum_comment' ) ) :
/**
 * Template for comments and pingbacks.
 * Used as a callback by wp_list_comments() for displaying the comments.
 */

	function blogum_comment( $comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
			?>
			<li class="pingback">
				<div class="comment-meta">&emsp;</div>
				<div class="comment-text">
					<p><?php _e( 'Pingback:', 'blogum' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'blogum' ), ' ' ); ?></p>
				</div>
				<div class="clear"></div>
			<?php
				break;
				default :
			?>
			<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
				<article id="comment-<?php comment_ID(); ?>" class="comment">
					<footer class="comment-meta">

						<div class="comment-author vcard">
							<?php
								/* translators: 1: comment author, 2: date and time */
								printf( __( '%1$s said: <span>%2$s</span>', 'blogum' ),
									sprintf( '%s', get_comment_author_link() ),
									sprintf( '<time pubdate datetime="%1$s">%2$s</time>',
										get_comment_time( 'c' ),
										/* translators: 1: date, 2: time */
										sprintf( __( '%1$s<em>%2$s</em>', 'blogum' ), get_comment_date(), get_comment_time() )
									)
								);
							?>
						</div><!-- .comment-author .vcard -->

						<span class="comment-edit"><?php edit_comment_link( __( 'Edit', 'blogum' ), ' ' ); ?></span>

						<?php if ( $comment->comment_approved == '0' ) : ?>
							<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'blogum' ); ?></em>
							<br />
						<?php endif; ?>

					</footer>

					<div class="comment-text clear">
						<div class="comment-avatar">
							<?php
								$avatar_size = 60;
								if ( '0' != $comment->comment_parent )
									$avatar_size = 30;
								echo get_avatar( $comment, $avatar_size );
							?>
						</div>
						<div class="comment-text-body">
							<?php comment_text(); ?>
							<div class="reply">
								<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply &darr;', 'blogum' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
							</div><!-- .reply -->
						</div>
					</div>
					<div class="clear"></div>
				</article><!-- #comment-## -->
			<?php
				break;
				endswitch;
	}
endif; // ends check for blogum_comment()

/**
 * Register widgetized area and update sidebar with default widgets
 */
function blogum_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar Widget Area', 'blogum' ),
		'id' => 'sidebar-1',
		'description' => __( 'The Sidebar Widget Area.', 'blogum' ),
		'before_widget' => '<aside id="%1$s" class="%2$s widget"><div class="widget-body">',
		'after_widget' => '</div></aside>',
		'before_title' => '<h1>',
		'after_title' => '</h1>',
	) );
}
add_action( 'init', 'blogum_widgets_init' );

// Display navigation to next/previous pages when applicable
function blogum_content_nav( $nav_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $nav_id; ?>" class="clear">
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'blogum' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'blogum' ) ); ?></div>
		</nav><!-- #nav-below -->
	<?php endif;
}

// Show post-data for use in loop
function blogum_post_data() { ?>
	<div class="post-data">
		<?php if( is_sticky() && is_multi_author() ):
			printf( __ ( '<div class="post-author">By <span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span></div><div class="post-date"><a class="post-date-link" href="%5$s" rel="bookmark">Featured</a></div><div class="post-categories">%4$s</div>', 'blogum' ),
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_attr( sprintf( __( 'View all posts by %s', 'blogum' ), get_the_author() ) ),
				esc_html( get_the_author() ),
				get_the_category_list( __( ', ', 'blogum' ) ),
				get_permalink()
			);
		elseif ( is_sticky() ) :
			printf( __ ( '<div class="post-date"><a class="post-date-link" href="%2$s" rel="bookmark">Featured</a></div><div class="post-categories">%1$s</div>', 'blogum' ),
				get_the_category_list( __( ', ', 'blogum' ) ),
				get_permalink()
			);
		elseif ( is_multi_author() ) :
			printf( __ ( '<div class="post-author">By <span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span></div><div class="post-date"><a class="post-date-link" href="%6$s" rel="bookmark">%4$s</a></div><div class="post-categories">%5$s</div>', 'blogum' ),
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				esc_attr( sprintf( __( 'View all posts by %s', 'blogum' ), get_the_author() ) ),
				esc_html( get_the_author() ),
				esc_html( get_the_date() ),
				get_the_category_list( __( ', ', 'blogum' ) ),
				get_permalink()
			);
		else:
			printf( __ ( '<div class="post-date"><a class="post-date-link" href="%3$s" rel="bookmark">%1$s</a></div><div class="post-categories">%2$s</div>', 'blogum' ),
				esc_html( get_the_date() ),
				get_the_category_list( __( ', ', 'blogum' ) ),
				get_permalink()
			);
		endif; ?>
	</div><!-- .post-data -->
<?php
}

// Returns a "Read More" link for excerpts
function blogum_continue_reading_link() {
	return '<p><a href="'. esc_url( get_permalink() ) . '" class="more-link">' . __( 'Read More', 'blogum' ) . '</a></p>';
}

// Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and blogum_continue_reading_link().
function blogum_auto_excerpt_more( $more ) {
	return ' &hellip;' . blogum_continue_reading_link();
}
add_filter( 'excerpt_more', 'blogum_auto_excerpt_more' );

// Adds a pretty "Continue Reading" link to custom post excerpts.
function blogum_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= blogum_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'blogum_custom_excerpt_more' );

function blogum_comments_popup_link() {
	if ( comments_open() || ( '0' != get_comments_number() && ! comments_open() ) ) : ?>
		<div class="comments-link">
			<?php comments_popup_link( __( 'Leave a Comment', 'blogum' ), __( '1 Comment', 'blogum' ), __( '% Comments', 'blogum' ) ); ?>
		</div>
	<?php endif;
}