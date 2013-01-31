<?php
/**
 * @package WordPress
 * @subpackage Fadtastic
 */

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '000000',
	'link' => '80ae14',
	'border' => 'cccccc',
	'url' => '59a3c1',
);

$content_width = 544;

add_filter( 'body_class', '__return_empty_array', 1 );

function widget_fadtastic_links() {
	wp_list_bookmarks(array('title_before'=>'<h3>', 'title_after'=>'</h3>', 'show_images'=>true));
}

function fadtastic_widget_init() {
	register_sidebar(array('before_title' => "<h3 class='widgettitle'>", 'after_title' => "</h3>", 'name' => 'Sidebar 1', 'id' => 'main-sidebar'));
	register_sidebar(array('before_title' => "<h3 class='widgettitle'>", 'after_title' => "</h3>", 'name' => 'Sidebar 2', 'id' => 'bottom-bar'));
	unregister_widget('WP_Widget_Links');
	wp_register_sidebar_widget('links', __('Links', 'sandbox'), 'widget_fadtastic_links');
}
add_action('widgets_init', 'fadtastic_widget_init');

add_theme_support( 'automatic-feed-links' );

// Custom background
add_custom_background();

function fadtastic_custom_background() {
	if ( '' != get_background_color() || '' != get_background_image() ) { ?>
		<style type="text/css">
			#wrapper { border: none; }
		<?php if ( '' != get_background_color() && '' == get_background_image() ) { ?>
			body { background-image: none; }
		<?php } ?>
		</style>
	<?php }
}
add_action( 'wp_head', 'fadtastic_custom_background' );

// Navigation menu
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'fadtastic' )
) );

// Fallback for primary navigation
function fadtastic_page_menu() { ?>
	<ul id="navlist" class="menu">
		<?php wp_list_pages( 'title_li=&depth=1' ); ?>
	</ul>
<?php }

function fadtastic_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<div <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="div-comment-<?php comment_ID(); ?>">
	<div class="comment_wrapper" id="comment-<?php comment_ID(); ?>">
		<div class="comment_content">
			<?php if ($comment->comment_approved == '0') : ?>
			<em><?php _e( 'Your comment is awaiting moderation.', 'fadtastic' ); ?></em>
			<?php else : ?>
			<?php comment_text(); ?>
			<?php endif; ?>
		</div>
	</div>
	<div class="comment_details comment-author vcard">
		<p class="comment-meta commentmetadata comment_meta"><strong class="fn"><?php comment_author_link(); ?></strong><br /><a href="#comment-<?php comment_ID(); ?>" title=""><?php comment_date(); ?></a><br /></p>
		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>		
	</div>
	<div class="clear"></div>
	<div class="reply">
		<?php comment_reply_link( array_merge( $args, array( 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
	</div>
	<div class="clear comment_bottom"></div>
<?php
}