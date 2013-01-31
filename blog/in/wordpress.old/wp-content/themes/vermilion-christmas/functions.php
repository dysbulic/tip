<?php
/**
 * @package WordPress
 * @subpackage Vermilion Christmas
 */

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '343434',
	'link' => '990000',
	'border' => 'f2f6ee',
	'url' => 'ff0000',
);

$content_width = 540; // actually 543

add_filter( 'body_class', '__return_empty_array', 1 );

if ( function_exists( 'register_sidebar' ) )
    register_sidebar();

add_theme_support( 'automatic-feed-links' );

function vermilion_christmas_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	global $relax_comment_count;
	extract($args, EXTR_SKIP);
	$relax_comment_count++;
?>
<li <?php comment_class( empty( $args['has_children'] ) ? 'comment-body' : 'comment-body parent'); ?> id="comment-<?php comment_ID(); ?>">
	<div id="div-comment-<?php comment_ID(); ?>">
	<div class="vcard">
		<?php echo get_avatar( $comment, 48 ); ?>
		<div class="comment-count"><?php echo $relax_comment_count; ?></div>
		<div class="comment-author"><cite class="fn"><?php comment_author_link(); ?></cite> <?php _e( 'Says:', 'vermilionchristmas' ); ?></div>
	</div>
	<?php if ($comment->comment_approved == '0') : ?>
		<em><?php _e( 'Your comment is awaiting moderation.', 'vermilionchristmas' ); ?></em>
	<?php endif; ?>

	<?php comment_text(); ?>
	<div style="text-align:right; ">
	<small class="commentmetadata"><?php _e( 'Posted on', 'vermilionchristmas' ); ?> <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>" title=""><?php printf( __( '%1$s at %2$s', 'vermilionchristmas' ), get_comment_date(), get_comment_time() ); ?></a> <?php edit_comment_link( 'e','','' ); ?></small>
	</div>
	
	<div class="reply">
		<?php comment_reply_link( array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
	</div>
	</div>
<?php
}