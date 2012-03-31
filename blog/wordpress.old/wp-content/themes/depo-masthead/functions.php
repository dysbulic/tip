<?php
/**
 * @package WordPress
 * @subpackage DePo Masthead
 */

/**
 * Make theme available for translation
 * Translations can be filed in the /languages/ directory
 */
load_theme_textdomain( 'depo-masthead', TEMPLATEPATH . '/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable( $locale_file ) )
	require_once( $locale_file );

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 604;

/**
 * Set $themecolors array.
 */
$themecolors = array(
	'bg' => 'FFFFFF',
	'text' => '000000',
	'link' => '990000',
	'border' => 'CCCCCC',
	'url' => 'CC0000',
);

/**
 * Feeds.
 */
add_theme_support( 'automatic-feed-links' );

/**
 * Add Custom Background.
 */
add_custom_background();

function depo_custom_background() {
	if ( '' != get_background_color() || '' != get_background_image() ) { ?>
		<style type="text/css">
			#sidebar { background-image: none; }
			#sidebar ul { border-color: #<?php echo get_background_color(); ?>; }
			#sidebar .closer { background: none; }
		<?php if ( '' != get_background_color() && '' == get_background_image() ) { ?>
			body { background-image: none; }
		<?php } ?>
		</style>
	<?php }
}

add_action( 'wp_head', 'depo_custom_background' );

/**
 * Register Nav Menu.
 */
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'depo-masthead' )
) );

/**
 * Custom Excerpt.
 */
function depomasthead_continue_reading_excerpt() {
		$text = get_the_excerpt();
		$text = apply_filters( 'the_content', $text);
		$text = str_replace( ']]>', ']]>', $text);
		$text = strip_tags($text, '<p>' );
		$excerpt_length = 50;
		$words = explode( ' ', $text, $excerpt_length + 1);
		if (count($words) > $excerpt_length) {
			array_pop($words);
			array_push($words, ' ... <a href="'. get_permalink() . '">'.__( 'Continue reading &raquo;' ).'</a>' );
			$text = implode( ' ', $words);
		}
	echo $text;
}

/**
 * Register Widget Areas and Custom Widgets.
 */
function depo_widgets_init() {
		register_sidebar( array(
			'name' => 'Right',
				'before_widget' => '<li id="%1$s" class="widget %2$s">',
				'after_widget' => '</li>',
				'before_title' => '<h3 class="widgettitle">',
				'after_title' => '</h3>',
		) );
		register_sidebar( array(
			'name' => 'Middle',
				'before_widget' => '<li id="%1$s" class="widget %2$s">',
				'after_widget' => '</li>',
				'before_title' => '<h3 class="widgettitle">',
				'after_title' => '</h3>',
		) );
		register_sidebar( array(
			'name' => 'Left',
				'before_widget' => '<li id="%1$s" class="widget %2$s">',
				'after_widget' => '</li>',
				'before_title' => '<h3 class="widgettitle">',
				'after_title' => '</h3>',
		) );

		wp_register_sidebar_widget( 'search' , __( 'DePo Search', 'depo-masthead' ), 'depo_search_widget' );
		wp_register_sidebar_widget( 'depo-about', __( 'DePo About', 'depo-masthead' ), 'depo_about_widget' );
		wp_register_sidebar_widget( 'depo-archives-categories', __( 'DePo Archives & Categories', 'depo-masthead' ), 'depo_archives_widget' );
		wp_register_sidebar_widget( 'rss-link', __( 'DePo RSS Link', 'depo-masthead' ), 'depo_rss_widget' );
}
add_action( 'widgets_init', 'depo_widgets_init' );

/**
 * Include Custom Widgets.
 */
require_once( get_template_directory() . '/lib/widgets.php' );

/**
 * Admin Theme Page.
 */
add_action( 'admin_menu', 'depo_add_theme_page' );

function depo_add_theme_page() {
	if ( isset( $_GET['page'] ) && $_GET['page'] == basename( __FILE__) ) {
		if ( isset( $_POST['action'] ) && 'save' == $_POST['action'] ) {
			if(isset($_POST['author-name'])) {
				check_admin_referer( 'depo-name' );
				if ( '' == $_POST['author-name'] ) {
					delete_option( 'depo-author-name' );
				} else {
					update_option( 'depo-author-name', esc_attr( $_POST['author-name'] ) );
				}
				wp_redirect( "themes.php?page=functions.php&saved=true" );
				die;
			}
		}
	}
	add_theme_page( __( 'Theme Options', 'depo-masthead' ), __( 'Theme Options', 'depo-masthead' ), 'edit_theme_options', basename( __FILE__), 'depo_theme_page' );
}

function depo_theme_page() {
	if ( isset( $_REQUEST['saved'] ) ) echo '<div id="message" class="updated fade"><p><strong>'.__( 'Options saved.', 'depo-masthead' ).'</strong></p></div>';
	?>
	<div class='wrap'>
	<form method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
		<?php wp_nonce_field( 'depo-name' ); ?>
		<p><label for="author-name"><?php _e( 'Author Box Text:', 'depo-masthead' ); ?></label> <input type="text" name="author-name" value="<?php echo get_option( 'depo-author-name' ); ?>" id="author-name" /> <small><?php _e( 'Leaving this field blank will insert the blog author\'s name.', 'depo-masthead' ); ?></small></p>
		<p><input type="hidden" name="action" value="save" /> <input class="button-primary" type="submit" name="submit" value="<?php esc_attr_e( 'Save Option',  'depo-masthead' ); ?>" id="submit" /></p>

	</form>
	</div>
<?php }

/**
 * Fallback for primary navigation.
 */
function depomasthead_page_menu() { ?>
	<ul class="menu">
		<?php wp_list_pages( 'sort_column=menu_order&depth=1&title_li=' ); ?>
		<li><a href="<?php echo home_url(); ?>/<?php echo mysql2date( 'Y', get_lastpostdate( 'blog' ) ); ?>/"><?php _e( 'Archives', 'depo-masthead' ); ?></a></li>
		<li><a href="<?php bloginfo( 'rss2_url' ); ?>"><?php _e( 'RSS Feed', 'depo-masthead' ); ?></a></li>
	</ul>
<?php }

function depo_masthead_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	extract( $args, EXTR_SKIP );
?>
	<li <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">
	<div id="div-comment-<?php comment_ID(); ?>">
		<div class="comments_text">
		<?php comment_text(); ?>
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em><?php _e( 'Your comment is awaiting moderation.', 'depo-masthead' ); ?></em>
		<?php endif; ?>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div>
		</div>

		<div class="comment-meta commentmetadata">
			<div class="gravatar"><?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?></div>
			<div class="cite comment-author vcard">
			<cite class="fn"><?php comment_author_link(); ?></cite>
			<small><a href="#comment-<?php comment_ID(); ?>" title=""><strong><?php comment_date( 'j F Y' ); ?></strong> at <strong><?php comment_time( 'ga' ); ?></strong></a> <?php edit_comment_link( 'edit', '&nbsp;&nbsp;', '' ); ?></small>
			</div>
		</div>
	</div>
<?php
}