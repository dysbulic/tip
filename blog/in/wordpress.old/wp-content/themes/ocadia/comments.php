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

	<h2 id="comments">
		<?php comments_number(__('Comments'), __('1 Comment'), __('% Comments')); ?>
		<?php if ( comments_open() ) : ?>
			<a href="#postcomment" title="<?php esc_attr_e( 'Jump to the comments form' ); ?>">&raquo;</a>
		<?php endif; ?>
	</h2> 

	<ol id="commentlist">
	<?php wp_list_comments(array('callback'=>'ocadia_comment','avatar_size'=>48)); ?>
	</ol>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	<br />
	<?php if (!comments_open()) : ?> 
		<p><?php _e('Comments are closed.'); ?></p>
	<?php endif; ?>
<?php endif; ?>

<?php if (comments_open()) : ?>

<?php comment_form(); ?>

<?php endif; // if you delete this the sky will fall on your head ?>