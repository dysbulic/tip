<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.','girl'); ?></p>
<?php
	return;
}

if (have_comments()) : ?>

<div class="commentlist">
	<?php wp_list_comments(array('callback'=>'girl_comment', 'style'=>'div')); ?>
</div>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
<?php endif; ?>

<div class="comments">
<?php if (comments_open()) : ?>

<?php comment_form(); ?>

<?php elseif ( have_comments() ) : ?>
	<p class="nocomments"><?php _e('Comments are closed.','girl'); ?></p>
<?php endif; // if you delete this the sky will fall on your head ?>

</div>
