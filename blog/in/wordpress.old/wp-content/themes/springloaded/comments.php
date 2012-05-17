<?php // Do not delete these lines
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->
<div class="comments-show">


<?php if ( have_comments() ) { ?>	
	<h3 id="comments"><?php comments_number(__('No responses yet to this post.'), __('One response to this post.'), __('% responses to this post.') );?></h3>

	<ol class="commentlist">

	<?php wp_list_comments(array('callback' => 'springloaded_comment')); ?>

	</ol>

	<div class="prev-next">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>

<?php } ?>


	<?php if ( comments_open() ) : ?>
		<!-- If comments are open, but there are no comments. -->

	 <?php elseif( have_comments() && !comments_open() ) : // comments are closed ?>
		<!-- If comments are closed. -->
		<p class="nocomments"><?php _e('Comments are closed.'); ?></p>

	<?php endif; ?>


<?php comment_form(); ?>

</div>