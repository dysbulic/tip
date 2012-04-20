<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->
<?php function springloaded_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
			<div class="comment-gravatar">
				<?php echo get_avatar( $comment, 30 ); ?>
			</div>
			<div class="comment-body">
				<div class="comment-head">
					<p><?php printf(__('Posted by %1$s on <a href="#comment-%2$s">%3$s at %4$s</a>'), get_comment_author_link(), get_comment_ID(), get_comment_date(), get_comment_time()); ?><?php edit_comment_link('edit','&nbsp;&nbsp;',''); ?></p>
					<?php if ($comment->comment_approved == '0') : ?>
						<p><em><?php _e('Your comment is awaiting moderation.'); ?></em></p>
					<?php endif; ?>
				</div>
				<div class="comment-text">
					<?php comment_text() ?>
					<p><?php echo comment_reply_link(array('depth' => $depth, 'max_depth' => $args['max_depth'], 'before' => '')) ?></p>
				</div>
			</div>
<?php } ?>
<div class="comments-show">


<?php if ( have_comments() ) { ?>	
	<h3 id="comments"><?php comments_number(__('No responses yet to this post.'), __('One response to this post.'), __('% responses to this post.') );?></h3>

	<ol class="commentlist">

	<?php wp_list_comments(array('callback' => 'springloaded_comment')); ?>

	</ol>

	<div class="prev-next">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>

<?php } ?>


	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php elseif( have_comments() && !comments_open() ) : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments"><?php _e('Comments are closed.'); ?></p>

	<?php endif; ?>


<?php comment_form(); ?>

</div>
