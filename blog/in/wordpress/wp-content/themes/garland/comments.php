<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'garland'); ?></p>
<?php
	return;
}

if (have_comments()) : ?>
	<h3 id="comments"><?php comments_number(__('No Responses Yet', 'garland'), __('One Response', 'garland'), __('% Responses', 'garland'));?> <?php printf(__('to &#8220;%s&#8221;', 'garland'), the_title('', '', false)); ?></h3>

	<ol class="commentlist">
	<?php wp_list_comments(array('callback'=>'garland_comment')); ?>
	</ol>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	<br />
<?php endif; ?>

<?php if (comments_open()) : ?>

<?php comment_form(); ?>

<?php elseif ( have_comments() ) : ?>
	<p class="nocomments"><?php _e('Comments are closed.', 'garland'); ?></p>
<?php endif; // if you delete this the sky will fall on your head ?>