<?php // Do not delete these lines
if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die ('Please do not load this page directly. Thanks!');

if (post_password_required()) {
	?>
		
	<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'journalist') ;?><p>
		
	<?php
	return;
}
?>

<a name="comments" id="comments"></a>

<?php if (have_comments()) : ?>
<h3 class="reply"><?php comments_number(__('No Responses Yet', 'journalist'), __('One Response', 'journalist'), __('% Responses', 'journalist') );?></h3> 
<p class="comment_meta"><?php printf( __('Subscribe to comments with <a href="%s">RSS</a>.', 'journalist'), get_post_comments_feed_link() ); ?></p>

<ol class="commentlist">
<?php wp_list_comments(array('callback'=>'journalist_comment')); ?>
</ol>
<div class="navigation">
	<div class="alignleft"><?php previous_comments_link() ?></div>
	<div class="alignright"><?php next_comments_link() ?></div>
</div>
<br />

	<?php if (!comments_open()) : ?> 
	<p class="nocomments"><?php _e('Comments are closed.','journalist'); ?></p>
	<?php endif; ?>
<?php endif; ?>


<?php if (comments_open()) : ?>

<?php comment_form(); ?>

<?php endif; // if you delete this the sky will fall on your head ?>