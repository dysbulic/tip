<?php
	// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.','mistylook'); ?></p>
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>
<h3 id="comments"><?php comments_number(__('No Responses Yet','mistylook'), __('One Response','mistylook'), __('% Responses','mistylook'));?></h3>

	<ol class="commentlist">
	<?php wp_list_comments(array('callback' => 'mistylook_comment')); ?>
	</ol>

	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	<br />

  <?php if (!comments_open()) : ?> 
	<p class="nocomments"><?php _e('Comments are closed.','mistylook'); ?></p>
  <?php endif; ?>
<?php endif; ?>
<div class="post-content">
<p>
<?php if (comments_open()) {?>
	<span class="commentsfeed"><?php post_comments_feed_link( __( 'Comments RSS', 'mistylook' ) ); ?></span>
<?php }; ?>
</p>
</div>

<?php if ( comments_open() ) : ?>

<?php comment_form(); ?>

<?php endif; // if you delete this the sky will fall on your head ?>