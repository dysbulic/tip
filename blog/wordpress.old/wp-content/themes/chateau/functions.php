<?php
/**
 * @package WordPress
 * @subpackage Chateau
 */

/**
 * WP.com: Check the current color scheme and set the correct themecolors array
 */
if ( ! isset( $themecolors ) ) {
	$options = get_option( 'chateau_theme_options' );
	$color_scheme = $options['color_scheme'];

	if ( 'light' == $color_scheme ) {
		$themecolors = array(
			'bg' => 'ffffff',
			'border' => 'dddddd',
			'text' => '333333',
			'link' => '990000',
			'url' => '990000',
		);
	}
	if ( 'dark' == $color_scheme ) {
		$themecolors = array(
			'bg' => '191819',
			'border' => '191819',
			'text' => 'a19090',
			'link' => 'ffffff',
			'url' => 'ffffff',
		);
	}
}

// Set the content width based on the chosen layout.
if ( ! isset( $content_width ) )
	$content_width = chateau_attachment_width();

/**
 * Returns the value of content_width depending on the current layout.
 *
 */
function chateau_attachment_width() {
	$options = get_option( 'chateau_theme_options' );
	$current_layout = $options['theme_layout'];

	if ( 'content' == $current_layout )
		return 791;
	else
		return 529;
}

// Tell WordPress to run chateau_setup() when the 'after_setup_theme' hook is run.
add_action( 'after_setup_theme', 'chateau_setup' );

if ( ! function_exists( 'chateau_setup' ) ):

function chateau_setup() {

	/* Make Chateau available for translation.
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Chateau, use a find and replace
	 * to change 'chateau' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'chateau', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Load theme options
	require_once( dirname( __FILE__ ) . '/inc/theme-options.php' );

	// Add default posts and comments RSS feed links to <head>.
	add_theme_support( 'automatic-feed-links' );

	// Add support for Post Formats
	add_theme_support( 'post-formats', array( 'aside', 'gallery', 'image' ) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'chateau' ) );

	// Add support for custom backgrounds
	add_custom_background();

	// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
	add_theme_support( 'post-thumbnails' );

	// The default header text color
	define( 'HEADER_TEXTCOLOR', '000' );

	// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
	define( 'HEADER_IMAGE', '%s/images/chateau-default.jpg' );

	// The height and width of your custom header.
	// Add a filter to chateau_header_image_width and chateau_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'chateau_header_image_width', 960 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'chateau_header_image_height', 260 ) );

	// We'll be using post thumbnails for custom header images on posts and pages.
	// We want them to be the size of the header image that we just defined
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See chateau_admin_header_style(), below.
	add_custom_image_header( 'chateau_header_style', 'chateau_admin_header_style', 'chateau_admin_header_image' );
}
endif; // end chateau_setup

// Allow custom colors to match the background color of some elements
function chateau_custom_background_color() {
	if ( '' != get_background_color() ) { 
	$custombackgroundcolor = get_background_color();
?>

		<style type="text/css">
		#menu li,
		.post-date em,
		.sticky .entry-format, 
		.more-posts .sticky h2.entry-format, 
		.more-posts h2.entry-format,
		#comments h3 span {
			background-color: #<?php echo $custombackgroundcolor; ?>;
		}
		
		#menu a {
			border-color: #<?php echo $custombackgroundcolor; ?>
		}
		</style>
<?php 
	}	
	if ( '' != get_background_image() ) {
?>
		<style type="text/css">
		#menu li,
		.sticky .entry-format, 
		.more-posts .sticky h2.entry-format, 
		.more-posts h2.entry-format,
		#comments h3 span {
			background-color: transparent;
		}
		
		#menu a {
			border-color: transparent;
		}
		</style>
<?php	
	}
}
add_action( 'wp_head', 'chateau_custom_background_color' );

if ( ! function_exists( 'chateau_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 */
function chateau_header_style() {

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	if ( HEADER_TEXTCOLOR == get_header_textcolor() )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
		<style type="text/css">
		<?php
			// Has the text been hidden?
			if ( 'blank' == get_header_textcolor() ) :
		?>
			#site-title,
			#site-description {
				position: absolute !important;
				clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
				clip: rect(1px, 1px, 1px, 1px);
			}
			#main-image {
				border-top: none;
				padding-top: 0;
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
endif; // chateau_header_style

if ( ! function_exists( 'chateau_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in chateau_setup().
 *
 */
function chateau_admin_header_style() {
?>
<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#headimg h1, #headimg h2 {
		font-family: "Adobe Garamond Pro", Garamond, Palatino, "Palatino Linotype", Times, "Times New Roman", Georgia, serif;
		font-style: italic;
		line-height: 39px;
	}
	#headimg h1 {
		display: inline;
		font-weight: bold;
		font-size: 39px;		
	}
	#headimg h1 a {
		text-decoration: none;
	}
	#desc {
		display: inline;
		font-size: 28px;
	}
	<?php
		// If the user has set a custom color for the text use that
		if ( get_header_textcolor() != HEADER_TEXTCOLOR ) :
	?>
		#site-title a,
		#site-description {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	#headimg img {
		border-top: 1px dotted #DDD;
		clear: both;
		height: auto;
		margin: 15px 0 0 0;
		max-width: 960px;
		padding: 20px 0 0 0;
		width: 100%;
	}
