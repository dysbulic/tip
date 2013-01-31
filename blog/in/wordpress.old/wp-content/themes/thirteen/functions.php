<?php
/**
 * @package WordPress
 * @subpackage Thirteen
 */

$themecolors = array(
	'bg' => 'ffffe3',
	'border' => 'bbb26c',
	'text' => '000000',
	'link' => 'c86c00',
	'url' => '4e5706',
);

$content_width = 477;

add_filter( 'body_class', '__return_empty_array', 1 );

if ( function_exists('register_sidebars') )
	register_sidebars(1);

add_theme_support( 'automatic-feed-links' );

function thirteen_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
	<div class="comment-author vcard">
		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		<h4 class="commentauthor"><span class="fn"><?php comment_author_link() ?></span> <?php _e('said,'); ?></h4>
	</div>
		<p class="commentdate">
			<?php comment_date() ?> <?php _e('at'); ?> <a href="#comment-<?php comment_ID() ?>" title="<?php esc_attr_e( 'Permanent link to this comment' ); ?>"><?php comment_time() ?></a> 
			<?php edit_comment_link(__('Edit'),' &#183; ',''); ?>
		</p>
		<?php comment_text(); ?>

	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
<?php
}