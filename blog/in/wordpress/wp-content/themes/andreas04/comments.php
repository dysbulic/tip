<?php 
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p><?php _e('Enter your password to view comments.','andreas04'); ?></p>
<?php
	return;
}

if ( have_comments() ) : ?>
<h2 id="comments"><?php comments_number(__('Leave a Comment','andreas04'), __('1 Comment','andreas04'), __('% Comments','andreas04')); ?> 
<?php if ( comments_open() ) : ?>
	<a href="#postcomment" title="<?php esc_attr_e( 'Leave a comment', 'andreas04' ); ?>">&raquo;</a>
<?php endif; ?>
</h2>

<ol id="commentlist">
<?php wp_list_comments(array('callback'=>'andreas04_comment', 'avatar_size'=>16)); ?>
</ol>

<div class="navigation">
	<div class="alignleft"><?php previous_comments_link() ?></div>
	<div class="alignright"><?php next_comments_link() ?></div>
</div>
<br />

<?php else: ?>

<?php if ( comments_open() ) : // If there are no comments yet ?>
	<p><?php _e( 'No comments yet.', 'andreas04' ); ?></p>
<?php endif; ?>

<?php endif; ?>

<?php if ( comments_open() ) : ?>
	<?php comment_form(); ?>
<?php else: ?>
	<?php if ( ! is_page() ) : ?>
	<p><?php _e( 'Sorry, the comment form is closed at this time.', 'andreas04' ); ?></p>
	<?php endif; ?>
<?php endif; ?>
