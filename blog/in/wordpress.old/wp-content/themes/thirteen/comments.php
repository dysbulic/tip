<?php // Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments">This post is password protected. Enter the password to view comments.<p>
<?php
	return;
}

if (have_comments()) : ?>
	<h3 id="comments"><?php comments_number(__('Comments'), __('1 Comment'), __('% Comments'));?></h3>

	<ol id="commentlist">
	<?php wp_list_comments(array('callback'=>'thirteen_comment')); ?>
	</ol>
	
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	<br />

 <?php else : // this is displayed if there are no comments so far ?>

  <?php if (comments_open()) : ?> 
		<!-- If comments are open, but there are no comments. -->
		
	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		
	<?php endif; ?>
<?php endif; ?>

<?php comment_form(); ?>