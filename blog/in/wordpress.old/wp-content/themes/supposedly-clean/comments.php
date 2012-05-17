<?php
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p><?php _e('Enter your password to view comments.', 'supposedly-clean'); ?></p>
<?php
	return;
}
?>

<?php if ( comments_open() || have_comments() ) : ?>
	<h2 id="comments"><?php comments_number(__('Leave a Comment', 'supposedly-clean'), __('1 Comment', 'supposedly-clean'), __('% Comments', 'supposedly-clean')); ?> 
<?php endif; ?>
<?php if ( comments_open() ) : ?>
	<a href="#postcomment" title="<?php esc_attr_e( 'Leave a comment', 'supposedly-clean' ); ?>">&raquo;</a>
<?php endif; ?>
</h2>

<?php if ( have_comments() ) : ?>
<ol id="commentlist">
<?php wp_list_comments(array('callback'=>'supposedly_clean_comment', 'avatar_size'=>16)); ?>
</ol>

<div class="navigation">
	<div class="alignleft"><?php previous_comments_link() ?></div>
	<div class="alignright"><?php next_comments_link() ?></div>
</div>
<br />
<?php endif; ?>

<p>
</p>

<?php comment_form(); ?>