</style>
<?php
}
endif; // chateau_admin_header_style

if ( ! function_exists( 'chateau_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in chateau_setup().
 *
 */
function chateau_admin_header_image() { ?>
	<div id="headimg">
		<?php
		if ( 'blank' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) || '' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) )
			$style = ' style="display:none;"';
		else
			$style = ' style="color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';"';
		?>
		<h1 id="site-title"><a id="name" <?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<?php $blog_description = get_bloginfo( 'description' ); if ( ! empty( $blog_description ) ) : ?>
			<h2 id="desc"<?php echo $style; ?>>~ <?php echo $blog_description; ?></h2>
		<?php endif; ?>
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }
endif; // chateau_admin_header_image

// Register widgetized areas
function chateau_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar Widget Area', 'chateau' ),
		'id' => 'sidebar',
		'description' => __( 'The Sidebar', 'chateau' ),
		'before_widget' => '<aside id="%1$s" class="sidebar-widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1>&clubs; ',
		'after_title' => '</h1>',
	));

	register_sidebar( array(
		'name' => __( 'Upper Footer Widget Area', 'chateau' ),
		'id' => 'upper-footer-widget-area',
		'description' => __( 'A single full-width footer column', 'chateau' ),
		'before_widget' => '<aside id="%1$s" class="footer-widget %2$s clear-fix">',
		'after_widget' => '</aside>',
		'before_title' => '<h1>',
		'after_title' => '</h1>',
	));

	register_sidebar( array(
		'name' => __( 'First Footer Widget Area', 'chateau' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first column below the Upper Footer Widget Area', 'chateau' ),
		'before_widget' => '<aside id="%1$s" class="footer-widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1>',
		'after_title' => '</h1>',
	));

	register_sidebar( array(
		'name' => __( 'Second Footer Widget Area', 'chateau' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second column below the Upper Footer Widget Area', 'chateau' ),
		'before_widget' => '<aside id="%1$s" class="footer-widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1>',
		'after_title' => '</h1>',
	));

	register_sidebar( array(
		'name' => __( 'Third Footer Widget Area', 'chateau' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third column below the Upper Footer Widget Area', 'chateau' ),
		'before_widget' => '<aside id="%1$s" class="footer-widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1>',
		'after_title' => '</h1>',
	));

	register_sidebar( array(
		'name' => __( 'Fourth Footer Widget Area', 'chateau' ),
		'id' => 'fourth-footer-widget-area',
		'description' => __( 'The fourth column below the Upper Footer Widget Area', 'chateau' ),
		'before_widget' => '<aside id="%1$s" class="footer-widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1>',
		'after_title' => '</h1>',
	));
}

add_action( 'init', 'chateau_widgets_init' );

//Changed wp_page_menu structure to get rid of the wrapped div and add menu_class arguments to <ul>
function chateau_add_menu_class ( $page_markup ) {
	preg_match( '/^<div class=\"([a-z0-9-_ ]+)\">/i', $page_markup, $matches );
	$divclass = $matches[1];
	$toreplace = array( '<div class="'.$divclass.'">', '</div>' );
	$new_markup = str_replace( $toreplace, '', $page_markup );
	$new_markup = preg_replace( '/^<ul>/i', '<ul class="'.$divclass.'">', $new_markup );
	return $new_markup;
}
add_filter( 'wp_page_menu', 'chateau_add_menu_class' );

// Display navigation to next/previous pages when applicable
function chateau_content_nav( $nav_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $nav_id; ?>" class="clear-fix">

			<div class="nav-previous"><?php next_posts_link( __( '&larr; Older posts', 'chateau' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts &rarr;', 'chateau' ) ); ?></div>
		</nav><!-- #nav-below -->
	<?php endif;
}

/**
 * Sets the post excerpt length to 20 words.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function chateau_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'chateau_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 */
function chateau_continue_reading_link() {
	return '<p><a href="'. esc_url( get_permalink() ) . '">' . __( 'Continue reading &raquo;', 'chateau' ) . '</a></p>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and chateau_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
function chateau_auto_excerpt_more( $more ) {
	return ' &hellip;' . chateau_continue_reading_link();
}
add_filter( 'excerpt_more', 'chateau_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
function chateau_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= chateau_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'chateau_custom_excerpt_more' );

if ( ! function_exists( 'chateau_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own chateau_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
	function chateau_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
			?>
			<li class="comment pingback">
				<div class="comment-text">
					<p><?php _e( 'Pingback:', 'chateau' ); ?> <?php comment_author_link(); ?></p>
					<p class="edit-comment"><?php edit_comment_link( __( '[Edit]', 'chateau' ), ' ' ); ?></p>
				</div>
			<?php
					break;
				default :
			?>

			<li <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">

				<div class="comment-heading clear-fix">
					<p class="comment-author">
						<?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
						<?php printf( __( '<span>%s</span> <em>said:</em>', 'chateau' ), get_comment_author_link() ); ?>
					</p>
					<p class="comment-date">
						<?php
							printf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								sprintf(__( '%1$s at %2$s', 'chateau' ), get_comment_date(),  get_comment_time() )
							);
						?>
					</p>
				</div>
				<div class="comment-text">
					<?php comment_text(); ?>
					<?php if ( $comment->comment_approved == '0' ) : ?>
					<p class="waiting4mod"><?php _e( 'Your comment is awaiting moderation.', 'chateau' ); ?></p>
					<?php endif; ?>
					<p class="reply-link"><?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'chateau' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></p>
					<p class="edit-comment"><?php edit_comment_link( __( '[Edit Comment]', 'chateau' ),'','' ); ?></p>
				</div>
		<?php
				break;
		endswitch;
	}
endif; // ends check for chateau_comment()

// Show post-date and post-info for use in loop
function chateau_post_info() {
?>
	<p class="post-date">
		<strong><?php the_time( 'd' ); ?></strong>
		<em><?php the_time( 'l' ); ?></em>
		<span><?php the_time( 'M Y' ); ?></span>
	</p>
	<div class="post-info clear-fix">
		<p>
			<?php 
				printf( __ ( 'Posted <span class="by-author"> by <span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span></span> in %4$s'),
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					esc_attr( sprintf( __( 'View all posts by %s', 'chateau' ), get_the_author() ) ),
					esc_html( get_the_author() ),
					get_the_category_list( ', ' )
				); 
			?>
		</p>
		<p class="post-com-count">
			<strong>&asymp; <?php comments_popup_link( __( 'Leave a Comment', 'chateau' ), __( '1 Comment', 'chateau' ), __( '% Comments', 'chateau' ) ); ?></strong>
		</p>
	</div><!-- end .post-info -->
<?php
}

// Show post-extras for use in loop
function chateau_post_extra() {
?>
	<div class="post-extras">
		<?php edit_post_link( __( 'Edit', 'chateau' ), '<p>[', ']</p>'); ?>
		<?php the_tags( '<p><strong>' . __( 'Tags','chateau' ) . '</strong></p><p>', ', ', '</p>' ); ?>
	</div><!-- end .post-extras -->
<?php
}
