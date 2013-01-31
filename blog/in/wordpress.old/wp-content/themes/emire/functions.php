<?php
/**
 * @package WordPress
 * @subpackage Emire
 */

$themecolors = array(
	'bg' => '3d3d3d',
	'text' => 'ffffff',
	'link' => 'bbbbbb',
	'border' => '3e3e3e',
	'url' => 'bde271',
);

$content_width = 418;

add_filter( 'body_class', '__return_empty_array', 1 );

if ( function_exists('register_sidebars') )
	register_sidebars(1);

add_theme_support( 'automatic-feed-links' );

// Custom background
add_custom_background();

function emire_custom_background() {
	if ( '' != get_background_color() || '' != get_background_image() ) { ?>
		<style type="text/css">
			#header { background: none; }
		<?php if ( '' != get_background_color() && '' == get_background_image() ) { ?>
			body { background-image: none; }
		<?php } ?>
		</style>
	<?php }
}
add_action( 'wp_head', 'emire_custom_background' );

// Navigation menu
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'emire' )
) );

// Fallback for primary navigation
function emire_page_menu() { ?>
	<ul>
		<li><a href="<?php echo home_url( '/' ); ?>"><?php _e( 'Home', 'emire' ); ?></a></li>
		<?php wp_list_pages( 'title_li=&depth=1' ); ?>
	</ul>
<?php }

function emire_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
	<div class="comment-author vcard">
		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		<cite class="fn"><?php comment_author_link() ?></cite> <?php _e('Says:','emire'); ?>
	</div>
	<?php if ($comment->comment_approved == '0') : ?>
	<em><?php _e('Your comment is awaiting moderation.','emire'); ?></em>
	<?php endif; ?>
	<br />

	<small class="comment-meta commentmetadata"><a href="#comment-<?php comment_ID() ?>" title=""><?php printf(__('%1$s at %2$s','emire'), get_comment_date(), get_comment_time()) ?></a> <?php edit_comment_link(__('e','emire'),'',''); ?></small>

	<?php comment_text() ?>
	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
<?php
}