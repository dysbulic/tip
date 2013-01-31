<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments',TEMPLATE_DOMAIN); ?><p>
<?php
	return;
}

if (have_comments()) : ?>
	<h3 id="comments"><?php comments_number(__('No responses yet',TEMPLATE_DOMAIN), __('One response',TEMPLATE_DOMAIN), __('% responses',TEMPLATE_DOMAIN));?></h3>

	<dl class="commentlist">
	<?php wp_list_comments(array('callback'=>'freshy_comment', 'end-callback'=>'freshy_end_comment', 'style'=>'div')); ?>
	</dl>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>

<?php endif; ?>

<?php comment_form(); ?>