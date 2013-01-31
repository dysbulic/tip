<?php // This is the one template that is based on Kubrick, simply because it does it so well. Thanks Michael!

if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');
if ( post_password_required() ) {
?>
<p class="nocomments"><?php _e("This post is password protected. Enter the password to view comments."); ?></p>
<?php
	return;
}

if (have_comments()) : ?>

	<h2 id="comments"><?php comments_number(__('Leave a Comment'), __('One Comment'), __('% Comments'));?></h2> 
	<ol id="commentlist">
	<?php wp_list_comments(array('callback'=>'fresh_bananas_comment', 'avatar_size'=>48)); ?>
	</ol>
	
	<div class="navigation">
		<p class="alignleft"><?php previous_comments_link() ?></p>
		<p class="alignright"><?php next_comments_link() ?></p>
	</div>
	
 <?php else : // no comments yet ?>

  <?php if (comments_open()) : ?> 
		<!-- If comments are open, but there are no comments. -->
		<h3><?php _e('Leave a Comment'); ?></h3>
		<p><?php _e('Be the first to comment!'); ?></p>
	<?php endif; ?>
<?php endif; ?>

<?php if (comments_open()) : ?>

<?php comment_form(); ?>

<?php endif; // need to be registered ?>

	<?php if (pings_open() ) { ?>
	<?php // Show the trackback address if ping is enabled ?>
	<p><a href="<?php trackback_url(); ?>" title="<?php esc_attr_e( 'Trackback URI' ); ?>"><?php _e('Trackback URI'); ?></a></p>
<?php } ?>
