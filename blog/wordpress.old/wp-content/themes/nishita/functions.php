<?php
// Set the content width based on the chosen layout.
if ( ! isset( $content_width ) )
	$content_width = nishita_attachment_width();
	
// Define Theme Colors
if ( ! isset( $themecolors ) ) {
	$themecolors = array(
		'bg' => 'f2f2f2',
		'text' => '727272',
		'link' => '7292c2',
		'border' => 'f0f0f0',
		'url' => '7292c2',
	);
}

/**
 * Tell WordPress to run nishita_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'nishita_setup' );

if ( ! function_exists( 'nishita_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override nishita_setup() in a child theme, add your own nishita_setup to your child theme's
 * functions.php file.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links, and Post Formats.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 */
function nishita_setup() {
	
	// Load up our theme options page and related code.
	require( dirname( __FILE__ ) . '/inc/theme-options.php' );

	// Automatic Feed Links
	add_theme_support( 'automatic-feed-links' );

	// I18n
	load_theme_textdomain( 'nishita', get_template_directory_uri() . '/languages' );

	$locale = get_locale();
	$locale_file = get_template_directory_uri() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// Navigation Menu
	register_nav_menu( 'primary', __( 'Primary Menu', 'nishita' ) );

	// Custom Background
	add_custom_background();

	// Post Thumbnails
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 128, 128 ); // 128 pixels wide by 128 pixels tall, soft proportional crop mode
	
	// Define constants in order for the custom image header to work
	define( 'HEADER_IMAGE', '' );
	define( 'HEADER_IMAGE_WIDTH', 1024 ); // use width and height appropriate for your theme
	define( 'HEADER_IMAGE_HEIGHT', 295 );
	define( 'HEADER_TEXTCOLOR', '727272' ); // The default header text color

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See nishita_admin_header_style(), below
	add_custom_image_header( 'nishita_header_style', 'nishita_admin_header_style', 'nishita_admin_header_image' );

}
endif; // nishita_setup

/**
 * Register our sidebars and widgetized areas.
 */
add_action( 'widgets_init', 'nishita_register_sidebars' );

if ( ! function_exists( 'nishita_register_sidebars' ) ) :

	function nishita_register_sidebars() {
		/*
			Add theme support for widgetized sidebars.
		*/
		register_sidebar(array(
			'name' => __( 'Primary Sidebar', 'nishita' ),
			'id' => 'primary-sidebar',
			'description' => __( 'Widgets in this sidebar will be shown adjacent to all site content (with the exception of individual image attachment pages).', 'nishita' ),
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		));
		register_sidebar(array(
			'name' => __( 'Footer Area One', 'nishita' ),
			'id' => 'footer-area-one',
			'description' => __( 'Widgets in this area will be shown below all site content and adjacent to the "Footer Area Two" area.', 'nishita' ),
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		));
		register_sidebar(array(
			'name' => __( 'Footer Area Two', 'nishita' ),
			'id' => 'footer-area-two',
			'description' => __( 'Widgets in this area will be shown below all site content and adjacent to the "Footer Area One" and "Footer Area Three" areas.', 'nishita' ),
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		));
		register_sidebar(array(
			'name' => __( 'Footer Area Three', 'nishita' ),
			'id' => 'footer-area-three',
			'description' => __( 'Widgets in this area will be shown below all site content and adjacent to the "Footer Area Two" area.', 'nishita' ),
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		));
		
	}

endif; // nishita_register_sidebars

/**
 * Modify the font sizes of WordPress' tag cloud
 */
if ( ! function_exists( 'nishita_widget_tag_cloud_args' ) ) :

	function nishita_widget_tag_cloud_args( $args ) {
		$args['smallest'] = 12;
		$args['largest']= 18;
		$args['unit']= 'px';
		return $args;
	}
	add_filter( 'widget_tag_cloud_args', 'nishita_widget_tag_cloud_args' );

endif; // nishita_widget_tag_cloud_args

if ( ! function_exists( 'nishita_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own nishita_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 */
function nishita_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'nishita' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'nishita' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 48;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 24;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s on %2$s <span class="says">said:</span>', 'nishita' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s at %2$s', 'nishita' ), get_comment_date(), get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( __( 'Edit', 'nishita' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'nishita' ); ?></em>
					<br />
				<?php endif; ?>

			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'nishita' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for nishita_comment()

/**
 * Calendar Widget Title
 *
 * For some reason, WordPress will print a non-breaking space
 * entity wrapped in the appropriate tags for the calendar
 * widget even if the title's value is left empty by the user.
 * This function will remove the empty heading tag.
 *
 * Hooked into "widget_title".
 *
 * It is possible that this filter may not be needed in the future:
 *
 * @see http://core.trac.wordpress.org/ticket/17837
 *
 * @param string $title The value of the calendar widget's title for this instance.
 * @param unknown $instance
 * @param string $id_base
 * @return string Calendar widget title.
 */
function nishita_calendar_widget_title( $title = '', $instance = '', $id_base = '' ) {
	if ( 'calendar' == $id_base && '&nbsp;' == $title )
		$title = '';

	return $title;
}
add_filter( 'widget_title',    'nishita_calendar_widget_title', 10, 3 );

if ( ! function_exists( 'nishita_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 */
function nishita_header_style() {

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
		#title h1,
		#tagline {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		#title h1 a,
		#tagline {
			color: #<?php echo get_header_textcolor(); ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // nishita_header_style

if ( ! function_exists( 'nishita_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in nishita_setup().
 */
function nishita_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#headimg {
		width: 768px !important;
	}
	#headimg h1,
	#desc {
		font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
	}
	#headimg h1 {
		font-size: 22px;
		font-weight: normal;
		margin: 0;
		padding: 0;
	}
	#headimg h1 a {
		border-bottom: 2px solid #f2f2f2;
		display: block;
		color: #7292c2;
		letter-spacing: -1px;
		line-height: 27px;
		margin-bottom: 5px;
		padding: 35px 0 3px;
		text-decoration: none;
		text-transform: uppercase;
	}
	#desc {
		font-size: 11px;
		line-height: 18px;
		margin-bottom: 10px;
	}
	</style>
<?php
}
endif; // nishita_admin_header_style

if ( ! function_exists( 'nishita_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in nishita_setup().
 *
 */
function nishita_admin_header_image() { ?>
	<div id="headimg">
		<?php
		if ( 'blank' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) || '' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) )
			$style = ' style="display:none;"';
		else
			$style = ' style="color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';"';
		?>
		<h1><a id="name"<?php echo $style; ?> onClick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }
endif; // nishita_admin_header_image

/**
 * Returns the value of content_width depending on the current layout.
 *
 */
function nishita_attachment_width() {
	$options = get_option( 'nishita_theme_options' );
	$current_layout = $options['theme_layout'];

	if ( in_array( $current_layout, array( 'photoblog' ) ) || empty ( $current_layout ) )
		return 1024;
	else
		return 768;
}