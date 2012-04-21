<?php
/**
 * @package WordPress
 * @subpackage Andrea
 */

// Calculate content_width based on layout option
$content_width = 1000;
$options = andrea_get_theme_options();
if ( 'fixed-width' == $options['layout_choice'] )
	$content_width = 500;

function andrea_setup() {
	$options = andrea_get_theme_options();

	$themecolors = array(
		'bg' => '00355F',
		'border' => '246192',
		'text' => 'D4E7F7',
		'link' => '87B2D8',
		'url' => '4E8ABE'
	);

	// Widgets
	register_sidebar( array(
		'name' => __( 'Sidebar', 'andrea' ),
		'id' => 'sidebar-1',
		'description' => __( 'The sidebar widget area', 'andrea' ),
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Feeds
	add_theme_support( 'automatic-feed-links' );

	// Custom navigation menu
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'andrea' ),
	) );

	// Custom background
	add_custom_background();

	// Custom header image
	define( 'HEADER_TEXTCOLOR', '4e8abe' );
	define( 'HEADER_IMAGE', '' );

	if ( 'fixed-width' == $options['layout_choice'] ) {
		define( 'HEADER_IMAGE_WIDTH', 690 );
		define( 'HEADER_IMAGE_HEIGHT', 145 );
	} else {
		define( 'HEADER_IMAGE_WIDTH', 1270 );
		define( 'HEADER_IMAGE_HEIGHT', 260 );		
	}

	add_custom_image_header( 'andrea_header_style', 'andrea_admin_header_style' );
}
add_action( 'after_setup_theme', 'andrea_setup' );

// Styles for the admin header image
function andrea_admin_header_style() { 
?>
	<style type="text/css">
		#headimg {
			background-color: #00355f;
			background-repeat: no-repeat;
		}
		#headimg h1, #headimg h1 a, #headimg #desc {
			margin: 0;
			padding: 10px 5px;
			font-size: 22px;
			font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif;
			letter-spacing: -1px;
			line-height: 27px;
		}
		#headimg h1 {
			float: left;
		}
		#headimg h1 a {
			color: #fff !important;
			text-decoration: none;
		}
		#headimg #desc {
			float: left;
		}
	</style>
<?php
}

// Styles for the header image
function andrea_header_style() {
?>
	<style type="text/css">
	<?php if ( '' != get_header_image() ) { ?>
		#header img {
			max-width: 100%;
		}
		#header {
			position: relative;
		}
		#header h1 {
			position: absolute;
			top: 0;
			left: 20px;
			color: #<?php header_textcolor(); ?>;
		}
	<?php } ?>
	<?php if ( 'blank' != get_header_textcolor() ) { ?>
		#header h1 {
			color: #<?php header_textcolor(); ?>;
		}
	<?php } else { ?>
		#header h1, #header h1 a {
			display: none;
		}
	<?php } ?>
	</style>
<?php
}

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function andrea_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'andrea_page_menu_args' );

/**
 *Changed wp_page_menu structure to get rid of the wrapped div and add menu_class arguments to <ul>
 */
function andrea_add_menu_class ($page_markup) {
	preg_match('/^<div class=\"([a-z0-9-_]+)\">/i', $page_markup, $matches);
	$divclass = $matches[1];
	$toreplace = array('<div class="'.$divclass.'">', '</div>');
	$new_markup = str_replace($toreplace, '', $page_markup);
	$new_markup = preg_replace('/^<ul>/i', '<ul class="'.$divclass.'">', $new_markup);
	return $new_markup;
}
add_filter('wp_page_menu', 'andrea_add_menu_class');

// Load theme options
require_once( get_template_directory() . '/inc/theme-options.php' );

function andrea_comment( $comment, $args, $depth ) {
	// Based on Twenty Ten comments
	$GLOBALS['comment'] = $comment; ?>

	<?php if ( '' == $comment->comment_type ) : ?>

	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<div class="commentmod"><?php _e( 'Your comment is awaiting moderation.', 'andrea' ); ?></div>
		<?php endif; ?>

		<div class="comment-body">
			<?php comment_text(); ?>
			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</div>

		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 40 ); ?>
			<?php printf( '<cite class="fn">%s</cite>', get_comment_author_link() ); ?>
			<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
				<?php printf( __( '%1$s at %2$s', 'andrea' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( 'Edit', 'andrea' ), ' &mdash; ' );
				?>
			</div><!-- .comment-meta .commentmetadata -->
		</div><!-- .comment-author .vcard -->

	<?php else : ?>

	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'andrea' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('&mdash; Edit', 'andrea' ), ' ' ); ?></p>

	<?php endif;
}

function andrea_get_theme_options() {
	$defaults = array(
		'layout_choice' => 'flexible-width',
	);
	$options = get_option( 'andrea_theme_options', $defaults );
	
	return $options;
}