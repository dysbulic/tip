<?php 
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p><?php _e('Enter your password to view comments.','benevolence'); ?></p>
<?php
	return;
}

function benevolence_callback($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);
?>
<div <?php comment_class(empty( $args['has_children'] ) ? 'commentBox' : 'commentBox parent') ?> id="comment-<?php comment_ID() ?>">
	<div id="div-comment-<?php comment_ID() ?>">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	<?php comment_text() ?>
	<span class="comment-author vcard">
	<i><?php comment_type(__('Comment','benevolence'), __('Trackback','benevolence'), __('Pingback','benevolence')); ?> <?php _e('by','benevolence'); ?> <cite class="fn"><?php comment_author_link() ?></cite>
	</span>
	<span class="comment-meta commentmetadata">
	<?php comment_date(); ?> @ <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time() ?></a> <?php edit_comment_link(__('Edit This','benevolence'), ' |'); ?></i>
	</span>

	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	</div>
	<br />
<?php 
}

if ( comments_open() || have_comments() ) : ?>
<b><?php comments_number(__('Leave a Comment','benevolence'), __('1 Comment','benevolence'), __('% Comments','benevolence')); ?> <?php if ( comments_open() ) _e('so far','benevolence'); ?></b>
<?php else : // If there are no comments yet ?>
<?php endif; ?>
<?php if ( comments_open() ) : ?><br /> 
<a href="#postcomment" title="<?php _e('Leave a comment','benevolence'); ?>"><?php _e('Leave a comment','benevolence'); ?></a>
<?php endif; ?>
<br /><br />
<a name="comments"></a>
<?php if ( $comments ) : ?>

<div class="commentlist">
<?php wp_list_comments(array('callback'=>'benevolence_callback', 'style'=>'div')); ?>
</div>
<div class="navigation">
	<div class="alignleft"><?php previous_comments_link() ?></div>
	<div class="alignright"><?php next_comments_link() ?></div>
</div>
<br />

<?php else : // If there are no comments yet ?>

<?php endif; ?>

<div class="right"><?php post_comments_feed_link( __( '<abbr title="Really Simple Syndication">RSS</abbr> feed for comments on this post.', 'benevolence' ) ); ?>
<?php if ( pings_open() ) : ?>
	<a href="<?php trackback_url() ?>" rel="trackback"><?php _e('TrackBack <abbr title="Uniform Resource Identifier">URI</abbr>','benevolence'); ?></a>
<?php endif; ?>
</div>

<br /><br />

<a name="postcomment"></a>

<?php if ( comments_open() ) : ?>

	<?php comment_form(); ?>

<?php else : // Comments are closed ?>

	<?php if ( ! is_page() ) : ?>
		<p><?php _e( 'Comments are closed.', 'benevolence' ); ?></p>
	<?php endif; ?>

<?php endif; ?>