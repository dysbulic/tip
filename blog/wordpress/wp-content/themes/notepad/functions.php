<?php
/**
 * @package WordPress
 * @subpackage Notepad
 */

// Theme colors and content width
$themecolors = array(
	'bg' => 'fafad3',
	'border' => '644527',
	'text' => '6F5E4E',
	'link' => '6F5E4E',
	'url' => '6F5E4E'
);
$content_width = 500;

// Automatic feed links
add_theme_support( 'automatic-feed-links' );

// Register support for nav menus
register_nav_menus( array(
	'primary' => __( 'Primary Menu', 'notepad-theme' ),
) );

// Filter the default page menu
function notepad_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'notepad_page_menu_args' );

// Add ID and CLASS attributes to the first <ul> occurence in wp_page_menu
function add_menuclass($ulclass) {
return preg_replace('/<ul>/', '<ul class="menu">', $ulclass, 1);
}
add_filter('wp_page_menu','add_menuclass');

//sidebar widgets
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => __( 'Sidebar', 'notepad-theme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
}

// Make [...] link to posts (from search results page)
function new_excerpt_more($more) {
	global $post;
	return ' <a href="' . get_permalink() . '" class="excerpt-more-link">' . '[&hellip;]' . '</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

function resize_youtube( $content ) {
	if ( strpos( $content, "width='425' height='350'></embed>" ) != -1 )
	return str_replace( "width='425' height='350'></embed>", "width='240' height='197'></embed>", $content );
}
add_filter( 'the_content', 'resize_youtube', 999 );

// Grab our theme options
require_once ( get_template_directory() . '/theme-options.php' );

//custom comment template
function mytheme_comment( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
		<p class="comment-author">
			<?php echo get_avatar( $comment, $size='48' ); ?>
			<?php printf(__( '<cite>%s</cite>' ), get_comment_author_link() ) ?><br />
			<small>
				<strong><?php comment_date( 'M d, Y' ); ?></strong> @ <?php comment_time( 'H:i:s' ); ?>
				<?php edit_comment_link( 'Edit',' [',']' ) ?>
			</small>
		</p>

		<?php if ( '' == $comment->comment_type ) : ?>

		<div class="commententry" id="comment-<?php comment_ID() ?>">
			<?php if ( $comment->comment_approved == '0' ) : ?>
			<p>
				<em>
					<?php _e( 'Your comment is awaiting moderation.' ) ?>
				</em>
			</p>
			<?php endif; ?>

			<?php comment_text() ?>
		</div>


		<p class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'add_below' => 'commententry', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ) ?>
		</p>

		<?php endif; ?>

<?php
}

// Custom background
add_custom_background();

function notepad_custom_background() {
	if ( '' != get_background_color() || '' != get_background_image() ) { ?>
		<style type="text/css">
			html { background-image: none; }
		</style>
	<?php }
}
add_action( 'wp_head', 'notepad_custom_background' );