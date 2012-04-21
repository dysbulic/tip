<?php

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '000000',
	'link' => '637677'
);

$content_width = 490;

if ( function_exists('register_sidebars') )
	register_sidebars(1);

add_theme_support( 'automatic-feed-links' );

// Custom background
add_custom_background();

function fresh_bananas_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
	<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
	<span class="comment-author vcard">
		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		<p><strong class="fn"><?php printf(__('%s replied:'), get_comment_author_link()); ?></strong>
	</span>
		<?php if ($comment->comment_approved == '0') : ?>
		<br />
		<em><?php _e('Your comment is awaiting moderation.'); ?></em>
		<?php endif; ?>
		</p>

		<?php comment_text() ?>

		<p class="comment-meta commentmetadata"><?php comment_date(get_option('date_format')) ?> at <?php comment_time() ?>. <a href="#comment-<?php comment_ID() ?>" title="<?php esc_attr_e( 'Permalink for this comment' ); ?>"><?php _e('Permalink'); ?></a>. <?php edit_comment_link(__('Edit'),'',''); ?></p>

	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
<?php
}