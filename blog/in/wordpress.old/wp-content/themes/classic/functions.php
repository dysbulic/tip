<?php
/**
 * @package WordPress
 * @subpackage Classic
 */
if ( ! isset( $themecolors ) ) {
	$themecolors = array(
		'bg' => 'ffffff',
		'text' => '000000',
		'link' => '667755',
		'border' => 'CCCCCC',
		'url' => '99AA88',
	);
}

add_filter( 'body_class', '__return_empty_array', 1 );

if ( function_exists('register_sidebars') )
	register_sidebars(1);

add_theme_support( 'automatic-feed-links' );

add_custom_background();

function classic_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li id="comment-<?php comment_ID() ?>" <?php comment_class(); ?>>
	<div id="div-comment-<?php comment_ID() ?>" class="vcard">
	<?php echo get_avatar( $comment, 32 ); ?>
	<?php comment_text() ?>
	<p><cite><?php comment_type(__('Comment','classic'), __('Trackback','classic'), __('Pingback','classic')); ?> <?php _e('by','classic'); ?> <span class="fn"><?php comment_author_link() ?></span> &#8212; <?php comment_date() ?> @ <a href="#comment-<?php comment_ID() ?>"><?php comment_time() ?></a></cite> <?php edit_comment_link(__('Edit This','classic'), ' | '); ?></p>
	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
<?php
}