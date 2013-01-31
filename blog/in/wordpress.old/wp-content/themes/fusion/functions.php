<?php
/**
 * @package WordPress
 * @subpackage Fusion
 */

$content_width = attachment_width();

function fusion_setup() {

	require_once( dirname( __FILE__ ) . '/inc/theme-options.php' );

	add_theme_support( 'automatic-feed-links' );

	load_theme_textdomain( 'fusion', get_template_directory() . '/languages' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'fusion' ),
	) );

	$themecolors = array(
		'bg' => 'ffffff',
		'border' => 'eeeeee',
		'text' => '666666',
		'link' => '20a3ca',
		'url' => 'ffb200',
	);
}

add_action( 'after_setup_theme', 'fusion_setup' );

function attachment_width() {
	$options = fusion_get_theme_options();
	$current_layout = $options['theme_layout'];
	$flexible_layout = $options['flexible_layout'];

	$two_columns = array( 'content-sidebar', 'sidebar-content' );
	$no_columns = array( 'no-sidebar' );
	$flexible = array( 'flexible' );

	if ( in_array( $current_layout, $two_columns ) )
		return 620;
	elseif ( in_array( $current_layout, $no_columns ) )
		return 958;
	elseif ( in_array( $flexible_layout, $flexible ) )
		return 0;
	else
		return 465;
}

function fusion_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'fusion_page_menu_args' );


function fusion_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Primary Widget Area', 'fusion' ),
		'id' => 'primary-widget-area',
		'description' => __( 'The primary widget area', 'fusion' ),
		'before_widget' => '<li><div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div></li>',
		'before_title' => '<h2 class="title">',
		'after_title' => '</h2>'
	));
	register_sidebar( array(
		'name' => __( 'Secondary Widget Area', 'fusion' ),
		'id' => 'secondary-widget-area',
		'description' => __( 'The secondary widget area (only on 3-col pages)', 'fusion' ),
		'before_widget' => '<li><div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div></li>',
		'before_title' => '<h2 class="title">',
		'after_title' => '</h2>'
	));
	register_sidebar( array(
		'name' => __( 'Footer Widget Area', 'fusion' ),
		'id' => 'footer-widget-area',
		'description' => __( 'Footer widget area', 'fusion' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s"><div class="the-content">',
		'after_widget' => '</div></li>',
		'before_title' => '<h4 class="title">',
		'after_title' => '</h4>'
	));
}

add_action( 'widgets_init', 'fusion_widgets_init' );

/**
 *	Custom header image support
 */

define( 'HEADER_IMAGE', '' );
define( 'HEADER_IMAGE_WIDTH', 980 );
define( 'HEADER_IMAGE_HEIGHT', 148 );
define( 'HEADER_TEXTCOLOR', 'fff' );

function fusion_admin_header_style() {
	?><style type="text/css">
			#headimg {
				background: url(<?php header_image(); ?>) no-repeat 50% bottom;
			}
			#headimg h1 {
				font-family: Georgia, Times, serif;
				font-variant: small-caps;
				letter-spacing: 1px;
				font-size: 46px;
				font-weight: bold;
				margin: 50px 0 0;
				padding: 0;
			}
			#headimg h1 a {
				background: rgba(0, 0, 0, 0.7);
				color: #<?php header_textcolor(); ?>;
				font-weight: normal;
				padding: 6px 20px 6px 20px;
				text-decoration: none;
				-webkit-border-radius: 6px;
				-moz-border-radius: 6px;
				border-radius: 6px;
			}
			#headimg #desc {
				display: none !important;
			}
	</style><?php
}

function fusion_header_style() {
?>
	<style type="text/css">
	<?php if ( '' != get_header_image() ) : ?>
			#page-wrap2 {
				background: url(<?php header_image(); ?>) no-repeat 50% bottom;
			}
	<?php endif;
	if ( 'blank' == get_header_textcolor() || '' == get_bloginfo( 'name' ) ) : ?>
			h1#title {
				display: none;
			}
	<?php else : ?>
			#header h1 a {
				color: #<?php header_textcolor(); ?> !important;
			}
	<?php endif; ?>
	</style><?php
}

add_custom_image_header( 'fusion_header_style', 'fusion_admin_header_style' );

/**
 *	Get current theme options with defaults as fallback
 */
function fusion_get_theme_options() {
	$defaults = array(
		'post_length' => 'full-post',
		'theme_layout' => 'content-sidebar',
		'flexible_layout' => 'fixed',
	);
	$options = get_option( 'fusion_theme_options', $defaults );
	return $options;
}

