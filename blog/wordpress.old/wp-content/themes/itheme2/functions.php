<?php
/**
 * iTheme2 functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage iTheme2
 * @since iTheme2 1.1-wpcom
 */

/**
 * Test to see if any posts meet our conditions for featuring posts
 */
function itheme2_featuring_posts() {
	if ( false === ( $featured_posts_with_thumbnail = get_transient( 'featured_posts_with_thumbnail' ) ) ) {

			$featured_posts_with_thumbnail = false;


			// See if we have any sticky posts with featured images and use them to create our featured posts area
			$sticky = get_option( 'sticky_posts' );

			// Proceed only if sticky posts exist.
			if ( ! empty( $sticky ) ) :

				$featured_args = array(
					'post__in' => $sticky,
					'post_status' => 'publish',
					'no_found_rows' => true,
				);

				// The Featured Posts query.
				$featured = new WP_Query( $featured_args );

				// Proceed only if published posts exist
				if ( $featured->have_posts() ) :

					// loop through once to see if any posts meet our conditions for featuring posts
					// in this case, sticky posts ($featured) that have a thumbnail (has_post_thumbnail())
					while ( $featured->have_posts() ) : $featured->the_post();
						if ( has_post_thumbnail() ) {
							$featured_posts_with_thumbnail = true;
							set_transient( 'featured_posts_with_thumbnail', $featured_posts_with_thumbnail );
							break;
						}
					endwhile;

				endif; // have_posts()

			endif; // $sticky

		} // checking for the featured post transient

		return $featured_posts_with_thumbnail;
}

/**
 * Flush out the transients used in itheme2_featuring_posts()
 *
 * @since iTheme2 1.2
 */
function itheme2_featured_post_checker_flusher() {
	// Vvwooshh!
	delete_transient( 'featured_posts_with_thumbnail' );
}
add_action( 'save_post', 'itheme2_featured_post_checker_flusher' );

/**
 * Load the scripts for running our carousel
 */
function itheme2_script_enqueue() {
	if ( itheme2_featuring_posts() )
		wp_enqueue_script( 'jcarousel', get_template_directory_uri() . '/js/jcarousel.js', array( 'jquery' ), '', true );
}
add_action( 'init', 'itheme2_script_enqueue' );

function itheme2_footer_scripts() {
	if ( itheme2_featuring_posts() ) { ?>
		<script type='text/javascript'>
		/* <![CDATA[ */
		/**
		 * We use the initCallback callback
		 * to assign functionality to the controls
		 */
		function mycarousel_initCallback(carousel) {
		    jQuery('.jcarousel-control a').bind('click', function() {
		        carousel.scroll(jQuery.jcarousel.intval(jQuery(this).text()));
		        return false;
		    });

		    jQuery('.jcarousel-scroll select').bind('change', function() {
		        carousel.options.scroll = jQuery.jcarousel.intval(this.options[this.selectedIndex].value);
		        return false;
		    });

		    jQuery('#featured-posts-next').bind('click', function() {
		        carousel.next();
		        return false;
		    });

		    jQuery('#featured-posts-prev').bind('click', function() {
		        carousel.prev();
		        return false;
		    });
		};

		jQuery(document).ready(function(){
			jQuery('#featured .slides').jcarousel({
				wrap: 'circular',
				visible: 5,
				scroll: 1,
				animation: "200",
				initCallback: mycarousel_initCallback,
		        // This tells jCarousel NOT to autobuild prev/next buttons
		        buttonNextHTML: null,
		        buttonPrevHTML: null
			});
		});
		/* ]]> */
		</script>
	<?php }
}
add_action( 'wp_footer', 'itheme2_footer_scripts' );

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 593; /* pixels */

if ( ! function_exists( 'itheme2_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override itheme2_setup() in a child theme, add your own itheme2_setup to your child theme's
 * functions.php file.
 */
function itheme2_setup() {
	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on itheme2, use a find and replace
	 * to change 'itheme2' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'itheme2', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	/**
	 * Load up our theme options page and related code.
	 */
	require( dirname( __FILE__ ) . '/inc/theme-options.php' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'itheme2' ),
	) );

	/**
	 * Add support for the Aside and Gallery Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'gallery' ) );

	/**
	 * Add support for custom backgrounds
	 */
	add_custom_background();

	/**
	 * Add in support for post thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'large-feature', 593, 261, true ); // Used for large feature images above the post
	add_image_size( 'small-feature', 145, 120, true ); // Used for small feature images in the carousel on the home page

	/**
	 * The default header text color
	 */
	define( 'HEADER_TEXTCOLOR', 'fff' );

	/**
	 * By leaving empty, we allow for random image rotation.
	 */
	define( 'HEADER_IMAGE', '' );

	/**
	 * The height and width of your custom header.
	 */
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'itheme2_header_image_width', 978 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'itheme2_header_image_height', 288 ) );

	/**
	 * Turn on random header image rotation by default.
	 */
	add_theme_support( 'custom-header', array( 'random-default' => true ) );

	/**
	 * Add a way for the custom header to be styled in the admin panel that controls headers
	 */
	add_custom_image_header( 'itheme2_header_style', 'itheme2_admin_header_style', 'itheme2_admin_header_image' );

}
endif; // itheme2_setup

