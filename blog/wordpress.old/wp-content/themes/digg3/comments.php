<?php if ('open' == $post->comment_status || $comments) : ?>

<div class="narrowcolumnwrapper"><div class="narrowcolumn">
	<div class="content">
	<div <?php post_class('post'); ?>>

<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e("This post is password protected. Enter the password to view comments.", 'digg3'); ?></p></div></div></div></div>
<?php
	return;
}

if (have_comments()) :
?>
	<h3 id="comments"><?php comments_number(__('No Responses Yet', 'digg3'), __('One Response', 'digg3'), __('% Responses', 'digg3') );?></h3>

	<ol class="commentlist">
	<?php wp_list_comments(array('callback'=>'digg3_comment')); ?>
	</ol>

	<div class="navigation">
	<div class="alignleft"><?php previous_comments_link() ?></div>
	<div class="alignright"><?php next_comments_link() ?></div>
	</div>

	<?php if (!comments_open()) : ?>
	<p class="nocomments"><?php _e('Comments are closed.', 'digg3'); ?></p>
	<?php endif; ?>

<?php endif; ?>


<?php if (comments_open()) : ?>

<?php comment_form(); ?>

<?php endif; // if you delete this the sky will fall on your head ?>
	</div>
	</div>
</div></div>

<?php endif;