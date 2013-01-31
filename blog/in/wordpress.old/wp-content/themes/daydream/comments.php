<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e("This post is password protected. Enter the password to view comments.", 'daydream'); ?></p>
<?php
	return;
}

if (have_comments()) : ?>

	<h4 id="comments"><?php comments_number(__('No Responses Yet','daydream'), __('One Response','daydream'), __('% Responses','daydream') );?></h4>

	<ol class="commentlist">
	<?php wp_list_comments(array(
		'callback'=>'daydream_comment',
		'avatar_size'=>48,
	)); ?>
	</ol>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	
	<?php if ('closed' == $post->comment_status) : ?> 
		<h4>Comments are closed.</h4>
	<?php endif; ?>

	<?php else : // this is displayed if there are no comments so far ?>
		<?php if ('open' == $post->comment_status) : ?>
			<?php if ( is_page() ) : ?>
			<h4><?php _e('There are no comments on this page.', 'daydream'); ?></h4>
			<?php else : ?>
			<h4><?php _e('There are no comments on this post.', 'daydream'); ?></h4>
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( comments_open() ) : ?>
		<?php comment_form(); ?>
	<?php endif; // if you delete this the sky will fall on your head ?>