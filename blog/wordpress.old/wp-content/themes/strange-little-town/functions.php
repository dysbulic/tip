<?php
/**
 * @package WordPress
 * @subpackage StrangeLittleTown
 */

if ( ! isset( $content_width ) ) {
	$content_width = 900;
	if ( is_active_sidebar( 'sidebar-1' ) )
		$content_width = 600;
}

if ( ! isset( $themecolors ) ) {
	$themecolors = array(
		'bg'     => 'cf7da1',
		'text'   => '503559',
		'link'   => 'e8cdeb',
		'border' => 'd79eb7',
		'url'    => 'e8cdeb',
	);
}

if ( ! function_exists( 'strange_little_town_setup' ) ) {
	/**
	 * Public styles for the header image and text.
	 *
	 * Hooks into the 'after_setup_theme' action.
	 *
	 * @since StrangeLittleTown 1.0
	 */
	function strange_little_town_setup() {
		add_theme_support( 'automatic-feed-links' );

		define( 'BACKGROUND_COLOR', 'c15b8b' );
		add_custom_background();

		define( 'HEADER_IMAGE', '' ); /* Defaults to no header image. */
		define( 'HEADER_IMAGE_WIDTH', 950 );
		define( 'HEADER_IMAGE_HEIGHT', 200 );
		define( 'HEADER_TEXTCOLOR', 'ffffff' );
		add_custom_image_header( 'strange_little_town_header_style', 'strange_little_town_admin_header_style' );

		register_nav_menu( 'primary-menu', __( 'Primary', 'strange-little-town' ) );

		load_theme_textdomain( 'strange-little-town', get_template_directory() . '/languages' );
		add_action( 'widgets_init', 'strange_little_town_register_sidebar' );
	}
}
add_action( 'after_setup_theme', 'strange_little_town_setup' );

if ( ! function_exists( 'strange_little_town_header_style' ) ) {
	/**
	 * Public styles for the header image and text.
	 *
	 * @since StrangeLittleTown 1.0
	 */
	function strange_little_town_header_style() {
		if ( HEADER_TEXTCOLOR == get_header_textcolor() && '' == get_header_image() )
			return;
		?>
		<style type="text/css">
		<?php
			// Has the text been hidden?
			if ( 'blank' == get_header_textcolor() ) :
		?>
			h1#site-title,
			h2#site-description {
				position: absolute !important;
				clip: rect( 1px 1px 1px 1px ); /* IE6, IE7 */
				clip: rect( 1px, 1px, 1px, 1px );
			}
		<?php
			endif;
		?>
		<?php
			$image = get_header_image();
			if ( '' != $image ) :
		?>

			#branding {
				background: url('<?php echo esc_url( $image ); ?>');
				overflow: hidden;
				padding: 0;
				margin: 10px auto 20px;
			}
			#branding,
			#home-link {
				float: none;
				display: block;
				width: <?php echo absint( HEADER_IMAGE_WIDTH ); ?>px;
				height: <?php echo absint( HEADER_IMAGE_HEIGHT ); ?>px;

			}
			h1#site-title,
			h2#site-description {
				margin-left: 50px;
			}
			h1#site-title {
				padding-top: 50px;
			}
		<?php endif; ?>

		<?php if ( 'blank' != get_header_textcolor() ) : ?>
			#site-title,
			#site-description {
				color: <?php echo strange_little_town_sanitize_color( get_header_textcolor(), '20232d' ); ?>;
			}
		<?php endif; ?>
		</style>
		<?php
	}
}

if ( ! function_exists( 'strange_little_town_admin_header_style' ) ) {
	/**
	 * Styles the header image displayed on the Appearance > Header admin screen.
	 *
	 * Referenced via add_custom_image_header() in strange_little_town_setup().
	 *
	 * @since StrangeLittleTown 1.0
	 */
	function strange_little_town_admin_header_style() {
		$text_color = get_header_textcolor();

		$header = get_header_image();
		$background_image = get_background_image();
		$background_color = get_background_color();

		$image = '';
		if ( ! empty( $header ) )
			$image = $header;
		else if ( ! empty( $background_image ) )
			$image = $background_image;
		else if ( BACKGROUND_COLOR == $background_color || empty( $background_color ) )
			$image = get_template_directory_uri() . '/img/stars.png';
	?>
		<style type="text/css">
		#headimg {
			background-color: <?php echo strange_little_town_sanitize_color( $background_color ); ?>;
			background-image: url( '<?php echo esc_url( $image ); ?>' ) !important;
			border: 1px solid #eee;
			overflow: hidden;
			position: relative;
			width: <?php echo absint( HEADER_IMAGE_WIDTH ); ?>px;
			height: <?php echo absint( HEADER_IMAGE_HEIGHT ); ?>px;
		}
		#headimg h1,
		#desc {
			color: <?php strange_little_town_sanitize_color( $text_color, HEADER_TEXTCOLOR ); ?>;
			font-family: times, Times New Roman, times-roman, georgia, serif;
			text-shadow: rgba( 0, 0, 0, .3 ) 2px 2px 2px;
		}
		#headimg h1 {
			font-size: 55px;
			font-weight: 100;
			margin: 0 0 0 50px;
			padding-top: 50px;
			text-decoration: none;
			line-height: 1.3;
		}
		#headimg a {
			text-decoration: none;
		}
		#desc {
			font-size: 18px;
			margin: 0 0 0 50px;
			text-transform: uppercase;
		}
		</style>
	<?php
	}
}

