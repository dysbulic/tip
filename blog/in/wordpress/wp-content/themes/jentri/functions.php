<?php
/**
 * @package WordPress
 * @subpackage Jentri
 */

$themecolors = array(
	'bg' => 'fefcfa',
	'text' => '77756b',
	'link' => '8e7d6c',
	'border' => 'e6d5c4',
	'url' => '8c4a4a',
);

$content_width = 420;

add_filter( 'body_class', '__return_empty_array', 1 );

if ( function_exists('register_sidebars') )
	register_sidebars(1);

add_theme_support( 'automatic-feed-links' );

// Custom background
add_custom_background();

function jentri_custom_background() {
	if ( '' != get_background_color() || '' != get_background_image() ) { ?>
		<style type="text/css">
			#wrap { background-image: none; }
		<?php if ( '' != get_background_color() && '' == get_background_image() ) { ?>
			body { background-image: none; }
		<?php } ?>
		</style>
	<?php }
}
add_action( 'wp_head', 'jentri_custom_background' );

function jentri_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>

<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent'); ?> id="comment-<?php comment_ID(); ?>">
	<div id="div-comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
      	        	<cite class="fn"><?php comment_author_link(); ?></cite> <?php _e( 'Says:', 'jentri' ); ?>
      	        </div>
		<?php if ($comment->comment_approved == '0') : ?>
		<em><?php _e( 'This post is password protected. Enter the password to view comments.', 'jentri' ); ?></em>
		<?php endif; ?>
		<br />

		<small class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>" title=""><?php comment_date(); ?> at <?php comment_time(); ?></a> <?php edit_comment_link('e','',''); ?></small>
		<?php comment_text(); ?>
		<div class="reply">
			<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
		</div>
	</div>
<?php
}