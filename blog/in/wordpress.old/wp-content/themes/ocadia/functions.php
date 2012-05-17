<?php
/**
 * @package WordPress
 * @subpackage Ocadia
 */

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '333333',
	'link' => '59708c',
	'border' => 'cdd3d3',
	'url' => '8ca0b4',
);

$content_width = 470;

add_filter( 'body_class', '__return_empty_array', 1 );

function ocadia_widgets_init() {
	register_sidebars(1);
}
add_action('widgets_init', 'ocadia_widgets_init');

add_theme_support( 'automatic-feed-links' );

function ocadia_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
	<div class="comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	<p class="commentauthor"><span class="fn"><?php comment_author_link() ?></span> <?php _e('said'); ?>,</p>
	</div>
	<p class="comment-meta commentmetadata commentmeta"><?php comment_date() ?> <?php _e('at'); ?> <a href="#comment-<?php comment_ID() ?>" title="<?php esc_attr_e( 'Permanent link to this comment' ); ?>"><?php comment_time() ?></a> <?php edit_comment_link(__("Edit"), ' &#183; ', ''); ?></p>
			
	<?php comment_text() ?>

	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
<?php
}