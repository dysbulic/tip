<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e("This post is password protected. Enter the password to view comments."); ?></p>
<?php
	return;
}
?>
<div id="commentblock">
  <!--comments area-->
  <?php if ( have_comments() || comments_open() ) { ?>
  <h2 id="comments">
    <?php comments_number(__('No comments yet'), __('1 comment so far'), __('% comments so far')); ?>
  </h2>
  <?php } ?>

  <?php if ( have_comments() ) : ?>
	  <ol class="commentlist" id='commentlist'>
	  <?php wp_list_comments(array('callback'=>'light_comment', 'avatar_size'=>16)); ?>
	  </ol>
	  <div class="commentnav">
	    	<div class="alignleft"><?php previous_comments_link() ?></div>
    		<div class="alignright"><?php next_comments_link() ?></div>
  	</div>
  	<br />
	<?php if ( !comments_open() ) { ?>
		<p><?php _e('Comments are closed.'); ?></p>
	<?php } ?>
  <?php endif; ?>

  <div id="loading" style="display: none;"><?php _e('Posting your comment.'); ?></div>
  <div id="errors"></div>
  <?php if (comments_open()) : ?>

	<?php comment_form(); ?>

  <?php endif; // if you delete this the sky will fall on your head ?>
</div>
