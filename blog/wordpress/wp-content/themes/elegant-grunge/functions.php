<?php

$themecolors = array(
	'bg' => 'bebcad',
	'border' => 'bebcad',
	'text' => '000000',
	'link' => '5785a4',
	'url' => '5785a4'
);

$content_width = 490;
if ( 'no-sidebar' == elegant_grunge_current_layout() )
	$content_width = 760;

/**
 * Setting up the theme and custom features.
 */
function elegant_grunge_setup() {

	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'elegant-grunge' ),
	) );

	add_theme_support( 'automatic-feed-links' );

	define( 'HEADER_TEXTCOLOR', 'd3d3d3' );
	define( 'HEADER_IMAGE', '' );
	define( 'HEADER_IMAGE_WIDTH', 956 );
	define( 'HEADER_IMAGE_HEIGHT', 160 );

	add_custom_image_header( 'elegant_grunge_header_style', 'elegant_grunge_admin_header_style' );
}
add_action( 'after_setup_theme', 'elegant_grunge_setup' );

/**
 *  Header styles
 */
function elegant_grunge_header_style() {
?>
	<style type="text/css">
	<?php if ( 'blank' != get_header_textcolor() ) { ?>
		#header div a,
		#header h1,
		#header h2,
		#blog-description {
			color: #<?php header_textcolor(); ?>;
		}
	<?php } else { ?>
		#header h1,
		#header h2,
		#blog-description {
			display: none;
		}
	<?php } ?>
	</style>
<?php
}

function elegant_grunge_admin_header_style() {
?>
	<style type="text/css">
		#headimg h1 {
			float: left;
			font: 35px/169px "Georgia", "Baskerville", serif !important;
			margin: 0 0 0 0.5em !important;
		}
		#headimg h1 a {
			float: left;
			font-style: normal;
			font-weight: normal;
			text-decoration: none;
			text-shadow: #000 0 1px 2px;
		}
		#desc {
			font-family: Palatino, Georgia, Baskerville, serif;
			font-size: 16px;
			position: relative;
			top: 60px;
			left: 1.3em;
			text-shadow: #000 0 1px 1px;
		}
	</style>
<?php }

/**
 * Set up widget areas
 */
function elegant_grunge_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Primary Widget Area', 'elegant-grunge' ),
		'id' => 'sidebar-1',
		'description' => __( 'The primary widget area', 'elegant-grunge' ),
		'before_widget ' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	) );
	register_sidebar( array(
		'name' => __( 'Secondary Widget Area', 'elegant-grunge' ),
		'id' => 'sidebar-2',
		'description' => __( 'The secondary widget area appears with 3-column layouts (two sidebars)', 'elegant-grunge' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	) );
	register_sidebar( array(
		'name' => __( 'Footer Widget Area', 'elegant-grunge' ),
		'id' => 'footer-1',
		'before_widget' => '<div class="widget-wrap"><div class="widget %2$s">',
		'after_widget' => '</div></div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	) );
}
add_action( 'widgets_init', 'elegant_grunge_widgets_init' );

/**
 * Adding home link to page navigation
 */
function elegant_grunge_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'elegant_grunge_page_menu_args' );

/**
 * Returns the current layout as selected in the theme options
 */
function elegant_grunge_current_layout() {
	$options = get_option( 'elegant_grunge_theme_options' );
	$current_layout = $options['theme_layout'];

	if ( is_attachment() || is_page_template( 'no-sidebar-page.php') )
		return 'no-sidebar';

	$two_columns = array( 'content-sidebar', 'sidebar-content' );
	if ( in_array( $current_layout, $two_columns ) )
		return 'two-column ' . $current_layout;
	elseif ( 'content-sidebar-sidebar' == $current_layout )
		return 'three-column ' . $current_layout;

	return $current_layout;
}

/**
 * Adds elegant_grunge_current_layout() to the array of body classes
 */
function elegant_grunge_body_class($classes) {
	$classes[] = elegant_grunge_current_layout();

	return $classes;
}
add_filter( 'body_class', 'elegant_grunge_body_class' );

/**
 * Load theme options
 */
require_once( dirname( __FILE__ ) . '/inc/theme-options.php' );

/**
 * Comments template callback
 */
function elegant_grunge_comments_template( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<div class="comment-content">
			<div class="before-comment"></div>
			<div class="comment">
				<?php echo get_avatar ( $comment, 32 ); ?>
				<cite>
					<?php comment_author_link(); ?>
				</cite>
				<?php _x( 'Says:', 'elegant-grunge' ); ?>
				<?php if ( '0' == $comment->comment_approved ) : ?>
				<em><?php _e( 'Your comment is awaiting moderation.', 'elegant-grunge' ); ?></em>
				<?php endif; ?>
				<br />
				<small class="commentmetadata"><a href="#comment-<?php comment_ID(); ?>" title="">
					<?php printf( _x( '%1$s at %2$s', 'elegant-grunge' ), get_comment_date( __( 'F jS, Y', 'elegant-grunge' ) ), get_comment_time() ); ?></a>
					<?php edit_comment_link( __( 'edit', 'elegant-grunge' ), '|&nbsp;', '' ); ?>
				</small>
				<div class="comment-text">
					<?php comment_text(); ?>
				</div>
				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div>
		</div>
	</div>
<?php
}