/**
 *	Returns either Full Posts or Excerpts as selected in the theme options
 */
function fusion_current_post_length() {
	$options = fusion_get_theme_options();
	$post_length = $options[ 'post_length' ];

	$excerpt_length = array( 'excerpt' );

	if ( in_array( $post_length, $excerpt_length ) )
		the_excerpt( __( 'Read the rest of this entry &raquo;', 'fusion' ) );
	else
		the_content( __( 'Read the rest of this entry &raquo;', 'fusion' ) );
}

/**
 *	Returns the current Fusion layout as selected in the theme options
 */
function fusion_current_layout() {
	$options = fusion_get_theme_options();
	$current_layout = $options[ 'theme_layout' ];

	$two_columns = array( 'content-sidebar', 'sidebar-content' );

	if ( in_array( $current_layout, $two_columns ) )
		return 'two-column ' . $current_layout;
	else
		return 'three-column ' . $current_layout;
}

/**
 *	Adds fusion_current_layout() to the array of body classes
 */
function fusion_body_class($classes) {
	$classes[] = fusion_current_layout();

	return $classes;
}
add_filter( 'body_class', 'fusion_body_class' );

/**
 *	Returns the current Fusion layout width option
 */
function fusion_current_layout_width() {
	$options = fusion_get_theme_options();
	$current_layout = $options[ 'flexible_layout' ];

	return $current_layout;
}

/**
 *	Adds fusion_current_layout_width() to the array of body classes
 */
function fusion_layout_options($classes) {
	$classes[] = fusion_current_layout_width();

	return $classes;
}
add_filter( 'body_class', 'fusion_layout_options' );

/**
 *  Lists Pings
 */
function list_pings( $comment, $args, $depth ) {
 $GLOBALS[ 'comment' ] = $comment;
 ?>

	<li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?>

<?php }
/**
 *  Lists Comments
 */
	function list_comments( $comment, $args, $depth ) {
	$GLOBALS[ 'comment' ] = $comment;
	global $commentcount;
	if( !$commentcount ) { $commentcount = 0; }
?>
	<!-- comment entry -->
	<li <?php if ( get_option( 'show_avatars' ) ) echo comment_class( 'with-avatars' ); else comment_class(); ?> id="comment-<?php comment_ID(); ?>">

	<div class="wrap tiptrigger">

		<?php if ( get_option( 'show_avatars' )): ?>

		<div class="avatar">
	 		<a class="gravatar"><?php echo get_avatar( $comment, 64 ); ?></a>
		</div>

		<?php endif; ?>

		<div class="details <?php if ( $comment->comment_author_email == get_the_author_meta( 'email' ) ) echo 'admincomment'; else echo 'regularcomment'; ?>">

			<p class="head">
			<span class="info">
				<?php
				if ( get_comment_author_url() ):
					$authorlink='<a id="commentauthor-' . get_comment_ID() .'" href="' . get_comment_author_url() . '">' . get_comment_author() . '</a>';
				else:
					$authorlink='<b id="commentauthor-' . get_comment_ID() . '">' . get_comment_author() . '</b>';
				endif;
				printf( __( '%s by %s on %s', 'fusion' ), '<a href="#comment-' . get_comment_ID() . '">#' . ++$commentcount . '</a>', $authorlink, get_comment_time( get_option( 'date_format' ) ) . ' - ' . get_comment_time( get_option( 'time_format' ) ) );
				?>
			</span>
			</p>

			<!-- comment contents -->
			<div class="text">

			 <?php if ( $comment->comment_approved == '0' ): ?>
				 <p class="error"><small><?php _e( 'Your comment is awaiting moderation.', 'fusion' ); ?></small></p>
			 <?php endif; ?>

				<div id="commentbody-<?php comment_ID(); ?>">
					<?php comment_text(); ?>
				</div>

		 	</div>

			<!-- /comment contents -->
 			</div>

		 	<div class="act tip">
				<?php if ( comments_open() ): ?>
	 			<?php comment_reply_link( array_merge( $args, array( 'add_below' => 'commentbody', 'depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => __( 'Reply', 'fusion' ) ) ) ); ?></span>
				<?php endif; ?>
				<?php edit_comment_link( __( 'Edit', 'fusion' ), '', '' ); ?>
		 	</div>

		</div>
<?php } // </li> is added by wordpress ?>