if ( ! function_exists( 'itheme2_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 */
function itheme2_header_style() {
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
endif; // itheme2_header_style

if ( ! function_exists( 'itheme2_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in itheme2_setup().
 *
 * @since iTheme2 1.0
 */
function itheme2_admin_header_style() {
?>
	<style type="text/css">
		.appearance_page_custom-header #headimg {
			background: #0d1424 url(<?php echo get_template_directory_uri(); ?>/images/body-bg.jpg) no-repeat center top;
			border: none;
			padding: 30px 20px 20px;
			max-width: 978px;
		}
	<?php if ( get_background_image() != '' ) : ?>
		.appearance_page_custom-header #headimg {
			background-image: url(<?php echo get_theme_mod( 'background_image' ); ?>);
			background-repeat: <?php echo get_theme_mod( 'background_repeat' ); ?>;
			background-position: <?php echo get_theme_mod( 'background_position_x' ); ?>;
		}
	<?php endif; ?>
	<?php if ( get_background_color() != '' ) : ?>
		.appearance_page_custom-header #headimg {
			background-color: #<?php echo get_background_color(); ?>;
		}
	<?php endif; ?>
	<?php if ( get_background_image() == '' && get_background_color() != '' ) : ?>
		.appearance_page_custom-header #headimg {
			background-image: none;
		}
	<?php endif; ?>
		#headimg h1,
		#desc {
			line-height: 1.5;
		}
		#headimg h1 {
			margin: 0;
			max-width: 978px;
		}
		#headimg h1 a {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 25px;
			text-decoration: none;
			text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
		}
		#desc {
			font-family: Georgia, "Times New Roman", Times, serif;
			font-size: 13px;
			font-style: italic;
			line-height: 23px;
			margin: 9px 0 30px;
			max-width: 978px;
			opacity: 0.85;
			padding: 0;
			text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
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
			-webkit-border-radius: 8px;
			-moz-border-radius: 8px;
			border-radius: 8px;
			-webkit-box-shadow: 0 1px 1px rgba(0,0,0,.15);
			-moz-box-shadow: 0 1px 1px rgba(0,0,0,.15);
			box-shadow: 0 1px 1px rgba(0,0,0,.15);
			max-width: 978px;
			height: auto;
			width: 100%;
		}
	</style>
<?php
}
endif; // itheme2_admin_header_style

/**
 * Tell WordPress to run itheme2_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'itheme2_setup' );

if ( ! function_exists( 'itheme2_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in itheme2_setup().
 *
 * @since iTheme2 1.0
 */
function itheme2_admin_header_image() { ?>
	<div id="headimg">
		<?php
		if ( 'blank' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) || '' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) )
			$style = ' style="display:none;"';
		else
			$style = ' style="color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';"';
		?>
		<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }
endif; // itheme2_admin_header_image

/**
 * Allow custom colors to clear the background image
 */
function itheme2_custom_background_color() {
	if ( get_background_image() == '' && get_background_color() != '' ) { ?>
		<style type="text/css">
		body {
			background-image: none;
		}
		</style>
	<?php }
}
add_action( 'wp_head', 'itheme2_custom_background_color' );

/**
 * Sets the post excerpt length to 40 words.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 */
