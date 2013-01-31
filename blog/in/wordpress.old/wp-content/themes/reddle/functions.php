<?php
/**
 * Reddle functions and definitions
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
 * @package Reddle
 * @since Reddle 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 440; /* pixels */
	
	$header_image = get_header_image();	
	if ( empty( $header_image ) )
		$content_width = 800; /* pixels */
}

if ( ! function_exists( 'reddle_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override reddle_setup() in a child theme, add your own reddle_setup to your child theme's
 * functions.php file.
 */
function reddle_setup() {
	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Reddle, use a find and replace
	 * to change 'reddle' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'reddle', get_template_directory() . '/languages' );

	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'reddle' ),
	) );

	/**
	 * Add support for the Aside and Gallery Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'image' ) );

	/**
	 * Add in support for post thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * Add support for custom backgrounds
	 */
	add_custom_background();

	/**
	 * The default header text color
	 */
	define( 'HEADER_TEXTCOLOR', '777' );

	/**
	 * By leaving empty, we allow for random image rotation.
	 */
	define( 'HEADER_IMAGE', '' );

	/**
	 * The height and width of our custom header.
	 */
	define( 'HEADER_IMAGE_WIDTH', 1120 );
	define( 'HEADER_IMAGE_HEIGHT', 252 );

	/**
	 * Turn on random header image rotation by default.
	 */
	add_theme_support( 'custom-header', array( 'random-default' => true ) );

	/**
	 * Add a way for the custom header to be styled in the admin panel that controls custom headers.
	 */
	add_custom_image_header( 'reddle_header_style', 'reddle_admin_header_style', 'reddle_admin_header_image' );

}
endif; // reddle_setup

/**
 * Tell WordPress to run reddle_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'reddle_setup' );

if ( ! function_exists( 'reddle_header_style' ) ) :
/**
 * Custom styles for our blog header
 */
function reddle_header_style() {
	// If no custom options for text are set, let's bail
	$header_image = get_header_image();
	if ( empty( $header_image ) && '' == get_header_textcolor() )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	#masthead img {
		float: left;
	}
	<?php
		// Has the text been hidden? Let's hide it then.
		if ( 'blank' == get_header_textcolor() ) :
	?>
		#masthead > hgroup {
		    padding: 0;
		}
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
		#site-description {
			color: #<?php echo get_header_textcolor(); ?> !important;
		}
	<?php endif; ?>
	<?php
		// Is the main menu empty?
		if ( ! has_nav_menu( 'primary' ) ) :
	?>
		#header-image {
			margin-bottom: 3.23em;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // reddle_header_style()

if ( ! function_exists( 'reddle_admin_header_style' ) ) :
/**
 * Custom styles for the custom header page in the admin
 */
function reddle_admin_header_style() {
?>
	<style type="text/css">
	#headimg {
		background: #fff;
		text-align: center;
		max-width: 1120px;
	}
	#headimg .masthead {
		padding: 70px 0 41px;
	}
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#headimg h1 {
		border-bottom: 1px solid #ddd;
		display: inline-block;
		font-weight: normal;
		font-family: Georgia, "Bitstream Charter", serif;
		font-size: 21px;
		line-height: 1.15;
		margin: 0;
		padding: 0 2.954209748892% .275em;
	}
	#headimg h1 a {
		color: #b12930;
		text-decoration: none;
	}
	#headimg h1 a:hover,
	#headimg h1 a:focus,
	#headimg h1 a:active {
		color: #000;
		text-decoration: none;
	}
	#headimg #desc {
		color: #777;
		display: block;
		font-family: Verdana, sans-serif;
		font-size: 11px;
		letter-spacing: 0.05em;
		line-height: 1.52727272727274;
		padding: 0.382em 0;
		text-transform: uppercase;
	}
	#headimg img {
		float: left;
		height: auto;
		max-width: 100%;
	}
	<?php
		// If the user has set a custom color for the text use that
		if ( get_header_textcolor() != HEADER_TEXTCOLOR ) :
	?>
		#headimg #desc {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	<?php
		// Has the text been hidden?
		if ( 'blank' == get_header_textcolor() ) :
	?>
	#headimg .masthead {
		display: none;
	}
	<?php endif; ?>
	</style>
<?php
}
endif; // reddle_admin_header_style

if ( ! function_exists( 'reddle_admin_header_image' ) ) :
/**
 * Custom markup for the custom header admin page
 */
