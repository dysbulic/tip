<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p><?php _e( 'This post is password protected. Enter the password to view comments.', 'tarski' ); ?></p>
<?php
	return;
}

// Custom comment markup
function tarski_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
	<?php if ($comment->comment_approved == '0') : ?>
		<p><?php _e( 'Your comment is awaiting moderation.', 'tarski' ); ?></p>
	<?php endif; ?>
	
	<div <?php comment_class(empty( $args['has_children'] ) ? 'vcard' : 'vcard parent') ?> id="comment-<?php comment_ID() ?>">
		<div class="comment-metadata">
			<p class="comment-permalink"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>" title="<?php _e( 'Permalink to this comment', 'tarski' ); ?>"><?php comment_date() ?> <?php _e('at'); ?> <?php comment_time() ?></a></p>
			<p class="comment-author"><strong class="fn"><?php comment_author_link(); ?></strong></p>
			<?php edit_comment_link( __( 'edit', 'tarski' ), '<p class="comment-permalink">(', ')</p>' ); ?> 
		</div>
		
		<div class="comment-content">
			<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
			<?php comment_text(); ?>
		</div>
		<div class="reply">
				<?php comment_reply_link(array_merge( $args, array('add_below' => 'comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		</div>
	</div>
<?php
}

function tarski_comment_end($comment, $args, $depth) {
// null function to prevent extra ending /div
}

?>

<?php if (have_comments()) : ?>
<div id="comments" class="commentlist">
<?php if (comments_open()) : ?>

	<div id="comments-meta">
		<h2 class="comments-title"><?php comments_number( __( 'No comments yet', 'tarski' ), __( '1 comment', 'tarski' ), __( '% comments', 'tarski' ) ); ?></h2>
		<p class="comments-feed"><?php post_comments_feed_link( __( 'Comments feed for this article', 'tarski' ) ); ?></p>
	</div>

<?php else : // comments are closed ?>

	<div id="comments-meta">
		<h2 class="comments-title"><?php comments_number( __( 'No comments yet', 'tarski' ), __( '1 comment', 'tarski' ), __( '% comments', 'tarski' ) ); ?></h2>
	</div>

<?php endif; ?>

	<?php wp_list_comments( array( 'callback'=>'tarski_comment', 'end-callback'=>'tarski_comment_end','style'=>'li' ) ); ?>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>

	<br />

</div>
<?php else : // this is displayed if there are no comments so far ?>

<?php if ( comments_open() ) : ?>
<div id="comments">
	<div id="comments-meta">
		<h2 class="comments-title"><?php comments_number( __( 'No comments yet', 'tarski' ), __( '1 comment', 'tarski' ), __( '% comments', 'tarski' ) ); ?></h2>
		<p class="comments-feed"><a title="<?php _e( 'Subscribe to this article&#8217;s comments feed', 'tarski' ); ?>" href="<?php the_permalink() ?>feed/"><?php _e( 'Comments feed for this article', 'tarski' ); ?></a></p>		
	</div>
</div>
<?php else : // comments are closed ?>

<?php endif; ?>

<?php endif; ?>

<?php if (comments_open()) : ?>
	
<?php comment_form(); ?>

<?php endif; // if you delete this the sky will fall on your head / O RLY / YA RLY ?>
