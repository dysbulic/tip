<?php
/**
 * @package WordPress
 * @subpackage AutoFocus
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 590;

/**
 * Define Theme Colors
 */
if ( ! isset( $themecolors ) ) {
	$themecolors = array(
		'bg' => 'fff',
		'text' => '777',
		'link' => '111',
		'border' => 'eee',
		'url' => '111',
	);
}

/**
 * Theme Setup
 */
add_action( 'after_setup_theme', 'autofocus_setup' );

if ( ! function_exists ( 'autofocus_setup' ) ) :

	function autofocus_setup() {

		// Automatic Feed Links
		add_theme_support( 'automatic-feed-links' );

		// Editor CSS
		add_editor_style();

		// I18n
		load_theme_textdomain( 'autofocus', get_template_directory_uri() . '/languages' );

		$locale = get_locale();
		$locale_file = get_template_directory_uri() . "/languages/$locale.php";
		if ( is_readable( $locale_file ) )
			require_once( $locale_file );

		// Navigation Menu
		register_nav_menu( 'primary', __( 'Primary Menu', 'autofocus' ) );

		// Post Thumbnails
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'autofocus-800x1200', 800, 1200 ); // 800 pixels wide by 1200 pixels tall, resize mode
		add_image_size( 'autofocus-600x1200', 600, 1200 ); // 600 pixels wide by 1200 pixels tall, resize mode
		add_image_size( 'autofocus-595x1200', 595, 1200 ); // 595 pixels wide by 1200 pixels tall, resize mode

		// Custom Background
		add_custom_background();

		// Define constants in order for the custom image header to work
		define( 'HEADER_IMAGE', '' );
		define( 'HEADER_IMAGE_WIDTH', 800 );
		define( 'HEADER_IMAGE_HEIGHT', 216 );
		define( 'HEADER_TEXTCOLOR', '444' );

		// Add a way for the custom header to be styled in the admin panel that controls
		// custom headers. See autofocus_admin_header_style(), below
		add_custom_image_header( 'autofocus_header_style', 'autofocus_admin_header_style', 'autofocus_admin_header_image' );

	}
endif; // autofocus_setup

/**
 * Register our sidebars and widgetized areas.
 */
add_action( 'widgets_init', 'autofocus_register_sidebars' );

if ( ! function_exists( 'autofocus_register_sidebars' ) ) :

	function autofocus_register_sidebars() {
		/*
			Add theme support for widgetized sidebars.
		*/
		register_sidebar( array(
			'name' => __( 'Primary Sidebar', 'autofocus' ),
			'id' => 'primary-sidebar',
			'description' => __( 'Widgets in this sidebar will be shown adjacent to page content.', 'autofocus' ),
			'before_title' => '<h4 class="widget-title">',
			'after_title' => '</h4>'
		) );
	}

endif; // autofocus_register_sidebars

if ( ! function_exists( 'autofocus_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own autofocus_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 */
function autofocus_comment( $comment, $args, $depth ) {
	$GLOBALS[ 'comment' ] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'autofocus' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'autofocus' ), '<span class="edit-comment-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 48;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 24;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s on %2$s <span class="says">said:</span>', 'autofocus' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s at %2$s', 'autofocus' ), get_comment_date(), get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( __( 'Edit', 'autofocus' ), '<span class="edit-comment-link">', '</span>' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'autofocus' ); ?></em>
					<br />
				<?php endif; ?>

			</div>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'autofocus' ), 'depth' => $depth, 'max_depth' => $args[ 'max_depth' ] ) ) ); ?>
			</div><!-- .reply -->
		</div><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for autofocus_comment()

/**
 * Sets the post excerpt length to 30 words.
 */
function autofocus_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'autofocus_excerpt_length' );

/**
 * Modify the font sizes of WordPress' tag cloud
 */
if ( ! function_exists( 'autofocus_widget_tag_cloud_args' ) ) :

	function autofocus_widget_tag_cloud_args( $args ) {
		$args[ 'smallest' ] = 13;
		$args[ 'largest' ]= 20;
		$args[ 'unit' ]= 'px';
		return $args;
	}
	add_filter( 'widget_tag_cloud_args', 'autofocus_widget_tag_cloud_args' );

endif; // autofocus_widget_tag_cloud_args

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and autofocus_continue_reading_link().
 */
function autofocus_auto_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'autofocus_auto_excerpt_more' );

if ( ! function_exists ( 'autofocus_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 */
function autofocus_header_style() {

	$header_image = get_header_image();

	if ( ! empty( $header_image ) ) : ?>
		<style type="text/css">
			#container {
				padding-top: 0;
			}
			#header-image {
				background: url( '<?php echo esc_url( $header_image ); ?>' ) no-repeat;
				float: left;
				margin: 0 0 24px;
				width: 800px;
				height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
			}
			#header-image a {
				display: block;
				text-indent: -9999px;
				width: 100%;
				height: 100%;
			}
		</style>
	<?php endif; // $header_image check ?>

	<?php if ( HEADER_TEXTCOLOR != get_header_textcolor() ) : ?>
		<style type="text/css">
			<?php
				// Has the text been hidden?
				if ( 'blank' == get_header_textcolor() ) :
			?>
				#header {
					display: none;
				}
				#access,
				div.menu {
					margin: 0 0 24px;
					width: 800px;
				}
				#access li,
				div.menu li {
					float: left;
					width: auto;
				}
				#access ul ul,
				div.menu ul ul {
					top: 21px;
					left: -3px;
				}
				#access ul ul ul,
				div.menu ul ul ul {
					left: 160px;
					top: -3px;
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
	<?php endif; // custom text color check ?>

<?php }
endif; // autofocus_header_style

if ( ! function_exists ( 'autofocus_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in autofocus_setup().
 */
function autofocus_admin_header_style() { ?>

	<style type="text/css">
		#header-image {
			width: 800px;
		}
		#header {
			padding: 24px 210px 72px 0;
			width: 590px;
		}
		#header-image img {
			display: block;
			margin: 0;
		}
		#autofocus-site-title,
		#desc {
			font-family: "Hoefler Text", "Baskerville old face", Garamond, "Times New Roman", serif;
			padding: 0 0 0 5px;
		}
		#autofocus-site-title {
			font-size: 25px;
			font-weight: normal;
			line-height: 24px;
			margin-bottom: 0;
		}
		#autofocus-site-title a {
			color: #444;
			display: inline-block;
			text-decoration: none;
		}
		#desc {
			font-size: 14px;
			line-height: 24px;
			margin-bottom: 0;
		}
	</style>

<?php }
endif; // autofocus_admin_header_style

if ( ! function_exists( 'autofocus_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in autofocus_setup().
 *
 */
function autofocus_admin_header_image() { ?>

	<div id="header-image">
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
	</div>
	<?php
	if ( 'blank' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) || '' == get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) )
		$style = ' style="display:none;"';
	else
		$style = ' style="color:#' . get_theme_mod( 'header_textcolor', HEADER_TEXTCOLOR ) . ';"';
	?>
	<div id="header">
		<h1 id="autofocus-site-title"><a id="name"<?php echo $style; ?> onClick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<h2 id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></h2>
	</div><!-- #header -->

<?php }
endif; // autofocus_admin_header_image