function reddle_admin_header_image() { ?>
	<div id="headimg">
		<?php
		if ( 'blank' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) || '' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) )
			$style = ' style="display:none;"';
		else
			$style = ' style="color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';"';
		?>
		<div class="masthead">
			<h1><a onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
			<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		</div>
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }
endif; // reddle_admin_header_image

/**
 * Sets the post excerpt length to 40 words.
 */
function reddle_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'reddle_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 */
function reddle_continue_reading_link() {
	return ' <a href="'. esc_url( get_permalink() ) . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'reddle' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and reddle_continue_reading_link().
 */
function reddle_auto_excerpt_more( $more ) {
	return ' &hellip;' . reddle_continue_reading_link();
}
add_filter( 'excerpt_more', 'reddle_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
function reddle_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= reddle_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'reddle_custom_excerpt_more' );

/**
 * Register widgetized area
 */
function reddle_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar 1', 'reddle' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );

	register_sidebar( array(
		'name' => __( 'Sidebar 2', 'reddle' ),
		'id' => 'sidebar-2',
		'description' => __( 'An optional second sidebar area', 'reddle' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );

	register_sidebar( array(
		'name' => __( 'First Footer Area', 'reddle' ),
		'id' => 'sidebar-3',
		'description' => __( 'An optional widget area for your site footer', 'reddle' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );

	register_sidebar( array(
		'name' => __( 'Second Footer Area', 'reddle' ),
		'id' => 'sidebar-4',
		'description' => __( 'An optional widget area for your site footer', 'reddle' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );

	register_sidebar( array(
		'name' => __( 'Third Footer Area', 'reddle' ),
		'id' => 'sidebar-5',
		'description' => __( 'An optional widget area for your site footer', 'reddle' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'init', 'reddle_widgets_init' );

/**
 * Add some useful default widgets to the Reddle sidebar
 */
function reddle_default_widgets() {
	global $sidebars_widgets;

	$sidebars = get_option( 'sidebars_widgets' );

	if ( empty( $sidebars['sidebar-1'] ) && isset( $_GET['activated'] ) ) {
		update_option( 'widget_calendar', array( 2 => array( 'title' => __( 'Archives', 'reddle' ) ), '_multiwidget' => 1 ) );
		update_option( 'widget_pages', array( 2 => array( 'title' => __( 'Pages', 'reddle' ) ), '_multiwidget' => 1 ) );
		update_option( 'widget_tag_cloud', array( 2 => array( 'title' => __( 'Topics', 'reddle' ) ), '_multiwidget' => 1 ) );
		update_option( 'widget_meta', array( 2 => array( 'title' => __( 'Info', 'reddle' ) ), '_multiwidget' => 1 ) );

		update_option( 'sidebars_widgets', array(
			'wp_inactive_widgets' => array(),
			'sidebar-1' => array(
				0 => 'calendar-2',
				1 => 'pages-2',
				2 => 'tag_cloud-2',
				3 => 'meta-2',
			),
			'array_version' => 3
		) );
	}
}
add_action( 'after_setup_theme', 'reddle_default_widgets' );

if ( ! function_exists( 'reddle_content_nav' ) ):
/**
 * Display navigation to next/previous pages when applicable
 *
 * @since Reddle 1.0
 */
function reddle_content_nav( $nav_id ) {
	global $wp_query;

	?>
	<nav id="<?php echo $nav_id; ?>">
		<h1 class="assistive-text section-heading"><?php _e( 'Post navigation', 'reddle' ); ?></h1>

	<?php if ( is_single() ) : // navigation links for single posts ?>

		<?php previous_post_link( '<div class="nav-previous">%link</div>', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'reddle' ) . '</span> %title' ); ?>
		<?php next_post_link( '<div class="nav-next">%link</div>', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'reddle' ) . '</span>' ); ?>

	<?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>

		<?php if ( get_next_posts_link() ) : ?>
		<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'reddle' ) ); ?></div>
		<?php endif; ?>

		<?php if ( get_previous_posts_link() ) : ?>
		<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'reddle' ) ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	</nav><!-- #<?php echo $nav_id; ?> -->
	<?php
}
endif; // reddle_content_nav


if ( ! function_exists( 'reddle_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own reddle_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Reddle 0.4
 */
function reddle_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'reddle' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '[Edit]', 'reddle' ), ' ' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer>
				<div class="comment-author vcard">
					<?php
					$comment_avatar_size = 47;
					if ( 0 != $comment->comment_parent )
						$comment_avatar_size = 23;

					echo get_avatar( $comment, $comment_avatar_size );
					?>
					<cite class="fn"><?php comment_author_link(); ?></cite>
				</div><!-- .comment-author .vcard -->
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'reddle' ); ?></em>
					<br />
				<?php endif; ?>

				<div class="comment-meta commentmetadata">
					<a class="comment-time" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><time pubdate datetime="<?php comment_time( 'c' ); ?>">
					<?php
						/* translators: 1: date, 2: time */
						printf( __( '%1$s at %2$s', 'reddle' ), get_comment_date(), get_comment_time() ); ?>
					</time></a>
					<?php edit_comment_link( __( '[Edit]', 'reddle' ), ' ' );
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
endif; // ends check for reddle_comment()

if ( ! function_exists( 'reddle_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own reddle_posted_on to override in a child theme
 *
 * @since Reddle 1.0
 */
function reddle_posted_on() {
	printf( __( '<span class="byline">Posted by <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span><span class="sep"> &mdash; </span><span class="entry-date"><time datetime="%3$s" pubdate>%4$s</time></span>', 'reddle' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		sprintf( esc_attr__( 'View all posts by %s', 'the-wolf' ), get_the_author() ),
		esc_html( get_the_author() )
	);
}
endif;

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Reddle 1.0
 */
function reddle_body_classes( $classes ) {
	// We should always have content
	$classes[] = 'primary';

	// If we have 1 sidebar active we have secondary content
	if ( is_active_sidebar( 'sidebar-1' ) || is_active_sidebar( 'sidebar-2' )  )
		$classes[] = 'secondary';

	// If we have both sidebars active we have tertiary content
	if ( is_active_sidebar( 'sidebar-1' ) && is_active_sidebar( 'sidebar-2' ) )
		$classes[] = 'tertiary';

	/**
	 * What's going on here?
	 * If there is a 'secondary' class we can override our basic CSS structure to make a 2-column layout
	 * adding some page width and some CSS to accommodate one widget area
	 * and if there is a 'tertiary' class we can override our basic structure to make a 3-column layout
	 * adding more page width and some CSS to accommodate two widget areas
	 */

	// Adds a class of index to views that are not posts or pages or search
	if ( ! is_singular() && ! is_search() ) {
		$classes[] = 'indexed';
	}

	// Adds a class of single-author to blogs with only 1 published author
	if ( ! is_multi_author() ) {
		$classes[] = 'single-author';
	}

	// Add as a class of fixed or fluid depending on whether or not there is or isn't a header image
	$header_image = get_header_image();
	if ( empty( $header_image ) ) {
		$classes[] = 'fluid';
	} else {
		$classes[] = 'fixed';
	}

	return $classes;
}
add_filter( 'body_class', 'reddle_body_classes' );

/**
 * Adds custom classes to the array of post classes.
 *
 * @since Reddle 1.0
 */
function reddle_post_classes( $classes ) {
	global $post;

	// Adds a class of has-featured-image
	if ( '' != get_the_post_thumbnail() ) {
		$classes[] = 'has-featured-image';
	}

	return $classes;
}
add_filter( 'post_class', 'reddle_post_classes' );

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 *
 * @since Reddle 1.0
 */
function reddle_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-3' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-4' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-5' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
	}

	if ( $class )
		echo 'class="' . $class . '"';
}

/**
 * Returns true if a blog has more than 1 category
 *
 * @since Reddle 1.0
 */
function reddle_categorized_blog() {
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
		// This blog has more than 1 category so reddle_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so reddle_categorized_blog should return false
		return false;
	}
}

/**
 * Flush out the transients used in reddle_categorized_blog
 *
 * @since Reddle 1.0
 */
function reddle_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'all_the_cool_cats' );
}
add_action( 'edit_category', 'reddle_category_transient_flusher' );
add_action( 'save_post', 'reddle_category_transient_flusher' );

/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 */
function reddle_enhanced_image_navigation( $url ) {
	global $post;

	if ( wp_attachment_is_image( $post->ID ) )
		$url = $url . '#main';

	return $url;
}
add_filter( 'attachment_link', 'reddle_enhanced_image_navigation' );

/**
 * WP.com: Set a default theme color array for WP.com.
 */
if ( ! isset( $themecolors ) ) {
	$themecolors = array(
		'bg' => 'ffffff',
		'text' => '111111',
		'link' => 'b12930',
		'border' => 'dddddd',
		'url' => 'b12930',
	);
}

/**
 * This theme was built with PHP, Semantic HTML, CSS, love, and a Reddle.
 */