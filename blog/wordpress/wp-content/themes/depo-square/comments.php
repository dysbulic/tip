<?php // Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'depo-squared'); ?></p>
	<?php
		return;
	}

	/* This variable is for alternating comment background */
	$oddcomment = 'alt';
?>

<!-- You can start editing here. -->
<?php
function depo_squared_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
?>
			<li <?php comment_class() ?> id="comment-<?php comment_ID() ?>">
			<?php echo get_avatar( $comment, 32 ); ?>
			<div class="commentmetadata">
			<?php printf(__('<cite>%s</cite>', 'depo-squared'), get_comment_author_link()); ?>
			<?php if ($comment->comment_approved == '0') : ?>
			<em><?php _e('Your comment is awaiting moderation.', 'depo-squared'); ?></em>
			<?php endif; ?>

			<small><a href="#comment-<?php comment_ID() ?>" title=""><?php printf(__('%1$s at %2$s', 'depo-squared'), get_comment_date(), get_comment_time()); ?></a><?php echo comment_reply_link(array('depth' => $depth, 'max_depth' => $args['max_depth'], 'before' => ' | ')) ?> <?php edit_comment_link(__('edit', 'depo-squared'),'&nbsp;&nbsp;',''); ?></small>
			</div>
			<div class="comment-text">
				<?php comment_text() ?>
			</div>
<?php } ?>
<?php if ( have_comments() ) : ?>
	<h3 id="comments"><?php comments_number(__('No Responses', 'depo-squared'), __('One Response', 'depo-squared'), __('% Responses', 'depo-squared'));?> <?php printf(__('to &#8220;%s&#8221;', 'depo-squared'), the_title('', '', false)); ?></h3>

	<ol class="commentlist">
		<?php wp_list_comments(array('callback' => 'depo_squared_comment')); ?>	
	</ol>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
<?php endif; ?>

<?php if ( have_comments() && !comments_open() ) { ?>
<p class="nocomments"><?php _e('Comments are closed.', 'depo-squared'); ?></p>
<?php } ?> 

<?php if ( comments_open() ) : ?>

<?php comment_form(); ?>

<?php endif; // if you delete this the sky will fall on your head ?>
