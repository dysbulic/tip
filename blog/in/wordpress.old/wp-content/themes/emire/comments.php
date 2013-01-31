<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e("This post is password protected. Enter the password to view comments.", 'emire'); ?></p>
<?php
	return;
}

if (have_comments()) : ?>
	<h3 id="comments"><?php printf(__('%1$s to &#8220;%2$s&#8221;','emire'), comments_number(__('No Responses Yet','emire'), __('One Response','emire'), __('% Responses','emire')), get_the_title()) ?></h3>

	<ol class="commentlist">
	<?php wp_list_comments(array(
		'callback'=>'emire_comment',
	)); ?>
	</ol>
	
	<div class="comments-navigation">
		<div class="alignleft"><?php previous_comments_link(); ?></div>
		<div class="alignright"><?php next_comments_link(); ?></div>
	</div>
	<br />

	<?php if (!comments_open()) : ?> 
		<p class="nocomments"><?php _e('Comments are closed.','emire'); ?></p>
	<?php endif; ?>
<?php endif; ?>


<?php if (comments_open()) : ?>

<?php comment_form(); ?>

<?php endif; // if you delete this the sky will fall on your head ?>