if ( ! function_exists( 'strange_little_town_comment' ) ) :
	/**
	 * Template for comments and pingbacks.
	 *
	 * To override this walker in a child theme without modifying the comments template
	 * simply create your own strange_little_town_comment(), and that function will be used instead.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 * @since StrangeLittleTown 1.0
	 */
	function strange_little_town_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
		?>

		<article <?php comment_class(); ?>><?php _e( 'Pingback:', 'strange-little-town' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'strange-little-town' ), ' - ' ); ?></article>

		<?php
				break;
			default :
        ?>
        	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
				<article id="comment-<?php comment_ID(); ?>" class="comment contain">
					<footer class="contain">
						<div class="comment-author vcard">
							<?php echo get_avatar( $comment, 40 ); ?>
							<cite class="fn"><?php echo get_comment_author_link(); ?></cite>
						</div>

						<?php if ( $comment->comment_approved == '0' ) : ?>
							<em><?php _e( 'Your comment is awaiting moderation.', 'strange-little-town' ); ?></em>
                        <?php endif; ?>

						<div class="comment-meta commentmetadata">
							<a class="permalink" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
								<span class="date-published"><?php
									/* translators: %1$s date, %2$s: time */
									printf( __( '%1$s at %2$s', 'strange-little-town' ), get_comment_date(), get_comment_time() ); ?>
								</span>
							</a>
							<?php edit_comment_link( __( 'Edit', 'strange-little-town' ), ' - ' ); ?>
						</div>
					</footer>

					<div class="comment-content"><?php comment_text(); ?></div>

					<div class="reply"><?php
						comment_reply_link( array_merge( $args, array(
							'depth'      => $depth,
							'max_depth'  => $args['max_depth'],
							'reply_text' => __( 'Reply &darr;', 'strange-little-town' ),
						) ) );
					?></div>
				</article>

		<?php
				break;
		endswitch;
}
endif;

/**
 * Custom class attributes for post objects.
 *
 * Hooks into the "post_class" filter.
 *
 * @since StrangeLittleTown 1.0
 */
function strange_little_town_post_class( $classes ) {
	if ( is_singular() ) {
		$classes[] = 'singular';
	}
	return $classes;
}
add_filter( 'post_class', 'strange_little_town_post_class' );

/**
 * Register Sidebar.
 *
 * Hooks into the 'widgets_init' action.
 *
 * @access private
 * @since StrangeLittleTown 1.0
 */
function strange_little_town_register_sidebar() {
	register_sidebar( array(
		'name'          => __( 'Main Sidebar', 'strange-little-town' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => "</aside>",
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}

/**
 * Body Classes.
 *
 * Hooks into the 'body_class' filter.
 *
 * @param array $classes List of classes to be applied to the body element.
 * @return array Modified classes for the body element.
 *
 * @access private
 * @since StrangeLittleTown 1.0
 */
function strange_little_town_body_class( $classes ) {
	if ( ! is_active_sidebar( 'sidebar-1' ) )
		$classes[] = 'single-column';
	else if ( is_attachment() && 0 === strpos( get_post_field( 'post_mime_type', get_the_ID() ), 'image' ) )
		$classes[] = 'single-column';

	return $classes;
}
add_filter( 'body_class', 'strange_little_town_body_class' );

/**
 * Hide the Town.
 *
 * In it's natural state, this theme will displays a background
 * image in the main footer element. In the event that a user has
 * uploaded their own custom background image or has defined a
 * custom background color, the default image will be hidden.
 *
 * Hooks into the 'wp_head' action.
 *
 * @access private
 * @since StrangeLittleTown 1.0
 */
function strange_little_town_hide_town() {
	$hide_town = false;

	$image = get_background_image();
	$color = get_background_color();
	if ( empty( $color ) )
		$color = BACKGROUND_COLOR;

	if ( BACKGROUND_COLOR == $color && empty( $image ) )
		return;
?>
<!-- Strange Little Town: Start -->
<style type="text/css">
<?php if ( empty( $image ) ) : ?>
body {
	background-image: none;
}
<?php endif; ?>
#colophon,
#site-generator {
	background: transparent;
}
#site-generator {
	color: #ddd;
	padding: 4em 0 1.5em;
	text-align: center;
	width: auto;
}
#site-generator a {
	color: #ddd;
	text-decoration: underline;
}
#site-generator a:hover,
#site-generator a:focus,
#site-generator a:active {
	color: #ccc;
}
</style>
<!-- Strange Little Town: End -->
<?php
}
add_action( 'wp_head', 'strange_little_town_hide_town' );

/**
 * Sanitize Color.
 *
 * @param string $color RGB color represented in hexidecimal notation.
 * @param string $default Value to return if $color is not recognized as a color.
 *
 * @return string The value of $color if it is a color represented in hexidecimal notation; the value of $default otherwise. Both values with have a hash character prepended to them.
 *
 * @since StrangeLittleTown 1.0
 */
function strange_little_town_sanitize_color( $color, $default = BACKGROUND_COLOR ) {
	if ( ! ctype_xdigit( $color ) )
		return '#' . $default;
	if ( ! in_array( strlen( $color ), array( 3, 6 ) ) )
		return '#' . $default;
	return '#' . $color;
}