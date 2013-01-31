<?php
/**
 * Bold Life functions and definitions
 *
 * @package WordPress
 * @subpackage Bold_Life
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 538;

/**
 * Define Theme Colors
 */
if ( ! isset( $themecolors ) ) {
	$themecolors = array(
		'bg' => 'f2efe8',
		'text' => '474534',
		'link' => 'da0a0a',
		'border' => '9d9474',
		'url' => 'da0a0a',
	);
}

/**
 * Tell WordPress to run boldlife_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'boldlife_setup' );

if ( ! function_exists( 'boldlife_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override boldlife_setup() in a child theme, add your own boldlife_setup to your child theme's
 * functions.php file.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links, and Post Formats.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_custom_image_header() To add support for a custom header.
 *
 */
function boldlife_setup() {

	// Automatic Feed Links
	add_theme_support( 'automatic-feed-links' );

	// I18n
	load_theme_textdomain( 'bold-life', get_template_directory_uri() . '/languages' );

	$locale = get_locale();
	$locale_file = get_template_directory_uri() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// Navigation Menu
	register_nav_menu( 'primary', __( 'Primary Menu', 'bold-life' ) );

	// Custom Background
	add_custom_background();

	// Define constants in order for the custom image header to work
	define( 'HEADER_IMAGE', '' );
	define( 'HEADER_IMAGE_WIDTH', 950 );
	define( 'HEADER_IMAGE_HEIGHT', 264 );
	define('NO_HEADER_TEXT', true );
	define( 'HEADER_TEXTCOLOR', '' );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See nishita_admin_header_style(), below
	add_custom_image_header( 'boldlife_header_style', 'boldlife_admin_header_style' );

}
endif; // boldlife_setup

/**
 * Register our sidebars and widgetized areas.
 */
add_action( 'widgets_init', 'boldlife_register_sidebars' );

if ( ! function_exists( 'boldlife_register_sidebars' ) ) :

	function boldlife_register_sidebars() {

		/*
			Add theme support for widgetized sidebars.
		*/
		register_sidebar( array(
			'name' => __( 'Primary Sidebar', 'bold-life' ),
			'id' => 'primary-sidebar',
			'description' => __( 'Widgets in this sidebar will be shown adjacent to all site content (with the exception of individual image attachment pages).', 'bold-life' )
		) );

		/*
			Add Bold Life-specific RSS subscription button widget
		*/
		wp_register_sidebar_widget(
			'boldlife-rss', 								// unique widget ID
			__( 'Subscribe to RSS Button', 'bold-life' ),   // widget name
			'widget_boldlife_rss', 							// callback function
			array( 											// options
				'description' => 'Add a large, bright "Subscribe to RSS" button to your sidebar.',
				'classname' => 'widget-boldlife-rss'
			)
		);

	}

endif; // boldlife_register_sidebars

/**
 * Modify the font sizes of WordPress' tag cloud
 */
if ( ! function_exists( 'boldlife_widget_tag_cloud_args' ) ) :

	function boldlife_widget_tag_cloud_args( $args ) {
		$args['smallest'] = 12;
		$args['largest']= 20;
		$args['unit']= 'px';
		return $args;
	}
	add_filter( 'widget_tag_cloud_args', 'boldlife_widget_tag_cloud_args' );

endif; // boldlife_widget_tag_cloud_args

/**
 * Build Subscribe to RSS widget
 */
if ( ! function_exists( 'widget_boldlife_rss' ) ) :

	function widget_boldlife_rss($args) {
		extract($args);
		echo $before_widget; ?>

			<a href="<?php bloginfo( 'rss2_url' ); ?>" class="rss"><?php _e( 'Subscribe to RSS', 'bold-life' ); ?></a>

		<?php echo $after_widget;
	}

endif; // widget_boldlife_rss

if ( ! function_exists( 'boldlife_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own boldlife_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function boldlife_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'bold-life' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'bold-life' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 60;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 36;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s on %2$s <span class="says">said:</span>', 'bold-life' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s at %2$s', 'bold-life' ), get_comment_date(), get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( __( 'Edit', 'bold-life' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'bold-life' ); ?></em>
					<br />
				<?php endif; ?>

			</div>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'bold-life' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</div><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for boldlife_comment()

if ( ! function_exists( 'boldlife_get_the_password_form' ) ) :
/**
 * Add HTML classes to protected post/page forms. See http://core.trac.wordpress.org/ticket/18729
 * for more information about why this is needed. It may no longer be needed in future versions of WordPress.
 */
function boldlife_get_the_password_form( $output ) {
	global $post;
	$label = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
	$output = '<form class="post-password-form" action="' . get_option( 'siteurl' ) . '/wp-pass.php" method="post">
		<p>' . __( 'This post is password protected. To view it please enter your password below:', 'bold-life' ) . '</p>
		<p><label class="screen-reader-text" for="' . $label . '">' . __( 'Password:', 'bold-life' ) . '</label></p>
		<p><input class="post-password-input" name="post_password" id="' . $label . '" type="password" size="20" /><input class="post-password-submit" type="submit" name="Submit" value="' . esc_attr__( 'Submit', 'bold-life' ) . '" /></p>
	</form>
	';
	return $output;
}
add_filter( 'the_password_form', 'boldlife_get_the_password_form' );

endif; // ends check for boldlife_get_the_password_form()

if ( ! function_exists ( 'boldlife_custom_background' ) ) :
/**
 * Bold Life uses multiple background images to construct the site background, so we
 * need to check if a custom background image has been added via the Theme Options panel.
 * If a custom image has been added, then we will use it, otherwise we'll use the background
 * images provided by Bold Life.
 */
function boldlife_custom_background() {
	$background = get_background_image();
	$background_color = get_background_color();
	if ( ! empty( $background ) ) { ?>
		<style type="text/css">
			#top-wrap,
			#bottom-wrap {
				background: none;
			}
		</style>
	<?php }
	if ( empty( $background ) && ! empty ( $background_color ) ) { ?>
		<style type="text/css">
			body,
			#top-wrap,
			#bottom-wrap {
				background-image: none;
			}
		</style>
	<?php }
}
add_filter( 'wp_head', 'boldlife_custom_background' );

endif;

if ( ! function_exists ( 'boldlife_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 */
function boldlife_header_style() {
$header_image = get_header_image();
if ( ! empty( $header_image ) ) : ?>

	<style type="text/css">
		#header-image {
			background: url( '<?php echo esc_url( $header_image ); ?>' ) no-repeat;
			float: left;
			margin: 0 0 24px;
			width: 950px;
			height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
		}
	</style>

<?php endif; // check for header image
}
endif; // boldlife_header_style

if ( ! function_exists ( 'boldlife_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in boldlife_setup().
 */
function boldlife_admin_header_style() { ?>

	<style type="text/css">
		#headimg {
			width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
			height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
			background: no-repeat;
		}
	</style>

<?php }
endif;