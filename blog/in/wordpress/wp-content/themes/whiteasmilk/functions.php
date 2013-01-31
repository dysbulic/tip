<?php
/**
 * @package WordPress
 * @subpackage White as Milk
 */

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '333333',
	'link' => '0066cc',
	'border' => '444444',
	'url' => '114477',
);

$content_width = 380;

add_filter( 'body_class', '__return_empty_array', 1 );

if ( function_exists('register_sidebars') )
	register_sidebars(1);

add_theme_support( 'automatic-feed-links' );

add_custom_background();

function whiteasmilk_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent'); ?> id="comment-<?php comment_ID(); ?>">
	<div id="div-comment-<?php comment_ID(); ?>">
	<div class="comment-author vcard">
		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		<cite class="fn"><?php comment_author_link(); ?></cite> <?php _e('Says:','whiteasmilk'); ?>
	</div>
	<?php if ($comment->comment_approved == '0') : ?>
		<em><?php _e( 'Your comment is awaiting moderation.', 'whiteasmilk' ); ?></em>
	<?php endif; ?>
	<br />
	<small class="commentmetadata"><a href="#comment-<?php comment_ID(); ?>" title=""><?php comment_date(); ?> <?php _e('at','whiteasmilk'); ?> <?php comment_time(); ?></a> <?php edit_comment_link(__('e','whiteasmilk'),'',''); ?></small>

	<?php comment_text(); ?>
		
	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
	</div>
	</div>
<?php
}
