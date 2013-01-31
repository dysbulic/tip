<?php
/**
 * @package WordPress
 * @subpackage Green Marinee
 */

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '3f3f3f',
	'link' => '7da721',
	'border' => 'fcfef4',
	'url' => 'ace149',
);

$content_width = 475;

register_sidebar( array(
	'name'          => __('Sidebar'),
	'id'            => 'sidebar',
	'before_widget' => '<span class="widget">',
	'after_widget'  => '</span><div class="line"></div>',
	'before_title'  => '<h3>',
	'after_title'   => '</h3>' )
);

add_theme_support( 'automatic-feed-links' );

add_custom_background();

function greenmarinee_custom_background() {
	if ( '' != get_background_color() || '' != get_background_image() ) { ?>
		<style type="text/css">
			.topline, .container_right, .container_left, #content_bg, #footer { background: none; }
			#container { background: #fff; }
		<?php if ( '' != get_background_color() && '' == get_background_image() ) { ?>
			body { background-image: none; }
		<?php } ?>
		</style>
	<?php }
}
add_action( 'wp_head', 'greenmarinee_custom_background' );

function green_marinee_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
	<div class="comment-author vcard">
		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		<div class="comment_author">
			<span class="fn"><?php comment_author_link() ?></span> <?php _e('said','greenmarinee'); ?>,
		</div>
	</div>
	
	<?php if ($comment->comment_approved == '0') : ?>
	<em><?php _e('Your comment is awaiting moderation.','greenmarinee'); ?></em>
	<?php endif; ?>
	<br />

	<p class="metadate comment-meta commentmetadata"><?php _e('on','greenmarinee'); ?> <?php comment_date() ?> <?php _e('on','greenmarinee'); ?> <?php comment_time() ?></p>

	<?php comment_text() ?>

	<p class="replylink">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</p>
	</div>
<?php
}
