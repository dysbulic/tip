<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e("This post is password protected. Enter the password to view comments."); ?></p>
<?php
	return;
}

if (have_comments()) : ?>
	<h3 id="comments"><?php comments_number(__('No Responses Yet', 'connections'), __('One Response', 'connections'), __('% Responses', 'connections'));?> <?php printf(__('to &#8220;%s&#8221;', 'connections'), the_title('', '', false)); ?></h3>

	<ol class="commentlist">
	<?php wp_list_comments(array(
		'callback'=>'connections_comment',
		'avatar_size'=>48,
		'style'=>'ol',
	)); ?>
	</ol>
	
	<div class="navigation">
	<div class="alignleft"><?php previous_comments_link() ?></div>
	<div class="alignright"><?php next_comments_link() ?></div>
	</div>

	<?php if (!comments_open()) : ?>
		<p class="nocomments"><?php _e('Comments are closed.') ?></p>
	<?php endif; ?>
<?php endif; ?>

<?php if ( comments_open() ) : ?>

	<?php comment_form(); ?>

<?php endif; // if you delete this the sky will fall on your head ?>