<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die (__('Please do not load this page directly. Thanks!', 'sapphire'));
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e("This post is password protected. Enter the password to view comments.", 'sapphire'); ?></p>
<?php
	return;
}

if (have_comments()) : ?>
	<h3 id="comments"><?php comments_number(__('Be the First to Comment', 'sapphire'), __('One Comment', 'sapphire'), __('% Comments', 'sapphire') );?> <?php _e('on', 'sapphire'); ?> &#8220;<?php the_title(); ?>&#8221;</h3> 

	<ol class="commentlist">
	<?php wp_list_comments(array('callback'=>'sapphire_comment')); ?>
	</ol>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	<br />
	
	<?php if (!comments_open()) : ?> 
		<p class="nocomments"><?php _e( 'Comments are closed.', 'sapphire' ); ?></p>
	<?php endif; ?>
<?php endif; ?>

<?php comment_form(); ?>