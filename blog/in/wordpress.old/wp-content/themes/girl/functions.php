<?php
/**
 * @package WordPress
 * @subpackage Girl
 */

$themecolors = array(
	'bg' => '41423d',
	'text' => '9cb895',
	'link' => 'c3df95',
	'border' => '9cb895',
	'url' => 'c3df95',
	'_bg_image_color' => 'd4e8b5',
);

add_filter( 'body_class', '__return_empty_array', 1 );

if ( function_exists('register_sidebars') )
	register_sidebars(1);

add_theme_support( 'automatic-feed-links' );

// Custom background
add_custom_background();

function girl_custom_background() {
	if ( '' != get_background_color() && '' == get_background_image() ) { ?>
	<style type="text/css">
		body { background-image: none; }
	</style>
	<?php }
}
add_action( 'wp_head', 'girl_custom_background' );

function girl_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<div <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
<div class="comment-author vcard">
<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
<div class="heading"><span class="fn"><?php comment_author_link() ?></span> <?php _e('says:','girl'); ?></div>
</div>
<div class="entry">
<?php if ($comment->comment_approved == '0') : ?>
<b><?php _e('Your comment is awaiting moderation.','girl'); ?></em></b><br />
<?php endif; ?>
<?php comment_text() ?>
<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => 'comment-footer', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
</div>
</div>
<div class="footer comment-meta commentmetadata" id="comment-footer-<?php comment_ID() ?>"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>" title=""><?php comment_date(get_option('date_format')) ?> <?php _e('at','girl'); ?> <?php comment_time() ?></a> <?php edit_comment_link(__('e','girl'),'',''); ?></div>
<br />
<br />
<?php
}