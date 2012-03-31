<?php
/**
 * @package WordPress
 * @subpackage Twenty Eight
 */

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '444444',
	'link' => '3388cc',
	'border' => '289acf',
	'url' => 'cccccc',
);

if ( function_exists('register_sidebars') )
	register_sidebars(2);

$content_width = 425;

function twenty_eight_body_class( $classes, $class ) {
	return $class;
}

add_filter( 'body_class', 'twenty_eight_body_class', 1, 2 );

add_theme_support( 'automatic-feed-links' );

add_custom_background();

function twenty_eight_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent'); ?> id="comment-<?php comment_ID(); ?>">
	<div id="div-comment-<?php comment_ID(); ?>">
	<div class="comment-author vcard">
		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
		<span class="commentauthor fn" style="font-weight: normal;"><?php comment_author_link(); ?></span>
	</div>
	<?php edit_comment_link('<img src="'.get_bloginfo(template_directory).'/images/pencil.png" alt="Edit Link" />','<span class="commentseditlink">','</span>'); ?>
	<p class="metadata" style="color:#9c9c9c;font-size:12px;margin-top:2px;"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>" title="<?php { ?>Permalink to Comment<?php } ?>"><?php comment_date(); ?> at <?php comment_time(); ?></a></p>
	<div class="itemtext"><?php comment_text(); ?></div>
	<?php if ($comment->comment_approved == '0') : ?>
		<p class="alert"><strong><?php _e( 'Your comment is awaiting moderation.', 'twenty-eight' ); ?></strong></p>
	<?php endif; ?>
	<div class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
	</div>
	</div>
<?php
}

function twenty_eight_ping($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	global $count_pings;
	extract($args, EXTR_SKIP);
?>
<li <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent'); ?> id="comment-<?php comment_ID(); ?>">
	<a href="#comment-<?php comment_ID(); ?>" title="Permanent Link to this Comment" class="counter"><?php echo $count_pings; $count_pings++; ?></a>
	<span class="commentauthor"><?php comment_author_link(); ?></span>
	<small class="commentmetadata">
	<span class="pingtype"><?php comment_type(); ?></span> on <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>" title="<?php if (function_exists('time_since')) { $comment_datetime = strtotime($comment->comment_date); echo time_since($comment_datetime); ?> ago<?php } else { ?>Permalink to Comment<?php } ?>"><?php printf( __( '%1$s at %2$s', 'twenty-eight' ), get_comment_date( 'M jS, Y' ), get_comment_time() ); ?></a> <?php edit_comment_link('<img src="'.get_bloginfo(template_directory).'/images/pencil.png" alt="Edit Link" />','<span class="commentseditlink">','</span>'); ?>
	</small>
	<?php comment_text(); ?>
<?php
}