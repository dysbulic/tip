<?php
/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 645;

/** Set the theme colors for Google Ads with the default color scheme */
$themecolors = array(
	'bg' => 'ffffff',
	'text' => '444444',
	'link' => 'cd4517'
);

/** Tell WordPress to run choco_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'choco_setup' );

if ( ! function_exists( 'choco_setup' ) ):

function choco_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'choco', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'choco' ),
	) );

	// This theme allows users to set a custom background
	add_custom_background();

}
endif;


/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 *
 */
function choco_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'choco_page_menu_args' );

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 *
 * To override this in a child theme, remove the filter and optionally add your own
 * function tied to the widgets_init action hook.
 *
 */
function choco_remove_recent_comments_style() {
	add_filter( 'show_recent_comments_widget_style', '__return_false' );
}
add_action( 'widgets_init', 'choco_remove_recent_comments_style' );
/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme's style.css. This is just
 * a simple filter call that tells WordPress to not use the default styles.
 *
 */
add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Deprecated way to remove inline styles printed when the gallery shortcode is used.
 *
 * This function is no longer needed or used. Use the use_default_gallery_style
 * filter instead, as seen above.
 *
 */
function choco_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
add_filter( 'gallery_style', 'choco_remove_gallery_css' );

register_sidebar( array (
		'name'			=> 'Sidebar',
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget'	=> '</li>',
		'before_title'	=> '<h4 class="widgettitle">',
		'after_title'	=> '</h4>',
) );

/**
 *Changed wp_page_menu structure to get rid of the wrapped div and add menu_class arguments to <ul>
 */
function choco_add_menu_class ($page_markup) {
	preg_match('/^<div class=\"([a-z0-9-_]+)\">/i', $page_markup, $matches);
	$divclass = $matches[1];
	$toreplace = array('<div class="'.$divclass.'">', '</div>');
	$new_markup = str_replace($toreplace, '', $page_markup);
	$new_markup = preg_replace('/^<ul>/i', '<ul class="'.$divclass.'">', $new_markup);
	return $new_markup;
}
add_filter('wp_page_menu', 'choco_add_menu_class');

function choco_print_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class( 'comment' ); ?>>
		<div class="comment-body" id="comment-<?php comment_ID(); ?>">
			<?php echo get_avatar( $comment, 70 ); ?>
			<p class="author">
				<?php comment_author_link(); ?>
			</p>
			<p class="comment-meta">
				<?php comment_date(); ?> at <?php comment_time(); ?>
			</p>
			<div class="comment-content">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php _e( 'Your comment is awaiting moderation.', 'choco' ); ?></em><br />
				<?php endif; ?>

				<?php comment_text(); ?>
				<div class="alignleft"><?php edit_comment_link(__( '(Edit)', 'choco' ),'  ','' ); ?></div>

			</div>
			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div>
			<div class="cl">&nbsp;</div>
		</div>
	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li <?php comment_class( 'comment' ); ?>>
		<div class="comment-body" id="comment-<?php comment_ID(); ?>">
			<?php _e( 'Pingback:', 'choco' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'choco' ), ' ' ); ?></div>
	<?php
			break;
	endswitch;

}

// Add the default Choco gravatar
function choco_avatar ( $avatar_defaults ) {
		$myavatar = get_stylesheet_directory_uri() . '/images/avatar.gif';
		
		$avatar_defaults[$myavatar] = "Choco";
		
		return $avatar_defaults;
}
add_filter( 'avatar_defaults', 'choco_avatar' );

// Load up the theme options
require( dirname( __FILE__ ) . '/inc/theme-options.php' );

/**
 *  Get choco options
 */
function choco_get_options() {
	$defaults = array(
		'color_scheme' => 'default',
	);
	$options = get_option( 'choco_theme_options', $defaults );
	return $options;
}

/**
 * Register our color schemes and add them to the style queue
 */
function choco_color_registrar() {
	$options = choco_get_options();
	$color_scheme = $options['color_scheme'];

	if ( ! empty( $color_scheme ) ) {
		wp_register_style( $color_scheme, get_template_directory_uri() . '/colors/' . $color_scheme . '/style.css', null, null );
		wp_register_style( $color_scheme . '_rtl' , get_template_directory_uri() . '/colors/' . $color_scheme . '/rtl.css', null, null );
		wp_enqueue_style( $color_scheme );

		if ( 'rtl' == get_option( 'text_direction' ) ) {
			wp_enqueue_style( $color_scheme . '_rtl' );
		}

	}
}
if ( ! is_admin() )
	add_action( 'wp_print_styles', 'choco_color_registrar' );