function itheme2_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'itheme2_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 */
function itheme2_continue_reading_link() {
	return ' <a href="'. esc_url( get_permalink() ) . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'itheme2' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and itheme2_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 */
function itheme2_auto_excerpt_more( $more ) {
	return ' &hellip;' . itheme2_continue_reading_link();
}
add_filter( 'excerpt_more', 'itheme2_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
function itheme2_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= itheme2_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'itheme2_custom_excerpt_more' );

/**
 * Set a default theme color array for WP.com.
 */
$themecolors = array(
	'bg' => 'ffffff',
	'border' => 'eeeeee',
	'text' => '444444',
);

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function itheme2_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'itheme2_page_menu_args' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function itheme2_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar 1', 'itheme2' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );

	register_sidebar( array(
		'name' => __( 'Sidebar 2', 'itheme2' ),
		'id' => 'sidebar-2',
		'description' => __( 'An optional second sidebar area', 'itheme2' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'init', 'itheme2_widgets_init' );

if ( ! function_exists( 'itheme2_content_nav' ) ):
/**
 * Display navigation to next/previous pages when applicable
 *
 * @since iTheme2 1.2
 */
function itheme2_content_nav( $nav_id ) {
	global $wp_query;

	?>
	<nav id="<?php echo $nav_id; ?>">
		<h1 class="assistive-text section-heading"><?php _e( 'Post navigation', 'itheme2' ); ?></h1>

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'itheme2' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'itheme2' ) . '</span>' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'itheme2' ) ); ?></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'itheme2' ) ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php
}
endif; // itheme2_content_nav


if ( ! function_exists( 'itheme2_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own itheme2_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since iTheme2 0.4
 */
function itheme2_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><strong><?php _e( 'Pingback:', 'itheme2' ); ?></strong> <?php comment_author_link(); ?><?php edit_comment_link( __( '[Edit]', 'itheme2' ), ' ' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer>
				<div class="comment-author vcard">
					<?php echo get_avatar( $comment, 36 ); ?>
					<cite class="fn"><?php comment_author_link(); ?></cite>
				</div><!-- .comment-author .vcard -->
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'itheme2' ); ?></em>
					<br />
				<?php endif; ?>

				<div class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s &#64; %2$s', 'itheme2' ), '<span class="comment-date"><strong>' . get_comment_date() . '</strong></span>', '<span class="comment-time">' . get_comment_time() . '</span>' ); ?>
					</time></a>
					<?php edit_comment_link( __( '[Edit]', 'itheme2' ), ' ' );
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
	endswitch;
}
endif; // ends check for itheme2_comment()

if ( ! function_exists( 'itheme2_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own itheme2_posted_on to override in a child theme
 *
 * @since iTheme2 1.2
 */
function itheme2_posted_on() {
	printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="byline"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'itheme2' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'itheme2' ), get_the_author() ) ),
		esc_html( get_the_author() )
	);
}
endif;

/**
 * Adds custom classes to the array of body classes.
 *
 * @since iTheme2 1.2
 */
function itheme2_body_classes( $classes ) {
	// Adds a class of indexed to index and archive views
	if ( is_home() || is_archive() )
		$classes[] = 'indexed';

	// Adds a class of single-author to blogs with only 1 published author
	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	return $classes;
}
add_filter( 'body_class', 'itheme2_body_classes' );

/**
 * Returns true if a blog has more than 1 category
 *
 * @since iTheme2 1.2
 */
function itheme2_categorized_blog() {
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
		// This blog has more than 1 category so itheme2_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so itheme2_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in itheme2_categorized_blog
 *
 * @since iTheme2 1.2
 */
function itheme2_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'itheme2_category_transient_flusher' );
add_action( 'save_post', 'itheme2_category_transient_flusher' );

/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 */
function itheme2_enhanced_image_navigation( $url ) {
	global $post;

	if ( wp_attachment_is_image( $post->ID ) )
		$url = $url . '#main';

	return $url;
}
add_filter( 'attachment_link', 'itheme2_enhanced_image_navigation' );


/**
 * WP.com: A dynamic $themecolors array for all of our alternate color schemes
 */
$options = get_option( 'itheme2_theme_options' );
$color_scheme = $options['color_scheme'];

switch ( $color_scheme ) {
	case 'gray':
		$themecolors = array(
			'bg' => 'efefef',
			'border' => 'cccccc',
			'text' => '666666',
			'link' => '026acb',
			'url' => '026acb',
		);
		break;

	case 'black':
		$themecolors = array(
			'bg' => '222222',
			'border' => '555555',
			'text' => 'aaaaaa',
			'link' => '026acb',
			'url' => '026acb',
		);
		break;
	
	default:
		$themecolors = array(
			'bg' => 'ffffff',
			'border' => 'dddddd',
			'text' => '666666',
			'link' => '026acb',
			'url' => '026acb',
		);
		break;
}

/**
 * This theme was built with PHP, Semantic HTML, CSS, love, and a iTheme2.
 */