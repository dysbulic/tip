<?php
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'vigilance'); ?></p>
	<?php
		return;
	}
?>
<!-- You can start editing here. -->
<div id="comments">
<?php if ( have_comments() ) : ?>
	<div class="comment-number">
		<span><?php comments_number(__('Leave a Comment', 'vigilance'), __('One Comment', 'vigilance'), __('% Comments', 'vigilance')); ?></span>
	<?php if (comments_open()) : ?>
		<a id="leavecomment" href="#respond" title="<?php _e('Leave One', 'vigilance'); ?>"><?php _e('leave one &rarr;', 'vigilance'); ?></a>
	<?php endif; ?>
	</div><!--end comment-number-->
	<?php if ( ! empty($comments_by_type['comment']) ) : ?>
		<ol class="commentlist">
			<?php wp_list_comments('type=comment&callback=custom_comment'); ?>
		</ol>
	<?php endif; ?>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link(__('&laquo; Older Comments', 'vigilance')); ?></div>
		<div class="alignright"><?php next_comments_link(__('Newer Comments &raquo;', 'vigilance')); ?></div>
	</div>
	<?php if ( ! empty($comments_by_type['pings']) ) : ?>
		<h3 class="pinghead"><?php _e('Trackbacks', 'vigilance'); ?></h3>
		<ol class="pinglist">
			<?php wp_list_comments('type=pings&callback=list_pings'); ?>
		</ol>
	<?php endif; ?>
	<?php if ( !comments_open() ) : ?>
		<p class="note"><?php _e('Comments are closed.', 'vigilance'); ?></p>
	<?php endif; ?>
<?php else : // this is displayed if there are no comments so far ?>
	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->
		<div class="comment-number">
			<span><?php _e('No comments yet', 'vigilance'); ?></span>
		</div>
	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<?php if (!is_page()) : ?>
			<p class="note"><?php _e('Comments are closed.', 'vigilance'); ?></p>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>
</div><!--end comments-->

<?php comment_form(); ?>
